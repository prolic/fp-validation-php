<?php

declare(strict_types=1);

namespace Validation;

final class Error extends Result
{
    public function map(callable $f): Result
    {
        return $this;
    }

    public function bind(callable $f): Result
    {
        return $this;
    }
}
