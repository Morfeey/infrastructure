<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

class PathHandler implements WayInterface, InnerEssenceInterface
{
    use HelperPath;

    protected array $ways;
    protected string $resultWay;

    public function subLastSlash(): self
    {
        $this->resultWay = self::deleteLastSlashCustom($this->resultWay);
        return $this;
    }

    public function addLastSlash(): self
    {
        $this->resultWay = self::getLastSlashCustom($this->resultWay);
        return $this;
    }

    public function add(string $way): self
    {
        $this->ways [] = $way;
        $this->build();
        return $this;
    }

    public function subtract(string $way): self
    {
        $ways = $this->ways;
        if (in_array($way, $ways)) {
            $key = array_search($way, $ways);
            unset($ways[$key]);
        }
        $this->ways = $ways;
        $this->build();
        return $this;
    }

    private function build(): static
    {
        $result = "";
        foreach ($this->ways as $way) {
            $result .= self::getLastSlashCustom($way);
        }
        $this->resultWay = $result;
        return $this;
    }

    public function getResult(): string
    {
        return $this->resultWay;
    }

    public function isExists(): bool
    {
        $way = $this->getResult();
        $result = (is_dir($way) || is_file($way));
        return $result;
    }

    public function __construct(array $ways = null)
    {
        if (!is_null($ways)) {
            if (count($ways)!==0) {
                $this->ways = $ways;
                $this->build();
            }
        } else {
            $this->ways = [];
            $this->resultWay = "";
        }
    }
}
