<?php
// ===== DATABASE CONFIG =====
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fxplatform');

// ===== APP CONFIG =====
define('APP_NAME', 'NexaPay FX');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/fxplatform');
define('SESSION_TIMEOUT', 3600); // 1 hour

// ===== FX CONFIG =====
define('BASE_CURRENCY', 'USD');
define('FX_SPREAD', 0.015); // 1.5% spread
define('MIN_TRANSFER', 1.00);
define('MAX_TRANSFER', 50000.00);

// ===== MOBILE MONEY PROVIDERS =====
define('MM_PROVIDERS', [
    'mtn'     => ['name' => 'MTN Mobile Money', 'countries' => ['RW', 'UG', 'GH', 'CM'], 'emoji' => '📱'],
    'airtel'  => ['name' => 'Airtel Money',     'countries' => ['RW', 'UG', 'KE', 'TZ'], 'emoji' => '📲'],
    'mpesa'   => ['name' => 'M-Pesa',           'countries' => ['KE', 'TZ', 'GH'],       'emoji' => '💚'],
    'orange'  => ['name' => 'Orange Money',     'countries' => ['SN', 'CI', 'CM'],        'emoji' => '🍊'],
    'wave'    => ['name' => 'Wave',             'countries' => ['SN', 'CI'],              'emoji' => '🌊'],
    'equity'  => ['name' => 'Equity Bank',      'countries' => ['KE', 'RW', 'UG'],        'emoji' => '🏦'],
]);

// ===== SUPPORTED CURRENCIES =====
define('CURRENCIES', [
    'USD' => ['name' => 'US Dollar',      'symbol' => '$',  'flag' => '🇺🇸'],
    'RWF' => ['name' => 'Rwandan Franc',  'symbol' => 'Fr', 'flag' => '🇷🇼'],
    'EUR' => ['name' => 'Euro',           'symbol' => '€',  'flag' => '🇪🇺'],
    'GBP' => ['name' => 'British Pound',  'symbol' => '£',  'flag' => '🇬🇧'],
    'KES' => ['name' => 'Kenyan Shilling','symbol' => 'KSh','flag' => '🇰🇪'],
    'UGX' => ['name' => 'Uganda Shilling','symbol' => 'USh','flag' => '🇺🇬'],
    'GHS' => ['name' => 'Ghana Cedi',     'symbol' => '₵',  'flag' => '🇬🇭'],
    'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦',  'flag' => '🇳🇬'],
    'XOF' => ['name' => 'CFA Franc',      'symbol' => 'CFA','flag' => '🌍'],
    'ZAR' => ['name' => 'South African Rand','symbol'=>'R', 'flag' => '🇿🇦'],
]);

// ===== MOCK FX RATES (vs USD) =====
// In production, replace with live API calls (e.g. Open Exchange Rates, Fixer.io)
define('FX_RATES', [
    'USD' => 1.0000,
    'RWF' => 1350.00,
    'EUR' => 0.9180,
    'GBP' => 0.7850,
    'KES' => 132.50,
    'UGX' => 3750.00,
    'GHS' => 12.80,
    'NGN' => 1580.00,
    'XOF' => 601.00,
    'ZAR' => 18.90,
]);

// ===== ERROR REPORTING (disable in production) =====
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ===== SESSION =====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
