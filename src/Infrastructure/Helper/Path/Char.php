<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

class Char
{
    private string $char;

    public function toUp(): self
    {
        $this->char = strtoupper($this->char);
        return $this;
    }

    public function isUp(): bool
    {
        return $this->getResult() === (new self($this->getResult()))->toUp()->getResult();
    }

    public function toLow(): self
    {
        $this->char = strtolower($this->char);
        return $this;
    }

    public function isLow(): bool
    {
        return $this->getResult() === (new self($this->getResult()))->toLow()->getResult();
    }

    public function getResult(): string
    {
        return $this->char;
    }

    public function __construct(string $char)
    {
        $length = strlen($char);
        if ($length === 1) {
            return $this->char = $char;
        }

        throw new \Exception("{$char} is not char" . $length);
    }
}
