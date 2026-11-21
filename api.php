<?php
define('BASE_PATH', str_replace('\\', '/', __DIR__));
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

  // ===== CURRENCY CONVERSION =====
  case 'convert':
    $amount = (float)($_GET['amount'] ?? 1);
    $from   = strtoupper(sanitize($_GET['from'] ?? 'USD'));
    $to     = strtoupper(sanitize($_GET['to']   ?? 'RWF'));
    $rates  = FX_RATES;

    if (!isset($rates[$from]) || !isset($rates[$to])) {
      jsonResponse(['error' => 'Invalid currency pair'], 400);
    }

    $result = convertCurrency($amount, $from, $to);
    $fee    = calculateFee($amount);
    $rate   = getRate($from, $to);
    $currencies = CURRENCIES;

    jsonResponse([
      'success'   => true,
      'amount'    => $amount,
      'from'      => $from,
      'to'        => $to,
      'result'    => $result,
      'rate'      => $rate,
      'fee'       => $fee,
      'formatted' => formatCurrency($result, $to),
      'fee_fmt'   => formatCurrency($fee, $from),
    ]);
    break;

  // ===== GET ALL RATES =====
  case 'rates':
    $base   = strtoupper(sanitize($_GET['base'] ?? 'USD'));
    $rates  = FX_RATES;
    $output = [];
    foreach ($rates as $currency => $rate) {
      if ($currency === $base) continue;
      $output[] = [
        'pair'   => "$base/$currency",
        'from'   => $base,
        'to'     => $currency,
        'rate'   => round($rate / $rates[$base], 4),
        'change' => round((mt_rand(-200, 200) / 10000), 4), // mock change
      ];
    }
    jsonResponse(['success' => true, 'base' => $base, 'rates' => $output]);
    break;

  // ===== SUBMIT TRANSFER =====
  case 'transfer':
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $amount   = (float)($data['amount']   ?? 0);
    $from     = strtoupper(sanitize($data['from_currency'] ?? 'USD'));
    $to       = strtoupper(sanitize($data['to_currency']   ?? 'RWF'));
    $provider = sanitize($data['provider'] ?? '');
    $recipient= sanitize($data['recipient'] ?? '');

    if ($amount < MIN_TRANSFER || $amount > MAX_TRANSFER) {
      jsonResponse(['error' => "Amount must be between $" . MIN_TRANSFER . " and $" . number_format(MAX_TRANSFER)], 400);
    }

    $converted = convertCurrency($amount, $from, $to);
    $fee       = calculateFee($amount);
    $ref       = generateTxRef();

    // In production: persist to DB and call payment provider SDK
    jsonResponse([
      'success'    => true,
      'ref'        => $ref,
      'amount'     => $amount,
      'from'       => $from,
      'to'         => $to,
      'converted'  => $converted,
      'fee'        => $fee,
      'provider'   => $provider,
      'recipient'  => $recipient,
      'status'     => 'processing',
      'message'    => "Transfer $ref initiated successfully",
    ]);
    break;

  // ===== TRANSACTIONS (mock) =====
  case 'transactions':
    jsonResponse(['success' => true, 'transactions' => getMockTransactions()]);
    break;

  // ===== FEE ESTIMATE =====
  case 'fee':
    $amount = (float)($_GET['amount'] ?? 0);
    jsonResponse(['fee' => calculateFee($amount), 'amount' => $amount]);
    break;

  default:
    jsonResponse(['error' => 'Unknown action', 'action' => $action], 400);
}
