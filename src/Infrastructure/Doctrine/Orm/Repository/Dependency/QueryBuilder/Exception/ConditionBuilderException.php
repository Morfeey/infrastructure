<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Exception;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\InvalidArgumentException\InvalidArgumentExceptionInterface;

class ConditionBuilderException extends DefaultException implements InvalidArgumentExceptionInterface
{
    public function __construct(Condition $condition, string $message = '')
    {
        $fieldStringMessage = "Field: {$condition->getField()->getFieldString()}";
        $fieldValueJson = json_encode($condition->getValue());
        $fieldConditionMessage = "value: {$fieldValueJson}";
        $conditionTypeMessage = "condition type: {$condition->getType()->name}";
        $conditionWhereType = "condition where type: {$condition->getWhereType()->name}";

        $message .= " {$fieldStringMessage}, {$fieldConditionMessage}, {$conditionTypeMessage}, {$conditionWhereType}.";
        parent::__construct($message);
    }
}
