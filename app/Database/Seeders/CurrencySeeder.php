<?php

namespace EHXDonate\Database\Seeders;

use EHXDonate\Models\Currency;

/**
 * Trip seeder
 */
class CurrencySeeder
{
    /**
     * Run the seeder
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'U.S. Dollar',
                'symbol' => '$',
                'exchange_rate' => 1, // Base currency
                'code' => 'USD',
            ],
            [
                'name' => 'Pound Sterling',
                'symbol' => '£',
                'exchange_rate' => 0.74721,
                'code' => 'GBP',
            ],
            [
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.87928,
                'code' => 'EUR',
            ],
            [
                'name' => 'Australian Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.5611,
                'code' => 'AUD',
            ],
            [
                'name' => 'Brazilian Real',
                'symbol' => 'R$',
                'exchange_rate' => 5.656,
                'code' => 'BRL',
            ],
            [
                'name' => 'Canadian Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.3845,
                'code' => 'CAD',
            ],
            [
                'name' => 'Czech Koruna',
                'symbol' => 'Kč',
                'exchange_rate' => 21.913,
                'code' => 'CZK',
            ],
            [
                'name' => 'Danish Krone',
                'symbol' => 'kr',
                'exchange_rate' => 6.5626,
                'code' => 'DKK',
            ],
            [
                'name' => 'Hong Kong Dollar',
                'symbol' => '$',
                'exchange_rate' => 7.7585,
                'code' => 'HKD',
            ],
            [
                'name' => 'Hungarian Forint',
                'symbol' => 'Ft',
                'exchange_rate' => 355.26,
                'code' => 'HUF',
            ],
            [
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'exchange_rate' => 142.69,
                'code' => 'JPY',
            ],
            [
                'name' => 'Malaysian Ringgit',
                'symbol' => 'RM',
                'exchange_rate' => 4.3275,
                'code' => 'MYR',
            ],
            [
                'name' => 'Mexican Peso',
                'symbol' => '$',
                'exchange_rate' => 19.6034,
                'code' => 'MXN',
            ],
            [
                'name' => 'Norwegian Krone',
                'symbol' => 'kr',
                'exchange_rate' => 10.3794,
                'code' => 'NOK',
            ],
            [
                'name' => 'New Zealand Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.6799,
                'code' => 'NZD',
            ],
            [
                'name' => 'Philippine Peso',
                'symbol' => '₱',
                'exchange_rate' => 56.1,
                'code' => 'PHP',
            ],
            [
                'name' => 'Polish Zloty',
                'symbol' => 'zł',
                'exchange_rate' => 3.7552,
                'code' => 'PLN',
            ],
            [
                'name' => 'Russian Ruble',
                'symbol' => '₽',
                'exchange_rate' => 81.70,
                'code' => 'RUB',
            ],
            [
                'name' => 'Singapore Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.3096,
                'code' => 'SGD',
            ],
            [
                'name' => 'Swedish Krona',
                'symbol' => 'kr',
                'exchange_rate' => 9.6386,
                'code' => 'SEK',
            ],
            [
                'name' => 'Swiss Franc',
                'symbol' => 'CHF',
                'exchange_rate' => 0.82582,
                'code' => 'CHF',
            ],
            [
                'name' => 'Thai Baht',
                'symbol' => '฿',
                'exchange_rate' => 33.44,
                'code' => 'THB',
            ],
            [
                'name' => 'Taka',
                'symbol' => '৳',
                'exchange_rate' => 121.46,
                'code' => 'BDT',
            ],
            [
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'exchange_rate' => 85.13,
                'code' => 'INR',
            ],
        ];

        foreach ($currencies as $currencyData) {
            Currency::create($currencyData);
        }
    }
}
