<?php

namespace OrderBundle\Test\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /**
     * @dataProvider customerAllowedDataProvider
     */
    public function testIsAllowedToOrder($isActive, $isBlocked, $expectedAllowed)
    {
        $customer = new Customer(
            $isActive,
            $isBlocked,
            "Eric Araújo",
            "+5513997174867"
        );

        $isAllowed = $customer->isAllowedToOrder();

        $this->assertEquals($expectedAllowed, $isAllowed);
    }

    public function customerAllowedDataProvider(): array
    {
        return [
            "shouldBeAllowedWhenIsActiveAndNotBlocked" => [
                "isActive" => true,
                "isBlocked" => false,
                "expectedAllowed" => true
            ],
            "shouldNotBeAllowedWhenIsActiveButIsBlocked" => [
                "isActive" => true,
                "isBlocked" => true,
                "expectedAllowed" => false
            ],
            "shouldNotBeAllowedWhenIsNotActive" => [
                "isActive" => false,
                "isBlocked" => false,
                "expectedAllowed" => false
            ],
            "shouldNotBeAllowedWhenIsNotActiveAndIsBlocked" => [
                "isActive" => false,
                "isBlocked" => true,
                "expectedAllowed" => false
            ]
        ];
    }
}