<?php
require_once str_replace('\\', '/', __DIR__) . '/config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            // For demo: use SQLite as fallback if MySQL not available
            $this->conn = null;
        } else {
            $this->conn->set_charset('utf8mb4');
        }
    }

    public static function getInstance(): self {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function getConn() { return $this->conn; }

    public function isConnected(): bool { return $this->conn !== null; }
}

// ===== FX HELPER FUNCTIONS =====

function convertCurrency(float $amount, string $from, string $to): float {
    $rates = FX_RATES;
    if (!isset($rates[$from]) || !isset($rates[$to])) return 0;
    $inUSD = $amount / $rates[$from];
    return round($inUSD * $rates[$to], 4);
}

function getRate(string $from, string $to): float {
    $rates = FX_RATES;
    if (!isset($rates[$from]) || !isset($rates[$to])) return 0;
    return round($rates[$to] / $rates[$from], 4);
}

function formatCurrency(float $amount, string $currency): string {
    $currencies = CURRENCIES;
    $symbol = $currencies[$currency]['symbol'] ?? $currency . ' ';
    if ($currency === 'RWF' || $currency === 'UGX' || $currency === 'NGN' || $currency === 'XOF') {
        return $symbol . ' ' . number_format($amount, 0);
    }
    return $symbol . number_format($amount, 2);
}

function calculateFee(float $amount): float {
    // Tiered fee structure
    if ($amount <= 100)    return round($amount * 0.025, 2); // 2.5%
    if ($amount <= 1000)   return round($amount * 0.020, 2); // 2.0%
    if ($amount <= 10000)  return round($amount * 0.015, 2); // 1.5%
    return round($amount * 0.010, 2);                          // 1.0%
}

function generateTxRef(): string {
    return 'NX' . strtoupper(substr(md5(uniqid()), 0, 10));
}

function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ===== MOCK TRANSACTION DATA (demo when no DB) =====
function getMockTransactions(): array {
    return [
        ['ref'=>'NX4A9B2C8D','type'=>'send',    'amount'=>250.00,   'currency'=>'USD','to'=>'MTN Mobile Money','country'=>'RW','status'=>'success','time'=>'2 min ago','rate'=>1350],
        ['ref'=>'NX7E3F1A5B','type'=>'receive',  'amount'=>500.00,   'currency'=>'USD','to'=>'Bank Transfer',  'country'=>'US','status'=>'success','time'=>'1 hr ago', 'rate'=>null],
        ['ref'=>'NX2D8C6E4F','type'=>'exchange', 'amount'=>1000.00,  'currency'=>'USD','to'=>'EUR',            'country'=>'EU','status'=>'success','time'=>'3 hr ago', 'rate'=>0.918],
        ['ref'=>'NX9A1B7C3D','type'=>'payout',   'amount'=>75.50,    'currency'=>'USD','to'=>'Airtel Money',   'country'=>'UG','status'=>'pending','time'=>'5 hr ago', 'rate'=>3750],
        ['ref'=>'NX5C2E8A1B','type'=>'send',     'amount'=>320.00,   'currency'=>'USD','to'=>'M-Pesa',         'country'=>'KE','status'=>'success','time'=>'1 day ago','rate'=>132.5],
        ['ref'=>'NX8F4D2A6C','type'=>'exchange', 'amount'=>2000.00,  'currency'=>'USD','to'=>'GBP',            'country'=>'GB','status'=>'success','time'=>'1 day ago','rate'=>0.785],
        ['ref'=>'NX3B9E5C7A','type'=>'send',     'amount'=>150.00,   'currency'=>'USD','to'=>'Wave Senegal',   'country'=>'SN','status'=>'failed', 'time'=>'2 days ago','rate'=>601],
    ];
}

function getMockRates(): array {
    return [
        ['pair'=>'USD/RWF','rate'=>1350.00,'change'=>+0.45,'trend'=>'up',  'history'=>[1330,1335,1340,1338,1344,1348,1350]],
        ['pair'=>'USD/KES','rate'=>132.50, 'change'=>-0.18,'trend'=>'down','history'=>[130,131,133,132,134,133,132.5]],
        ['pair'=>'USD/EUR','rate'=>0.9180, 'change'=>+0.12,'trend'=>'up',  'history'=>[0.91,0.912,0.915,0.913,0.916,0.917,0.918]],
        ['pair'=>'USD/GBP','rate'=>0.7850, 'change'=>-0.05,'trend'=>'down','history'=>[0.79,0.788,0.786,0.787,0.784,0.785,0.785]],
        ['pair'=>'USD/NGN','rate'=>1580.00,'change'=>+1.20,'trend'=>'up',  'history'=>[1560,1565,1570,1568,1575,1578,1580]],
        ['pair'=>'USD/UGX','rate'=>3750.00,'change'=>+0.08,'trend'=>'up',  'history'=>[3720,3730,3740,3735,3745,3748,3750]],
    ];
}
