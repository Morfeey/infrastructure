<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

trait HelperPath
{
    public static function getDocumentRoot(bool $isPublicReplaced = false): string
    {
        $root = PHP_SAPI === 'cli' ? getcwd() : $_SERVER["DOCUMENT_ROOT"];
        if ($isPublicReplaced) {
            $count = 1;
            $root = str_replace('/public', '', $root, $count);
        }

        return $root;
    }
    public static function getLastSlashCustom(string $Directory): string
    {
        $Directory = str_replace("\\", DIRECTORY_SEPARATOR, $Directory);
        $Directory = rtrim($Directory, DIRECTORY_SEPARATOR);

        return $Directory . DIRECTORY_SEPARATOR;
    }
    public static function deleteLastSlashCustom(string $Directory): string
    {
        $Directory = str_replace("\\", DIRECTORY_SEPARATOR, $Directory);

        return rtrim($Directory, DIRECTORY_SEPARATOR);
    }
}
