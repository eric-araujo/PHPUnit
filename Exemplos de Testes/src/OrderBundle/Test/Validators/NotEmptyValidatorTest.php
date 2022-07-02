<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    /**
     * @dataProvider returnProviders
     */
    public function testIsValid($value, $expectedResult): void
    {
        $notEmptyValidator = new NotEmptyValidator($value);

        $isValid = $notEmptyValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public function returnProviders(): array
    {
        return [
            "shouldBeValidWhenValueIsNotEmpty" => ["value" => "test", "expectedResult" => true],
            "shouldNotBeValidWhenValueIsEmpty" => ["value" => "", "expectedResult" => false],
        ];
    }
}
