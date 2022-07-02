<?php

class DiscountCalculatorTest
{
    public function shouldApplyWhenValueIsAboveTheMinimumTest(): void
    {
        $discountCalculator = new DiscountCalculator();

        $totalValue = 130;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $expectedValue = 110;
        $this->assertEquals($expectedValue, $totalWithDiscount);
    }

    public function shouldNotApplyWhenValueIsBellowTheMinimumTest(): void
    {
        $discountCalculator = new DiscountCalculator();

        $totalValue = 90;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $expectedValue = 90;
        $this->assertEquals($expectedValue, $totalWithDiscount);
    }

    private function assertEquals(float $expectedValue, float $actualValue): void
    {
        if ($expectedValue !== $actualValue) {
            $message = "Expected: {$expectedValue} but got: {$actualValue}";
            throw new \Exception($message);
        }

        echo "Test passed! \n";
    }
}