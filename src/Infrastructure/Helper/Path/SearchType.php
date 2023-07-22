<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

class SearchType implements PathOptionInterface
{
    private bool $searchIsRecurse;

    public static function searchOnlyHere(): static
    {
        return new self();
    }
    public static function searchRecurse(): static
    {
        return new self(true);
    }

    public function isRecurse(): bool
    {
        return $this->searchIsRecurse;
    }

    private function __construct(bool $searchIsRecurse = false)
    {
        $this->searchIsRecurse = $searchIsRecurse;
    }
}
