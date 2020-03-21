<?php

namespace App\Services;

class CurrencyService
{

    const RATES = [
        'usd' => [
            'eur' => 0.89
        ]
    ];

    public function convert($amount, $currnecy_from, $currnecy_to)
    {
        $rate = 0;
        if (isset(self::RATES[$currnecy_from])) {
            $rate = self::RATES[$currnecy_from][$currnecy_to] ?? 0;
        }

        return round($amount * $rate, 2);
    }
}