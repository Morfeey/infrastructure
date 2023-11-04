<?php

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum;

enum ConditionTypeEnum: int
{
    case EQUALS = 1;
    case NOT_EQUALS = 2;

    case LIKE = 3;
    case NOT_LIKE = 4;

    case IN = 5;
    case NOT_IN = 6;

    case BETWEEN = 7;
    case NOT_BETWEEN = 8;

    case IS_NULL = 9;
    case IS_NOT_NULL = 10;

    case GREATER = 11;
    case GREATER_THAN_OR_EQUAL_TO = 12;

    case LESS = 13;
    case LESS_THAN_OR_EQUAL_TO = 14;

    public function toInt(): int
    {
        return $this->value;
    }
}
