<?php

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum;

enum ConditionWhereType: int
{
    case AND = 1;
    case OR = 2;

    public function toInt(): int
    {
        return $this->value;
    }
}
