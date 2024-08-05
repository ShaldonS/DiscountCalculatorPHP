<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\DiscountCalculator;

class DiscountCalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new DiscountCalculator();
    }
    public function testCalculateDiscountMayAdult_1() // 7%
    {
        $basePrice = 10000;
        $birthDate = '2000-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2024-11-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.07;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountMayAdult_2() // 5%
    {
        $basePrice = 10000;
        $birthDate = '2000-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2024-12-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.05;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountMayAdult_3() // 3%
    {
        $basePrice = 10000;
        $birthDate = '2000-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2025-01-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.03;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountJanAdult_1() // 7%
    {
        $basePrice = 10000;
        $birthDate = '2000-05-01';
        $startDate = '2025-01-15';
        $paymentDate = '2024-08-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.07;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountJanAdult_2() // 5%
    {
        $basePrice = 10000;
        $birthDate = '2000-05-01';
        $startDate = '2025-01-15';
        $paymentDate = '2024-09-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.05;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountJanAdult_3() // 3%
    {
        $basePrice = 10000;
        $birthDate = '2000-05-01';
        $startDate = '2025-01-15';
        $paymentDate = '2024-10-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.03;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountForChildrenUnder3() // 7% + 0%
    {
        $basePrice = 10000;
        $birthDate = '2023-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2024-11-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);
        
        $percent = 0.07;
        $price = $basePrice - (int)($basePrice * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountForChildrenBetween3And6() // 7% + 80%
    {
        $basePrice = 10000;
        $birthDate = '2020-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2024-11-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.07;
        $price = $basePrice - (int)($basePrice * $percent);
        $percent = 0.8;
        $price = $price - (int)($price * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountForChildrenBetween6And12() // 7% + 30%
    {
        $basePrice = 10000;
        $birthDate = '2014-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2024-11-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.07;
        $price = $basePrice - (int)($basePrice * $percent);
        $percent = 0.3;
        $price = $price - (int)($price * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountForChildrenBetween12And18() // 7% + 10%
    {
        $basePrice = 10000;
        $birthDate = '2008-05-01';
        $startDate = '2025-05-01';
        $paymentDate = '2024-11-26';

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        $percent = 0.07;
        $price = $basePrice - (int)($basePrice * $percent);
        $percent = 0.1;
        $price = $price - (int)($price * $percent);
        $this->assertEquals($price, $finalPrice);
    }
    public function testCalculateDiscountWithoutEmptyStartDate()
    {
        $basePrice = 10000;
        $birthDate = '2010-01-01';
        $startDate = null;
        $paymentDate = null;

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);
        $this->assertEquals($basePrice, $finalPrice);
    }
}


