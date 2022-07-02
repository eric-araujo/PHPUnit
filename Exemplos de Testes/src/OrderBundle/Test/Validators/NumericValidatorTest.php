<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * @dataProvider returnProviders
     */
    public function testIsValid($value, $expectedResult): void
    {
        $numericValidator = new NumericValidator($value);

        $isValid = $numericValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public function returnProviders(): array
    {
        return [
            "shouldBeValidWhenValueIsANumber" => ["value" => 20, "expectedResult" => true],
            "shouldBeValidWhenValueIsANumericString" => ["value" => "20", "expectedResult" => true],
            "shouldNotBeValidWhenValueIsNotANumber" => ["value" => "test", "expectedResult" => false],
            "shouldBeValidWhenValueIsEmpty" => ["value" => "", "expectedResult" => false],
        ];
    }
}
