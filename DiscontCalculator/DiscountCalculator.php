<?php

class DiscountCalculator 
{
    const MINIMUM_VALUE = 100;
    const DISCONT_VALUE = 20;

    public function apply(float $value): float
    {
        if($value >= self::MINIMUM_VALUE) {
            return $value - self::DISCONT_VALUE;
        }

        return $value;
    }
}