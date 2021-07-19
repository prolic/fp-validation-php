<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Domain\Address;
use Domain\City;
use Domain\Contact;
use Domain\FirstName;
use Domain\LastName;
use Domain\Street;
use Domain\Street2;

$data = [
    'street' => 'Main Street 1',
    'street2' => '',
    'city' => 'Mega City',
    'zipCode' => '12455',
    'firstName' => 'Bob',
    'lastName' => 'Smith',
    'email' => 'valid@email.com',
    'phoneNumber' => '4242587630',
    'bool' => 'FAlSe',
];

$address = \Validation\all(
    fn ($v) => new Address(...$v),
    \Validation\nonEmptyString($data, 'street')->map(fn ($v) => new Street($v)),
    \Validation\optional($data, 'street2', \Validation\isString)->map(fn ($v) => new Street2((string) $v)),
    \Validation\nonEmptyString($data, 'city')->map(fn ($v) => new City($v)),
    \Validation\validZipCode($data, 'zipCode'),
);

//var_dump($address);

$contact = \Validation\all(
    fn ($v) => new Contact(...$v),
    \Validation\nonEmptyString($data, 'firstName')->map(fn ($v) => new FirstName($v)),
    \Validation\nonEmptyString($data, 'lastName')->map(fn ($v) => new LastName($v)),
    \Validation\validEmail($data, 'email'),
    \Validation\validPhoneNumber($data, 'phoneNumber'),
    $address
);

var_dump($contact);

var_dump(\Validation\matchType($contact, [
    \Validation\Error::class => fn () => 'Some error',
    \Validation\Pass::class => fn () => 'All Good',
]));

//var_dump($contact);
