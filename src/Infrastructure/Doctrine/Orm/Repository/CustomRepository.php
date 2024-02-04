<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\DefaultFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Domain\Entity\DomainEntityInterface;
use App\Bundles\InfrastructureBundle\Domain\Repository\IsolationLevel\TransactionIsolationLevelInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity\CustomEntityInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\IsolationLevel\DoctrineIsolationLevel;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\DefaultDependencyForRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\NotFound\NotFoundException;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\Collection;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;
use Closure;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Throwable;

/**
 * Optional EntityRepository base class with a simplified constructor (for autowiring).
 *
 * To use in your class, inject the "registry" service and call
 * the parent constructor. For example:
 *
 * class YourEntityRepository extends ServiceEntityRepository
 * {
 *     public function __construct(ManagerRegistry $registry)
 *     {
 *         parent::__construct($registry, YourEntity::class);
 *     }
 * }
 *
 * @template T
 * @template-extends EntityRepository<T>
 */
class CustomRepository implements DoctrineRepositoryInterface
{
    public const DELETE_MAX_CHUNK = 5000;
    protected CustomEntityInterface $prototype;
    protected EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param DefaultDependencyForRepositoryInterface $defaultDependencyForRepository
     * @param string $entityClass                    The class name of the entity this repository manages
     */
    public function __construct(
        ManagerRegistry $registry,
        private readonly DefaultDependencyForRepositoryInterface $defaultDependencyForRepository,
        private string $entityClass
    ) {
        if (!( new $entityClass() instanceof CustomEntityInterface)) {
            throw new LogicException('Your entity class is not Implemented ' . CustomEntityInterface::class . " ({$entityClass})");
        }

        $manager = $registry->getManagerForClass($entityClass);
        if ($manager === null) {
            throw new LogicException(sprintf(
                'Could not find the entity manager for class "%s". Check your Doctrine configuration to make sure it is configured to load this entity’s metadata.',
                $entityClass
            ));
        }

        /** @var EntityManagerInterface $manager */
        $this->entityManager = $manager;
    }

    public function findAll(): CollectionInterface
    {
        return $this->findBy([]);
    }

    public function findAllIterable(): iterable
    {
        $query = $this->entityManager
            ->createQueryBuilder()
            ->select($this->getEntityPrefix())
            ->from($this->entityClass, $this->getEntityPrefix())
            ->getQuery()
        ;

        $iterator = $query->toIterable();
        foreach ($iterator as $item) {
            yield $item;
        }
    }

    /**
     * @deprecated Не стоит пользоваться данным методом
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): CollectionInterface
    {
        $persist = $this->entityManager->getUnitOfWork()->getEntityPersister($this->getClassName());

        return new Collection($persist->loadAll($criteria, $orderBy, $limit, $offset));
    }

    public function find($id): ?CustomEntityInterface
    {
        return $this->entityManager->find($this->getClassName(), $id);
    }

    /**
     * @deprecated Не стоит пользоваться данным методом
     */
    public function findOneBy(array $criteria)
    {
        $persist = $this->entityManager->getUnitOfWork()->getEntityPersister($this->getClassName());

        return $persist->load($criteria, null, null, [], null, 1);
    }

    public function getClassName(): string
    {
        return $this->entityClass;
    }

    public function getEntityPrefix(): string
    {
        /** @var $entity CustomEntityInterface */
        $entity = $this->getClassName();

        return $entity::prefix();
    }

    public function deleteByFilter(DefaultFilterInterface $filter): static
    {
        $this->buildDefaultSelectQueryBuilderByFilter($filter)
            ->delete()
            ->getQuery()
            ->execute();

        return $this;
    }

    public function findByFilter(DefaultFilterInterface $filter): CollectionInterface
    {
        $collection = new Collection();
        if ($filter->isEmpty()) {
            return $collection;
        }

        foreach ($this->findByFilterIterable($filter) as $item) {
            $collection->add($item);
        }

        return $collection;
    }

