<?php

declare(strict_types=1);

namespace Domain;

final class Address
{
    private Street $street;
    private Street2 $street2;
    private City $city;
    private ZipCode $zipCode;

    public function __construct(Street $street, Street2 $street2, City $city, ZipCode $zipCode)
    {
        $this->street = $street;
        $this->street2 = $street2;
        $this->city = $city;
        $this->zipCode = $zipCode;
    }

    public function street(): Street
    {
        return $this->street;
    }

    public function street2(): Street2
    {
        return $this->street2;
    }

    public function city(): City
    {
        return $this->city;
    }

    public function zipCode(): ZipCode
    {
        return $this->zipCode;
    }
}
