<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';


function test (string $a, callable $f)
{
    return $f($a);
}

const ucfirst = 'ucfirst';

echo test('test', ucfirst);