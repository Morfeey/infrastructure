<?php

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum;

enum SortTypeEnum: int
{
    case ASC = 1;
    case DESC = 2;

    public function toInt(): int
    {
        return $this->value;
    }
}
