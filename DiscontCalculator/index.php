<?php

require_once "autoloader.php";

$discount = new DiscountCalculator();
echo $discount->apply(110);