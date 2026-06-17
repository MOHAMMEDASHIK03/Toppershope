<?php

namespace App\Support;

class IndianAmountInWords
{
    private const ONES = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen',
    ];

    private const TENS = [
        '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety',
    ];

    public static function format(float $amount): string
    {
        $rupees = (int) floor($amount);
        $paise = (int) round(($amount - $rupees) * 100);

        if ($rupees === 0 && $paise === 0) {
            return 'Zero Rupees Only';
        }

        $words = self::convertIndian($rupees) . ' Rupees';

        if ($paise > 0) {
            $words .= ' and ' . self::convertIndian($paise) . ' Paise';
        }

        return $words . ' Only';
    }

    private static function convertIndian(int $number): string
    {
        if ($number === 0) {
            return '';
        }

        $parts = [];

        $crore = intdiv($number, 10000000);
        $number %= 10000000;
        $lakh = intdiv($number, 100000);
        $number %= 100000;
        $thousand = intdiv($number, 1000);
        $number %= 1000;
        $hundred = intdiv($number, 100);
        $number %= 100;

        if ($crore > 0) {
            $parts[] = self::convertHundreds($crore) . ' Crore';
        }
        if ($lakh > 0) {
            $parts[] = self::convertHundreds($lakh) . ' Lakh';
        }
        if ($thousand > 0) {
            $parts[] = self::convertHundreds($thousand) . ' Thousand';
        }
        if ($hundred > 0) {
            $parts[] = self::convertHundreds($hundred) . ' Hundred';
        }
        if ($number > 0) {
            $parts[] = self::convertHundreds($number);
        }

        return trim(implode(' ', $parts));
    }

    private static function convertHundreds(int $number): string
    {
        $words = '';

        if ($number >= 100) {
            $words .= self::ONES[intdiv($number, 100)] . ' Hundred';
            $number %= 100;
            if ($number > 0) {
                $words .= ' ';
            }
        }

        if ($number >= 20) {
            $words .= self::TENS[intdiv($number, 10)];
            $number %= 10;
            if ($number > 0) {
                $words .= ' ' . self::ONES[$number];
            }
        } elseif ($number > 0) {
            $words .= self::ONES[$number];
        }

        return trim($words);
    }
}