    public function findByFilterIterable(DefaultFilterInterface $filter): iterable
    {
        if ($filter->isEmpty()) {
            yield;
        }

        $query = $this->buildDefaultSelectQueryBuilderByFilter($filter)->getQuery();
        foreach ($query->toIterable() as $item) {
            yield $item;
        }
    }

    public function findOneByFilter(DefaultFilterInterface $filter): ?CustomEntityInterface
    {
        return $this->findByFilter($filter)?->first();
    }

    public function requireOneByFilter(DefaultFilterInterface $filter): CustomEntityInterface
    {
        return $this->findOneByFilter($filter)
            ?: throw new NotFoundException('Entity not found: ' . $this->entityClass);
    }

    public function findByFilterFieldList(DefaultFilterInterface $filter, ContractEntityFieldListInterface ...$fieldList): array
    {
        return $this->buildQuery(
            $this->buildDefaultSelectQueryBuilderByFilter($filter, ...$fieldList),
            $filter
        )
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return iterable<array>
    */
    public function findByFilterFieldListIterable(DefaultFilterInterface $filter, ContractEntityFieldListInterface ...$fieldList): iterable
    {
        return $this->buildQuery(
            $this->buildDefaultSelectQueryBuilderByFilter($filter, ...$fieldList),
            $filter
        )
            ->getQuery()
            ->toIterable([], AbstractQuery::HYDRATE_ARRAY)
        ;
    }

    public function findByFilterField(DefaultFilterInterface $filter, ContractEntityFieldListInterface $field): array
    {
        return $this->buildQuery(
            $this->buildDefaultSelectQueryBuilderByFilter($filter, $field),
            $filter
        )
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findByFilterSingleField(DefaultFilterInterface $filter, ContractEntityFieldListInterface $field): mixed
    {
        $fields = $this->findByFilterField($filter, $field);
        if (!count($fields)) {
            return null;
        }

        return current($fields);
    }

    public function updateByFilter(DefaultFilterInterface $filter, ContractEntityFieldListInterface ...$fieldList): static
    {
        if (empty($fieldList)) {
            return $this;
        }


        $qb = $this->buildDefaultSelectQueryBuilderByFilter($filter, ...$fieldList)
            ->update($this->entityClass, $this->getEntityPrefix());

        foreach ($fieldList as $index => $field) {
            $parameter = "{$this->getEntityPrefix()}.{$field->getFieldString()}";
            $placeholder = "{$this->getEntityPrefix()}_{$index}_{$field->getFieldString()}";
            $qb->set($parameter, ':' . $placeholder)->setParameter($placeholder, $field->getValue());
        }

        $query = $qb->getQuery();
        $query->execute();

        return $this;
    }

    private function buildDefaultSelectQueryBuilderByFilter(DefaultFilterInterface $filter, ContractEntityFieldListInterface ...$fieldList): DoctrineQueryBuilder
    {
        $qb = $this->entityManager
            ->createQueryBuilder()
            ->select($this->getEntityPrefix())
            ->from($this->entityClass, $this->getEntityPrefix());

        if (count($fieldList)) {
            $fields = [];
            foreach ($fieldList as $domainField) {
                $fields[] = "{$this->getEntityPrefix()}.{$domainField->getFieldString()}";
            }
            $qb->select($fields);
        }

        if ($filter->getLimit()) {
            $qb->setMaxResults($filter->getLimit());
        }

        if ($filter->getOffset()) {
            $qb->setFirstResult($filter->getOffset());
        }

        return $this->buildQuery($qb, $filter);
    }

    public function getCount(): int
    {
        $identification = $this->getEntityPrefix() . '.'. $this->getIdFieldName();

        $query =
            $this->entityManager
                ->createQueryBuilder()
                ->select("count({$identification})")
                ->from($this->getClassName(), $this->getEntityPrefix());

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getCountByFilter(DefaultFilterInterface $filter): int
    {
        return
            $this->buildDefaultSelectQueryBuilderByFilter($filter)
                ->select("count({$this->getEntityPrefix()}.{$this->getIdFieldName()})")
                ->getQuery()
                ->getSingleScalarResult();
    }

    private function buildQuery(DoctrineQueryBuilder $queryBuilder, DefaultFilterInterface $filter): DoctrineQueryBuilder
    {
        if ($filter->isEmpty()) {
            return $queryBuilder;
        }

        return $this->defaultDependencyForRepository
            ->getQueryBuilderProcessService()
            ->processByFilter($queryBuilder, $filter);
    }

    public function saveCollection(CollectionInterface $entityCollection, bool $inTransaction = false): static
    {
        return $this->saveList($entityCollection->toArray(), $inTransaction);
    }

    public function saveList(array $listItems, bool $inTransaction = false): static
    {
        if (!count($listItems)) {
            return $this;
        }

        foreach ($listItems as $item) {
            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();
        //        $this->entityManager->clear();

        return $this;
    }

    public function save(DomainEntityInterface $entity): static
    {
        $isNeedClear = true;
        $meta = $this->entityManager->getClassMetadata($entity::class);
        foreach ($meta->getFieldNames() as $fieldName) {
            $value = $meta->getFieldValue($entity, $fieldName);
            if ($value instanceof DomainEntityInterface || $value instanceof DoctrineCollection) {
                $isNeedClear = false;
                break;
            }
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        if (!$isNeedClear) {
            return $this;
        }

        $this->entityManager->clear();

        return $this;
    }

    public function getTableName(?string $entityName = null): string
    {
        return $this->entityManager->getClassMetadata($entityName ?? $this->getClassName())->getTableName();
    }

    /**
     * @inheritDoc
     */
    public function commit(): static
    {
        $this->entityManager->commit();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rollback(): static
    {
        $this->entityManager->rollback();

        return $this;
    }

    public function create(): CustomEntityInterface
    {
        $entityClass = $this->getClassName();
        $this->prototype = $this->prototype ?? new $entityClass();

        return clone $this->prototype;
    }

    /**
     * @throws Throwable
     */
    public function transactionStart(?TransactionIsolationLevelInterface $level = null): static
    {
        $level = $level ?? DoctrineIsolationLevel::repeatableRead();
        $this->entityManager->beginTransaction();
        if ($level->getValue() === DoctrineIsolationLevel::repeatableRead()->getValue()) {
            return $this;
        }

        $this->entityManager->getConnection()->setTransactionIsolation($level->getValue());

        return $this;
    }

    public function findIdListByFilter(DefaultFilterInterface $filter): array
    {
        return
            $this
                ->buildDefaultSelectQueryBuilderByFilter($filter)
                ->select(["{$this->getEntityPrefix()}.{$this->getIdFieldName()}"])
                ->getQuery()
                ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    /**
     * @throws Throwable
     */
    public function clear(): static
    {
        return $this->transactional(function () {
            $foreignKeysChecksDisableSql = 'SET FOREIGN_KEY_CHECKS = 0;';
            $foreignKeysChecksEnableSql = 'SET FOREIGN_KEY_CHECKS = 1;';
            $sql = "DELETE FROM {$this->getTableName()} LIMIT " . self::DELETE_MAX_CHUNK . ';';

            $this->entityManager->getConnection()->exec($foreignKeysChecksDisableSql);
            $statement = $this->entityManager->getConnection()->prepare($sql);

            do {
                $deletedRowsCount = $statement->execute()->rowCount();
            } while ($deletedRowsCount > 0);

            $this->entityManager->getConnection()->exec($foreignKeysChecksEnableSql);
        });
    }

    /**
     * @throws Throwable
     */
    public function truncate(): static
    {
        $this->entityManager->getConnection()->prepare("TRUNCATE {$this->getTableName()}")->execute();

        return $this;
    }

    public function delete(DomainEntityInterface $entity): static
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function deleteCollection(CollectionInterface $entityCollection): static
    {
        return $this->transactional(
            function () use ($entityCollection) {
                $chunkedCollection = array_chunk($entityCollection->toArray(), static::DELETE_MAX_CHUNK);
                foreach ($chunkedCollection as $entityList) {
                    foreach ($entityList as $entity) {
                        $this->entityManager->remove($entity);
                    }

                    $this->entityManager->flush();
                }
            }
        );
    }

    public function isActiveTransaction(): bool
    {
        return $this->entityManager->getConnection()->isTransactionActive();
    }

    /**
     * @throws Throwable
     */
    public function deleteByIdList(array $idList): static
    {
        if (!count($idList)) {
            return $this;
        }

        return $this->transactional(
            function () use ($idList) {
                $chunkedIdList = array_chunk($idList, self::DELETE_MAX_CHUNK);
                $queryBuilder = $this->entityManager->createQueryBuilder();
                foreach ($chunkedIdList as $idList) {
                    $query =
                        $queryBuilder
                            ->delete($this->getEntityPrefix())
                            ->from($this->entityClass, $this->getEntityPrefix())
                            ->where($queryBuilder->expr()->in(
                                "{$this->getEntityPrefix()}.{$this->getIdFieldName()}",
                                $idList
                                ));

                    $query->getQuery()->execute();
                }
            }
        );
    }

    public function update(DomainEntityInterface $entity): static
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function transactional(Closure $closure, ?TransactionIsolationLevelInterface $isolationLevel = null): static
    {
        $isActiveTransaction = $this->isActiveTransaction();
        try {
            if (!$isActiveTransaction) {
                $this->transactionStart($isolationLevel);
            }

            $closure($this);

            if (!$isActiveTransaction) {
                $this->commit();
            }
        } catch (Throwable $throwable) {
            if (!$isActiveTransaction) {
                $this->rollback();
            }
            throw $throwable;
        }

        return $this;
    }

    /**
     * @throws MappingException
     */
    public function getIdFieldName(): string
    {
        return $this->entityManager->getClassMetadata($this->getClassName())->getSingleIdentifierFieldName();
    }

    public function findByIdentifier(mixed $identification): ?CustomEntityInterface
    {
        $prefix = $this->getEntityPrefix() . '.';
        $query = $this->entityManager->createQueryBuilder()
            ->select($this->getEntityPrefix())
            ->from($this->getClassName(), $this->getEntityPrefix())
            ->andWhere($prefix . $this->getIdFieldName() . ' = ' . $identification);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function max(?DefaultFilterInterface $filter = null, ?ContractEntityFieldListInterface $field = null): int
    {
        $fieldName = $field ? $field->getFieldString() : $this->getIdFieldName();
        $prefixedField = $this->getEntityPrefix() . '.' . $fieldName;

        $query = (
            $filter
                ? $this->buildQuery($this->buildDefaultSelectQueryBuilderByFilter($filter), $filter)
                : $this->entityManager->createQueryBuilder()
        )
            ->select("max({$prefixedField})")
            ->from($this->getClassName(), $this->getEntityPrefix());

        return $query->getQuery()->getSingleScalarResult();
    }

    public function min(?DefaultFilterInterface $filter = null, ?ContractEntityFieldListInterface $field = null): int
    {
        $fieldName = $field ? $field->getFieldString() : $this->getIdFieldName();
        $prefixedField = $this->getEntityPrefix() . '.' . $fieldName;

        $query = (
        $filter
            ? $this->buildQuery($this->buildDefaultSelectQueryBuilderByFilter($filter), $filter)
            : $this->entityManager->createQueryBuilder()
        )
            ->select("min({$prefixedField})")
            ->from($this->getClassName(), $this->getEntityPrefix());

        return $query->getQuery()->getSingleScalarResult();
    }
}
