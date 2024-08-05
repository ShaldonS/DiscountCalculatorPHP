<?php

namespace App\Service;
use DateTime;

class DiscountCalculator
{
    public function calculateDiscount($basePrice, $birthDate, $startDate = null, $paymentDate = null) {
        if ($startDate == null) return (int)$basePrice;

        $finalPrice = $basePrice;
        $birthDate = new DateTime($birthDate);
        $startDate = new DateTime($startDate);

        $age = $startDate->diff($birthDate)->y;

        if ($age < 18) {
            $finalPrice = $this->getAgeDiscount($finalPrice, $age);
        }

        if ($paymentDate) {
            $finalPrice = $this->getEarlyBookingDiscount($finalPrice, $startDate, $paymentDate);
        }

        return (int)$finalPrice;
    }

    private function getAgeDiscount($basePrice, $age) {
        if ($age < 3) {
            return $basePrice;
        } elseif ($age < 6) {
            return $basePrice * 0.2;
        } elseif ($age < 12) {
            return max($basePrice - 4500, $basePrice * 0.7);
        } elseif ($age < 18) {
            return $basePrice * 0.9;
        }

        return $basePrice; 
    }

    private function getEarlyBookingDiscount($basePrice, $startDate, $paymentDate) {
        $paymentDate = new DateTime($paymentDate);

        $factor = 1;

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $paymentYear= (int)$paymentDate->format('Y');
        $paymentMonth = (int)$paymentDate->format('m');

        $aprNextYearDate = new DateTime("$nextYear-04-01");
        $sepNextYearDate = new DateTime("$nextYear-09-30");
        $octCurYearDate = new DateTime("$currentYear-10-01");
        $jan14NextYearDate = new DateTime("$nextYear-01-14");
        $jan15NextYearDate = new DateTime("$nextYear-01-15");

        if ($startDate >= $aprNextYearDate && $startDate <= $sepNextYearDate) { // 1 apr next year -> 30 sept next year
            if ($paymentYear == $currentYear) {
                if ($paymentMonth <= 11) { // jan -> nov
                    $factor = 0.07;
                } elseif ($paymentMonth == 12) { // dec
                    $factor = 0.05;
                }
            } elseif ($paymentYear == $nextYear && $paymentMonth == 1) { // jan next year
                    $factor = 0.03;
            }
        } elseif ($startDate >= $octCurYearDate && $startDate <= $jan14NextYearDate) { // 1 oct -> 14 jan next year
            if ($paymentYear == $currentYear) {
                if ($paymentMonth <= 3) { // jan -> march
                    $factor = 0.07;
                } elseif ($paymentMonth == 4) { // apr
                    $factor = 0.05;
                } elseif ($paymentMonth == 5) { // may
                    $factor = 0.03;
                }
            }
        } elseif ($startDate >= $jan15NextYearDate) { // 15 jan -> happy future
            if ($paymentYear == $currentYear) {
                if ($paymentMonth <= 8) { // jan -> sep
                    $factor = 0.07;
                } elseif ($paymentMonth == 9) { // sep
                    $factor = 0.05;
                } elseif ($paymentMonth == 10) { // oct
                    $factor = 0.03;
                }
            }
        }
        $priceWithDisc = min($basePrice * $factor, 1500);

        return $basePrice - $priceWithDisc; 
    }
}
