<?php

declare(strict_types=1);

namespace Validation;

final class Pass extends Result
{
    public function map(callable $f): Result
    {
        return pass($f($this->value()));
    }

    public function bind(callable $f): Result
    {
        return $f($this->value());
    }
}
