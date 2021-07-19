<?php

declare(strict_types=1);

namespace Domain;

final class Product
{
    public const Auto = 0;
    public const Home = 1;
    public const Options = ['Auto', 'Home'];

    private string $name;
    private int $value;

    public static function Auto(): self
    {
        return new self('Auto', 0);
    }

    public static function Home(): self
    {
        return new self('Home', 1);
    }

    private function __construct(string $name, int $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function fromName(string $name): self
    {
        foreach (self::Options as $i => $n) {
            if ($n === $name) {
                return new self($n, $i);
            }
        }

        throw new \InvalidArgumentException('Unknown enum name given');
    }

    public static function fromValue(int $value): self
    {
        if (! isset(self::Options[$value])) {
            throw new \InvalidArgumentException('Unknown enum value given');
        }

        return new self(self::Options[$value], $value);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): int
    {
        return $this->value;
    }
}
