<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase
{
    /**
     * @dataProvider returnProviders
     */
    public function testIsValid($value, $expectedResult): void
    {
        $creditCardNumberValidator = new CreditCardNumberValidator($value);

        $isValid = $creditCardNumberValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public function returnProviders(): array
    {
        return [
            "shouldBeValidWhenValueACreditCard" => ["value" => 5565533959534328, "expectedResult" => true],
            "shouldBeValidWhenValueACreditCardAsString" => ["value" => "5565533959534328", "expectedResult" => true],
            "shouldNotBeValidWhenValueIsNotACreditCard" => ["value" => 324325, "expectedResult" => false],
            "shouldNotBeValidWhenValueIsEmpty" => ["value" => "", "expectedResult" => false],
        ];
    }
}
