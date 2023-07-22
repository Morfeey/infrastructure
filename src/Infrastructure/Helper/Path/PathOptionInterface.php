<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

interface PathOptionInterface
{
    public static function searchRecurse(): static;
    public static function searchOnlyHere(): static;

    public function isRecurse(): bool;
}
