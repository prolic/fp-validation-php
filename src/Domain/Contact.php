<?php

declare(strict_types=1);

namespace Domain;

final class Contact
{
    private FirstName $firstName;
    private LastName $lastName;
    private Email $email;
    private PhoneNumber $phoneNumber;
    private Address $address;

    public function __construct(
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        PhoneNumber $phoneNumber,
        Address $address
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function phoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function address(): Address
    {
        return $this->address;
    }
}
