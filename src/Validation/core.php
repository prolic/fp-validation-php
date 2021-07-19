<?php

declare(strict_types=1);

namespace Validation;

use Domain\Email;
use Domain\PhoneNumber;
use Domain\Product;
use Domain\ZipCode;
use Laminas\I18n\Validator\PhoneNumber as PhoneNumberValidator;
use Laminas\Validator\EmailAddress;

const error = __NAMESPACE__ . '\\error';

function error(array $messages): Error
{
    return new Error($messages);
}

const pass = __NAMESPACE__ . '\\pass';

/** @param mixed $value */
function pass($value): Pass
{
    return new Pass($value);
}

const matchType = __NAMESPACE__ . '\\matchType';

/** @return mixed */
function matchType(object $o, array $patterns, ?callable $default = null)
{
    foreach ($patterns as $type => $fn) {
        if ($o instanceof $type) {
            return $fn();
        }
    }

    if ($default) {
        return $default();
    }

    throw new \TypeError(\sprintf('Patterns for %s not exhaustive', \get_class($o)));
}

const tail = __NAMESPACE__ . '\\tail';

function tail(array $list): array
{
    \array_shift($list);

    return $list;
}

const isString = __NAMESPACE__ . '\\isString';

function isString(array $data, string $field): Result
{
    return \is_string($data[$field] ?? null)
        ? pass($data[$field])
        : error([$field . ': is not a string']);
}

const nonEmptyString = __NAMESPACE__ . '\\nonEmptyString';

function nonEmptyString(array $data, string $field): Result
{
    return isString($data, $field)->bind(fn ($v) => '' === $v ? error([$field . ': expected non-empty string']) : pass($v));
}

const validPhoneNumber = __NAMESPACE__ . '\\validPhoneNumber';

function validPhoneNumber(array $data, string $field): Result
{
    $validator = new PhoneNumberValidator();

    return nonEmptyString($data, $field)
        ->bind(fn ($v) => $validator->isValid($v) ? pass($v) : error(validatorErrorMessages($field, $validator->getMessages())))
        ->map(fn ($v) => new PhoneNumber($v));
}

const validZipCode = __NAMESPACE__ . '\\validZipCode';

function validZipCode(array $data, string $field): Result
{
    return nonEmptyString($data, $field)
        ->map(fn ($v) => new ZipCode($v));
}

const validatorErrorMessages = __NAMESPACE__ . '\\validatorErrorMessages';

function validatorErrorMessages(string $field, array $messages): array
{
    return \array_map(
        fn (string $m) => $field . ': ' . $m,
        \array_values($messages)
    );
}

const andd = __NAMESPACE__ . '\\andd';

function andd(callable $f, Result $a, Result $b): Result
{
    return matchType($a, [
        Error::class => fn () => matchType($b, [
            Error::class => fn () => error(\array_merge($a->value(), $b->value())),
            Pass::class => fn () => $a,
        ]),
        Pass::class => fn () => matchType($b, [
            Error::class => fn () => $b,
            Pass::class => fn () => pass($f($a->value(), $b->value()))
        ]),
    ]);
}

const collect = __NAMESPACE__ . '\\collect';

function collect($a, $b): array
{
    if (\is_array($a)) {
        $a[] = $b;

        return $a;
    }

    return [$a, $b];
}

const all = __NAMESPACE__ . '\\all';

function all(callable $f, Result $e, Result ...$es): Result
{
    switch (\count($es)) {
        case 0:
            return $e->map($f);

        case 1:
            return andd(collect, $e, $es[0])->map($f);

        default:
            return all($f, andd(collect, $e, $es[0]), ...tail($es));
    }
}

const optional = __NAMESPACE__ . '\\optional';

function optional(array $data, string $field, callable $f): Result
{
    return null === ($data[$field] ?? null) ? pass(null) : $f($data, $field);
}

const validEmail = __NAMESPACE__ . '\\validEmail';

function validEmail(array $data, string $field): Result
{
    $validator = new EmailAddress();

    return nonEmptyString($data, $field)
        ->bind(
            fn ($v) => $validator->isValid($v)
                ? pass(new Email($v))
                : error(validatorErrorMessages($field, $validator->getMessages()))
        );
}

const orr = __NAMESPACE__ . '\\orr';

function orr(Result $a, Result $b, string $errorMessage): Result
{
    return matchType($a, [
        Error::class => fn () => matchType($b, [
            Error::class => fn () => error([$errorMessage]),
            Pass::class => fn () => $b,
        ]),
        Pass::class => fn () => $a,
    ]);
}

const isBool = __NAMESPACE__ . '\\isBool';

function isBool(array $data, string $field): Result
{
    return \is_bool($data[$field] ?? null)
        ? pass($data[$field])
        : error([$field . ': bool expected']);
}

const isBooleanish = __NAMESPACE__ . '\\isBooleanish';

function isBooleanish(array $data, string $field): Result
{
    $fallbackError = $field . ': bool expected';

    return orr(
        isBool($data, $field),
        orr(
            nonEmptyString($data, $field)->bind(fn ($v) => \strtolower($v) === 'true' ? pass(true) : error([$fallbackError])),
            nonEmptyString($data, $field)->bind(fn ($v) => \strtolower($v) === 'false' ? pass(false) : error([$fallbackError])),
            $fallbackError
        ),
        $fallbackError
    );
}
