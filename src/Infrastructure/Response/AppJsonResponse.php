<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class AppJsonResponse extends JsonResponse
{
    public function __construct(mixed $data = [], mixed $meta = [], int $status = 200, array $headers = [], bool $json = false)
    {
        $data = [
            'meta' => $meta,
            'data' => $data,
        ];
        parent::__construct($data, $status, $headers, $json);
    }
}