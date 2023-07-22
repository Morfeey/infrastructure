<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

class StringValue implements InnerEssenceInterface
{
    public function __construct(private ?string $result = null)
    {
    }

    public function setString(string $string): self
    {
        $this->result = $string;

        return $this;
    }

    public function replace(string $search, string $replace = ''): static
    {
        $this->result = str_replace($search, $replace, $this->result);

        return $this;
    }

    public function toUp(): static
    {
        $this->result = strtoupper($this->result);

        return $this;
    }

    public function toLow(): static
    {
        $this->result = strtolower($this->result);

        return $this;
    }

    public function firstCharUp(): static
    {
        $this->result = ucfirst($this->result);

        return $this;
    }

    public function firstCharLow(): static
    {
        $this->result = lcfirst($this->result);

        return $this;
    }

    public function explode(string $delimiter): array
    {
        $result = explode($delimiter, $this->getResult());

        return array_diff($result, [''], [null]);
    }

    public function split(string ...$chars): array
    {
        $getSlashedCharsString  = function () use ($chars) {
            $result = "";
            foreach ($chars as $char) {
                $result .= "\\$char";
            }
            return $result;
        };
        $pattern = "/[{$getSlashedCharsString()}]/";
        return preg_split($pattern, $this->getResult());
    }

    public function splitOnRegular($pattern)
    {
        return preg_split($pattern, $this->getResult());
    }

    public function toCamelCase(bool $firstIsUp = true): static
    {
        $split = $this->split('-', '_');
        if (count($split)>0) {
            $result = "";
            foreach ($split as $key => $item) {
                if (!$firstIsUp && $key === 0) {
                    $result .=
                        (new self($item))
                            ->getResult();
                } else {
                    $result .=
                        (new self($item))
                            ->firstCharUp()
                            ->getResult();
                }
            }
            $this->result = $result;
        }
        return $this;
    }

    public function toSnakeCase(): static
    {
        $this->toDelimiterCase('_');
        return $this;
    }

    public function toKebabCase(): static
    {
        $this->toDelimiterCase('-');
        return $this;
    }

    protected function toDelimiterCase(string $delimiter) :self
    {
        $chars = (new self($this->getResult()))->replace(' ', $delimiter)->toCamelCase()->getChars();
        $result = "";
        $temp = [];
        foreach ($chars as $char) {
            if ($char->isUp()) {
                $result .= "$delimiter{$char->toLow()->getResult()}";
            } else {
                $result .= $char->getResult();
            }
        }

        $result = ltrim($result, $delimiter);
        $this->result = $result;
        return $this;
    }

    /**
     * @return Char[]
     * @throws \Exception
     */
    public function getChars(): array
    {
        $result = [];
        $matches = [];
        preg_match_all("/./", $this->getResult(), $matches);
        foreach ($matches[0] as $item) {
            $result [] = new Char($item);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getResult() : string
    {
        return $this->result;
    }

    public function isContains(string $Item) :bool
    {
        $str = stristr($this->result, $Item);
        $result = (strlen($str) === 0 || !$str);
        return !$result;
    }

    public function isContained(string $inItem) :bool
    {
        $str = stristr($inItem, $this->result);
        $result = (strlen($str) === 0 || !$str);
        return !$result;
    }

    public function startsWith(string $needle): bool
    {
        return str_starts_with($this->result, $needle);
    }

    public function endsWith(string $needle): bool
    {
        return str_ends_with($this->result, $needle);
    }
}
