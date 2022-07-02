<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;
use DateTime;

class CreditCardExpirationValidatorTest extends TestCase
{
    /**
     * @dataProvider returnProviders
     */
    public function testIsValid($value, $expectedResult): void
    {
        $creditCardExpirationDate = new DateTime($value);
        $creditCardExpirationValidate = new CreditCardExpirationValidator($creditCardExpirationDate);

        $isValid = $creditCardExpirationValidate->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public function returnProviders(): array
    {
        return [
            "shouldBeValidWhenDateIsNotExpired" => ["value" => "2025-01-01", "expectedResult" => true],
            "shouldBeValidWhenDateIsExpired" => ["value" => "2020-01-01", "expectedResult" => false]
        ];
    }
}
