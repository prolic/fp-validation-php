<?php

declare(strict_types=1);

namespace Validation;

abstract class Result
{
    /** @var mixed */
    private $value;

    /** @param mixed $value */
    final public function __construct($value)
    {
        if (! $this instanceof Error
            && ! $this instanceof Pass
        ) {
            throw new \TypeError('Result can only be Error or Pass');
        }

        $this->value = $value;
    }

    /** @return mixed */
    public function value()
    {
        return $this->value;
    }

    abstract public function map(callable $f): Result;

    abstract public function bind(callable $f): Result;
}