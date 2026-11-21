<?php
// ============================================================
//  NexaPay FX Platform — Single File, No Dependencies
//  Drop this file + assets/css/style.css into htdocs/nexapay/
// ============================================================

// ---------- CONFIG ----------
define('APP_NAME', 'NexaPay FX');

$FX_RATES = [
    'USD'=>1.0000,'RWF'=>1350.00,'EUR'=>0.9180,'GBP'=>0.7850,
    'KES'=>132.50,'UGX'=>3750.00,'GHS'=>12.80,'NGN'=>1580.00,
    'XOF'=>601.00,'ZAR'=>18.90,
];

$CURRENCIES = [
    'USD'=>['name'=>'US Dollar',       'symbol'=>'$',   'flag'=>'🇺🇸'],
    'RWF'=>['name'=>'Rwandan Franc',   'symbol'=>'Fr',  'flag'=>'🇷🇼'],
    'EUR'=>['name'=>'Euro',            'symbol'=>'€',   'flag'=>'🇪🇺'],
    'GBP'=>['name'=>'British Pound',   'symbol'=>'£',   'flag'=>'🇬🇧'],
    'KES'=>['name'=>'Kenyan Shilling', 'symbol'=>'KSh', 'flag'=>'🇰🇪'],
    'UGX'=>['name'=>'Uganda Shilling', 'symbol'=>'USh', 'flag'=>'🇺🇬'],
    'GHS'=>['name'=>'Ghana Cedi',      'symbol'=>'₵',   'flag'=>'🇬🇭'],
    'NGN'=>['name'=>'Nigerian Naira',  'symbol'=>'₦',   'flag'=>'🇳🇬'],
    'XOF'=>['name'=>'CFA Franc',       'symbol'=>'CFA', 'flag'=>'🌍'],
    'ZAR'=>['name'=>'South African Rand','symbol'=>'R', 'flag'=>'🇿🇦'],
];

$MM_PROVIDERS = [
    ['emoji'=>'📱','name'=>'MTN Mobile Money', 'countries'=>'RW · UG · GH','color'=>'#ffcc00'],
    ['emoji'=>'📲','name'=>'Airtel Money',      'countries'=>'RW · UG · KE','color'=>'#ff0000'],
    ['emoji'=>'💚','name'=>'M-Pesa',            'countries'=>'KE · TZ · GH','color'=>'#00b300'],
    ['emoji'=>'🍊','name'=>'Orange Money',      'countries'=>'SN · CI · CM','color'=>'#ff6600'],
    ['emoji'=>'🌊','name'=>'Wave',              'countries'=>'SN · CI',     'color'=>'#00aaff'],
    ['emoji'=>'🏦','name'=>'Equity Bank',       'countries'=>'KE · RW · UG','color'=>'#aa00ff'],
];

$TRANSACTIONS = [
    ['ref'=>'NX4A9B2C','type'=>'send',    'amount'=>250.00,  'to'=>'MTN Mobile Money','country'=>'RW','status'=>'success','time'=>'2 min ago'],
    ['ref'=>'NX7E3F1A','type'=>'receive', 'amount'=>500.00,  'to'=>'Bank Transfer',   'country'=>'US','status'=>'success','time'=>'1 hr ago'],
    ['ref'=>'NX2D8C6E','type'=>'exchange','amount'=>1000.00, 'to'=>'EUR',             'country'=>'EU','status'=>'success','time'=>'3 hr ago'],
    ['ref'=>'NX9A1B7C','type'=>'payout',  'amount'=>75.50,   'to'=>'Airtel Money',    'country'=>'UG','status'=>'pending','time'=>'5 hr ago'],
    ['ref'=>'NX5C2E8A','type'=>'send',    'amount'=>320.00,  'to'=>'M-Pesa',          'country'=>'KE','status'=>'success','time'=>'1 day ago'],
    ['ref'=>'NX8F4D2A','type'=>'exchange','amount'=>2000.00, 'to'=>'GBP',             'country'=>'GB','status'=>'success','time'=>'1 day ago'],
    ['ref'=>'NX3B9E5C','type'=>'send',    'amount'=>150.00,  'to'=>'Wave Senegal',    'country'=>'SN','status'=>'failed', 'time'=>'2 days ago'],
];

// ---------- API HANDLER ----------
if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    $action = $_GET['api'];
    if ($action === 'convert') {
        $amount = (float)($_GET['amount'] ?? 0);
        $from   = strtoupper($_GET['from'] ?? 'USD');
        $to     = strtoupper($_GET['to']   ?? 'RWF');
        $rate   = $FX_RATES[$to] / $FX_RATES[$from];
        $result = round($amount * $rate, 2);
        $fee    = round($amount * 0.02, 2);
        echo json_encode(['result'=>$result,'rate'=>round($rate,4),'fee'=>$fee,'to'=>$to]);
    } elseif ($action === 'transactions') {
        echo json_encode($TRANSACTIONS);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NexaPay FX — Cross-Border Payments Platform</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap">
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%230066ff'/><text y='.9em' font-size='70' x='12'>💱</text></svg>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<canvas id="bg-canvas"></canvas>
<div class="toast-container" id="toasts"></div>

<div class="app-shell">

<!-- ====== SIDEBAR ====== -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">💱</div>
    <div class="logo-text">
      <div class="logo-name">NexaPay</div>
      <div class="logo-sub">CROSS-BORDER FX</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-label">MAIN</div>
    <a class="nav-item active" data-page="dashboard"><span class="nav-icon">📊</span>Dashboard</a>
    <a class="nav-item" data-page="send"><span class="nav-icon">💸</span>Send Money<span class="nav-badge">NEW</span></a>
    <a class="nav-item" data-page="fx"><span class="nav-icon">📈</span>FX Rates</a>
    <a class="nav-item" data-page="transactions"><span class="nav-icon">🔄</span>Transactions</a>
    <a class="nav-item" data-page="wallets"><span class="nav-icon">👛</span>Wallets</a>
    <div class="nav-label" style="margin-top:8px">PAYOUTS</div>
    <a class="nav-item" data-page="mobilemoney"><span class="nav-icon">📱</span>Mobile Money</a>
    <a class="nav-item" data-page="bank"><span class="nav-icon">🏦</span>Bank Transfer</a>
    <div class="nav-label" style="margin-top:8px">ACCOUNT</div>
    <a class="nav-item" data-page="api"><span class="nav-icon">🔌</span>API Access</a>
    <a class="nav-item" data-page="settings"><span class="nav-icon">⚙️</span>Settings</a>
  </nav>

  <div class="sidebar-user">
    <div class="user-avatar">NP</div>
    <div>
      <div class="user-name">NexaPay Admin</div>
      <div class="user-role">Platform Owner · Rwanda</div>
    </div>
  </div>
</aside>

<!-- ====== MAIN ====== -->
<main class="main">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="icon-btn" id="menu-btn">☰</button>
      <div>
        <div class="page-title" id="page-title">Dashboard</div>
        <div class="breadcrumb">NexaPay › <span id="breadcrumb">Dashboard</span></div>
      </div>
    </div>
    <div class="topbar-right">
      <div class="ticker-bar">
        <div class="ticker-item"><span class="t-pair">USD/RWF</span><span class="t-rate" data-base="1350" data-dp="2">1350.00</span><span class="t-up">▲0.45%</span></div>
        <div class="ticker-item"><span class="t-pair">USD/KES</span><span class="t-rate" data-base="132.5" data-dp="2">132.50</span><span class="t-dn">▼0.18%</span></div>
        <div class="ticker-item"><span class="t-pair">EUR/USD</span><span class="t-rate" data-base="1.0893" data-dp="4">1.0893</span><span class="t-up">▲0.12%</span></div>
      </div>
      <button class="icon-btn" title="Notifications">🔔<span class="notif-dot"></span></button>
    </div>
  </header>

  <!-- ==================== DASHBOARD ==================== -->
  <section class="page active" id="page-dashboard">

    <!-- Hero -->
    <div class="hero">
      <div class="hero-text">
        <div class="eyebrow">🚀 Cross-Border FX Infrastructure</div>
        <h1>Move Money Across<br><span class="gradient-text">Africa & Beyond</span></h1>
        <p>Real-time FX, mobile money payouts & bank transfers — fintech infrastructure, not a basic app.</p>
        <div class="hero-btns">
          <button class="btn btn-primary" onclick="nav('send')">💸 Send Money</button>
          <button class="btn btn-ghost" onclick="nav('fx')">📈 View FX Rates</button>
        </div>
      </div>
      <div class="hero-stats">
        <div class="hstat"><div class="hstat-val">$2.4M</div><div class="hstat-lbl">Volume Today</div></div>
        <div class="hstat"><div class="hstat-val">98.7%</div><div class="hstat-lbl">Success Rate</div></div>
        <div class="hstat"><div class="hstat-val">4.2s</div><div class="hstat-lbl">Avg Settlement</div></div>
        <div class="hstat"><div class="hstat-val">14</div><div class="hstat-lbl">Currencies</div></div>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-top"><div class="stat-icon blue">💰</div><span class="badge-up">▲ 12.4%</span></div>
        <div class="stat-val">$2,418,340</div>
        <div class="stat-lbl">Total Volume (USD)</div>
        <div class="stat-sub">↑ $267k vs last week</div>
      </div>
      <div class="stat-card">
        <div class="stat-top"><div class="stat-icon cyan">🔄</div><span class="badge-up">▲ 8.1%</span></div>
        <div class="stat-val">1,847</div>
        <div class="stat-lbl">Transactions Today</div>
        <div class="stat-sub">↑ 138 vs yesterday</div>
      </div>
      <div class="stat-card">
        <div class="stat-top"><div class="stat-icon green">📱</div><span class="badge-up">▲ 5.3%</span></div>
        <div class="stat-val">93.2%</div>
        <div class="stat-lbl">Mobile Money Success</div>
        <div class="stat-sub">Industry avg: 72%</div>
      </div>
      <div class="stat-card">
        <div class="stat-top"><div class="stat-icon gold">⚡</div><span class="badge-dn">▼ 0.3s</span></div>
        <div class="stat-val">4.2s</div>
        <div class="stat-lbl">Avg. Settlement Time</div>
        <div class="stat-sub">Industry avg: 48hr</div>
      </div>
    </div>

    <!-- Chart + Converter -->
    <div class="two-col">
      <div class="glass-card">
        <div class="card-head">
          <div class="card-title"><span class="live-dot"></span>Transaction Volume</div>
          <div class="chart-tabs">
            <button class="ctab active">7D</button>
            <button class="ctab">30D</button>
            <button class="ctab">90D</button>
          </div>
        </div>
        <div class="chart-wrap"><canvas id="vol-chart"></canvas></div>
      </div>

      <div class="glass-card">
        <div class="card-head"><div class="card-title">⚡ Quick Convert</div><span class="text-muted text-xs">Live rates</span></div>
        <div class="converter">
          <div class="fx-display">
            <div class="fx-result" id="qc-result">—</div>
            <div class="fx-label" id="qc-rate">Enter amount above</div>
            <div class="fx-fee text-gold" id="qc-fee"></div>
          </div>
          <div class="form-row">
            <label class="form-label">YOU SEND</label>
            <div class="currency-input">
              <select id="qc-from" onchange="quickConvert()">
                <?php foreach($CURRENCIES as $c=>$d): ?>
                <option value="<?=$c?>" <?=$c==='USD'?'selected':''?>><?=$d['flag']?> <?=$c?></option>
                <?php endforeach; ?>
              </select>
              <input type="number" id="qc-amount" value="250" oninput="quickConvert()">
            </div>
          </div>
          <button class="swap-btn" onclick="swapCurrencies()">⇅ Swap</button>
          <div class="form-row">
            <label class="form-label">THEY RECEIVE</label>
            <div class="currency-input">
              <select id="qc-to" onchange="quickConvert()">
                <?php foreach($CURRENCIES as $c=>$d): ?>
                <option value="<?=$c?>" <?=$c==='RWF'?'selected':''?>><?=$d['flag']?> <?=$c?></option>
                <?php endforeach; ?>
              </select>
              <input type="text" id="qc-receive" readonly placeholder="Result" class="text-cyan">
            </div>
          </div>
          <button class="btn btn-primary w-full mt" onclick="nav('send')">Send This Amount →</button>
        </div>
      </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass-card">
      <div class="card-head">
        <div class="card-title">🔄 Recent Transactions</div>
        <button class="btn btn-ghost btn-sm" onclick="nav('transactions')">View All →</button>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead><tr><th>Reference</th><th>Type</th><th>Amount</th><th>Channel</th><th>Status</th><th>Time</th></tr></thead>
          <tbody>
            <?php foreach(array_slice($TRANSACTIONS,0,5) as $tx): ?>
            <tr>
              <td><code class="ref"><?=$tx['ref']?></code></td>
              <td><span class="tx-badge tx-<?=$tx['type']?>"><?=ucfirst($tx['type'])?></span></td>
              <td><strong>$<?=number_format($tx['amount'],2)?></strong></td>
              <td><?=htmlspecialchars($tx['to'])?> <span class="text-muted">(<?=$tx['country']?>)</span></td>
              <td><span class="status-pill status-<?=$tx['status']?>"><span class="sdot"></span><?=ucfirst($tx['status'])?></span></td>
              <td class="text-muted"><?=$tx['time']?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section><!-- /dashboard -->

  <!-- ==================== SEND MONEY ==================== -->
  <section class="page" id="page-send">
    <div class="page-header">
      <div><h2 class="page-h2">💸 Send Money</h2><p class="text-muted">Cross-border transfers via Mobile Money or Bank</p></div>
    </div>

    <div class="send-grid">
      <div class="glass-card">
        <div class="card-head"><div class="card-title">Transfer Details</div></div>
        <div class="card-body">

          <div class="steps" id="steps">
            <div class="step active" data-s="1"><div class="step-circle">1</div><span>Details</span></div>
            <div class="step-line"></div>
            <div class="step" data-s="2"><div class="step-circle">2</div><span>Review</span></div>
            <div class="step-line"></div>
            <div class="step" data-s="3"><div class="step-circle">3</div><span>Confirm</span></div>
          </div>

          <!-- Panel 1 -->
          <div class="step-panel" id="sp-1">
            <div class="alert alert-info">💡 Transfers to Rwanda settle in ~60 seconds via Mobile Money.</div>
            <div class="form-row"><label class="form-label">YOU SEND</label>
              <div class="currency-input">
                <select id="s-from" onchange="liveCalc()">
                  <?php foreach($CURRENCIES as $c=>$d): ?><option value="<?=$c?>" <?=$c==='USD'?'selected':''?>><?=$d['flag']?> <?=$c?></option><?php endforeach; ?>
                </select>
                <input type="number" id="s-amount" value="250" oninput="liveCalc()">
              </div>
            </div>
            <div class="form-row"><label class="form-label">THEY RECEIVE</label>
              <div class="currency-input">
                <select id="s-to" onchange="liveCalc()">
                  <?php foreach($CURRENCIES as $c=>$d): ?><option value="<?=$c?>" <?=$c==='RWF'?'selected':''?>><?=$d['flag']?> <?=$c?></option><?php endforeach; ?>
                </select>
                <input type="text" id="s-receive" readonly class="text-cyan">
              </div>
            </div>
            <div class="form-row"><label class="form-label">PAYOUT CHANNEL</label>
              <select class="form-control" id="s-channel">
                <option>📱 MTN Mobile Money</option>
                <option>📲 Airtel Money</option>
                <option>💚 M-Pesa</option>
                <option>🏦 Bank Transfer</option>
                <option>🌊 Wave</option>
              </select>
            </div>
            <div class="form-row"><label class="form-label">RECIPIENT PHONE / ACCOUNT</label>
              <input class="form-control" type="text" id="s-recipient" placeholder="+250 78X XXX XXX">
            </div>
            <div class="form-row"><label class="form-label">REFERENCE (OPTIONAL)</label>
              <input class="form-control" type="text" placeholder="Payment for services...">
            </div>
            <button class="btn btn-primary w-full" onclick="goStep(2)">Continue →</button>
          </div>

          <!-- Panel 2 -->
          <div class="step-panel hidden" id="sp-2">
            <div class="summary-box">
              <div class="sum-row"><span>You send</span><span id="rev-send" class="text-cyan"></span></div>
              <div class="sum-row"><span>Exchange rate</span><span id="rev-rate"></span></div>
              <div class="sum-row"><span>Fee (2%)</span><span id="rev-fee" class="text-gold"></span></div>
              <div class="sum-row"><span>Total cost</span><span id="rev-total"></span></div>
              <div class="sum-row sum-final"><span>They receive</span><span id="rev-receive" class="text-green big-num"></span></div>
            </div>
            <div class="btn-row">
              <button class="btn btn-ghost" onclick="goStep(1)">← Back</button>
              <button class="btn btn-primary" onclick="goStep(3)">Looks Good →</button>
            </div>
          </div>

          <!-- Panel 3 -->
          <div class="step-panel hidden" id="sp-3">
            <div class="alert alert-success">✅ All details verified. Ready to send.</div>
            <div class="confirm-center">
              <div style="font-size:52px">💸</div>
              <div class="confirm-amount" id="confirm-amt"></div>
              <div class="text-muted" style="margin-top:6px">via Mobile Money · ~60 seconds</div>
            </div>
            <div class="btn-row">
              <button class="btn btn-ghost" onclick="goStep(2)">← Back</button>
              <button class="btn btn-primary" id="send-btn" onclick="doSend()">Send Money 🚀</button>
            </div>
          </div>

        </div>
      </div>

      <!-- Live summary sidebar -->
      <div>
        <div class="glass-card mb">
          <div class="card-head"><div class="card-title">📋 Live Summary</div></div>
          <div class="card-body">
            <div class="summary-box">
              <div class="sum-row"><span>Sending</span><span id="ls-send" class="text-cyan">$250.00 USD</span></div>
              <div class="sum-row"><span>Rate</span><span id="ls-rate">1 USD = 1,350 RWF</span></div>
              <div class="sum-row"><span>Fee (2%)</span><span id="ls-fee" class="text-gold">$5.00</span></div>
              <div class="sum-row"><span>Total</span><span id="ls-total">$255.00</span></div>
              <div class="sum-row sum-final"><span>They get</span><span id="ls-receive" class="text-green big-num">337,500 RWF</span></div>
            </div>
          </div>
        </div>
        <div class="glass-card">
          <div class="card-head"><div class="card-title">🔒 Security</div></div>
          <div class="card-body">
            <?php foreach(['256-bit TLS encryption','Real-time fraud detection','Regulated & compliant','Instant SMS receipt'] as $s): ?>
            <div class="security-row"><span class="text-green">✓</span> <?=$s?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== FX RATES ==================== -->
  <section class="page" id="page-fx">
    <div class="page-header">
      <div><h2 class="page-h2">📈 FX Rates</h2><p class="text-muted">Live exchange rates — updated every 30s</p></div>
      <span class="live-badge">● LIVE</span>
    </div>

    <div class="glass-card mb">
      <div class="card-head"><div class="card-title">Rate Cards (vs USD)</div></div>
      <div class="rates-grid">
        <?php
        $ratecards = [
          ['USD/RWF',1350.00,+0.45,'up',[1330,1335,1340,1338,1344,1348,1350]],
          ['USD/KES',132.50,-0.18,'dn',[130,131,133,132,134,133,132.5]],
          ['USD/EUR',0.9180,+0.12,'up',[0.91,0.912,0.915,0.913,0.916,0.917,0.918]],
          ['USD/GBP',0.7850,-0.05,'dn',[0.79,0.788,0.786,0.787,0.784,0.785,0.785]],
          ['USD/NGN',1580.00,+1.20,'up',[1560,1565,1570,1568,1575,1578,1580]],
          ['USD/UGX',3750.00,+0.08,'up',[3720,3730,3740,3735,3745,3748,3750]],
        ];
        foreach($ratecards as $i=>$r):
        ?>
        <div class="rate-card">
          <div class="rc-pair"><?=$r[0]?></div>
          <div class="rc-val"><?=number_format($r[1],$r[1]<10?4:2)?></div>
          <div class="rc-chg <?=$r[3]==='up'?'text-green':'text-danger'?>">
            <?=$r[3]==='up'?'▲':'▼'?> <?=abs($r[2])?>%
          </div>
          <canvas class="mini-c" id="mc-<?=$i?>" data-pts='<?=json_encode($r[4])?>' data-trend="<?=$r[3]?>"></canvas>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="glass-card">
      <div class="card-head"><div class="card-title">All Rates Table</div><span class="text-muted text-xs">Spread: 1.5%</span></div>
      <div class="table-wrap">
        <table class="data-table">
          <thead><tr><th>Currency</th><th>Code</th><th>Buy Rate</th><th>Sell Rate</th><th>Mid Rate</th><th></th></tr></thead>
          <tbody>
            <?php foreach($CURRENCIES as $code=>$info):
              if($code==='USD') continue;
              $mid  = $FX_RATES[$code];
              $buy  = round($mid*0.985,4);
              $sell = round($mid*1.015,4);
            ?>
            <tr>
              <td><?=$info['flag']?> <?=$info['name']?></td>
              <td><strong><?=$code?></strong></td>
              <td class="text-green"><?=number_format($buy,4)?></td>
              <td class="text-danger"><?=number_format($sell,4)?></td>
              <td><?=number_format($mid,4)?></td>
              <td><button class="btn btn-ghost btn-sm" onclick="nav('send')">Convert</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ==================== TRANSACTIONS ==================== -->
  <section class="page" id="page-transactions">
    <div class="page-header">
      <div><h2 class="page-h2">🔄 Transactions</h2><p class="text-muted">Full payment history</p></div>
      <button class="btn btn-ghost btn-sm">Export CSV</button>
    </div>
    <div class="glass-card">
      <div class="table-wrap">
        <table class="data-table">
          <thead><tr><th>Reference</th><th>Type</th><th>Amount</th><th>Channel</th><th>Country</th><th>Status</th><th>Time</th><th></th></tr></thead>
          <tbody>
            <?php foreach($TRANSACTIONS as $tx): ?>
            <tr>
              <td><code class="ref"><?=$tx['ref']?></code></td>
              <td><span class="tx-badge tx-<?=$tx['type']?>"><?=ucfirst($tx['type'])?></span></td>
              <td><strong>$<?=number_format($tx['amount'],2)?></strong></td>
              <td><?=htmlspecialchars($tx['to'])?></td>
              <td><?=$tx['country']?></td>
              <td><span class="status-pill status-<?=$tx['status']?>"><span class="sdot"></span><?=ucfirst($tx['status'])?></span></td>
              <td class="text-muted"><?=$tx['time']?></td>
              <td><button class="btn btn-ghost btn-sm" onclick="toast('Ref: <?=$tx['ref']?>','Transaction Detail','info')">View</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ==================== WALLETS ==================== -->
  <section class="page" id="page-wallets">
    <div class="page-header"><div><h2 class="page-h2">👛 Wallets</h2><p class="text-muted">Multi-currency balances</p></div></div>
    <div class="wallets-row">
      <div class="wallet-card wc-blue">
        <div class="wc-curr">🇺🇸 USD WALLET</div>
        <div class="wc-bal"><span>$</span>48,250<span>.00</span></div>
        <div class="wc-sub">US Dollar · Primary</div>
        <div class="wc-foot"><span class="text-green">▲ 2.3% this week</span><div class="wc-btns"><button class="wc-btn" onclick="nav('send')">↗</button><button class="wc-btn" onclick="nav('fx')">⇄</button></div></div>
      </div>
      <div class="wallet-card wc-green">
        <div class="wc-curr">🇷🇼 RWF WALLET</div>
        <div class="wc-bal"><span>Fr</span>6,750,000</div>
        <div class="wc-sub">Rwandan Franc · ≈ $5,000</div>
        <div class="wc-foot"><span class="text-green">▲ 0.45% today</span><div class="wc-btns"><button class="wc-btn" onclick="nav('send')">↗</button><button class="wc-btn" onclick="nav('mobilemoney')">📱</button></div></div>
      </div>
      <div class="wallet-card wc-purple">
        <div class="wc-curr">🇪🇺 EUR WALLET</div>
        <div class="wc-bal"><span>€</span>12,430<span>.50</span></div>
        <div class="wc-sub">Euro · ≈ $13,540</div>
        <div class="wc-foot"><span class="text-green">▲ 0.12%</span><div class="wc-btns"><button class="wc-btn" onclick="nav('send')">↗</button><button class="wc-btn" onclick="nav('fx')">⇄</button></div></div>
      </div>
    </div>
    <div class="glass-card">
      <div class="card-head"><div class="card-title">All Balances</div></div>
      <div class="table-wrap">
        <table class="data-table">
          <thead><tr><th>Currency</th><th>Balance</th><th>≈ USD</th><th>Available</th><th>Pending</th><th></th></tr></thead>
          <tbody>
            <?php
            $bals=[
              'USD'=>['b'=>48250,'p'=>1200],
              'RWF'=>['b'=>6750000,'p'=>0],
              'EUR'=>['b'=>12430.50,'p'=>500],
              'GBP'=>['b'=>3200,'p'=>0],
              'KES'=>['b'=>580000,'p'=>25000],
            ];
            foreach($bals as $code=>$b):
              $usd = round($b['b']/$FX_RATES[$code],2);
              $av  = $b['b']-$b['p'];
            ?>
            <tr>
              <td><?=$CURRENCIES[$code]['flag']?> <?=$CURRENCIES[$code]['name']?></td>
              <td><strong><?=$CURRENCIES[$code]['symbol']?><?=number_format($b['b'],2)?></strong></td>
              <td class="text-muted">$<?=number_format($usd,2)?></td>
              <td class="text-green"><?=$CURRENCIES[$code]['symbol']?><?=number_format($av,2)?></td>
              <td class="text-gold"><?=$b['p']>0?$CURRENCIES[$code]['symbol'].number_format($b['p'],2):'—'?></td>
              <td><button class="btn btn-ghost btn-sm" onclick="nav('send')">Send</button> <button class="btn btn-ghost btn-sm" onclick="nav('fx')">Exchange</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ==================== MOBILE MONEY ==================== -->
  <section class="page" id="page-mobilemoney">
    <div class="page-header"><div><h2 class="page-h2">📱 Mobile Money</h2><p class="text-muted">Direct payouts across Africa</p></div></div>
    <div class="alert alert-success mb">✅ All mobile money rails are operational. Avg settlement: 45 seconds.</div>
    <div class="glass-card mb">
      <div class="card-head"><div class="card-title">Supported Providers</div></div>
      <div class="mm-grid">
        <?php foreach($MM_PROVIDERS as $p): ?>
        <div class="mm-card" onclick="toast('<?=$p['name']?>','Click Send Money to use this provider.','info')">
          <span class="mm-icon"><?=$p['emoji']?></span>
          <div class="mm-name"><?=$p['name']?></div>
          <div class="mm-status">● Active</div>
          <div class="text-xs text-muted" style="margin-top:4px"><?=$p['countries']?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="glass-card">
      <div class="card-head"><div class="card-title">Quick Payout</div></div>
      <div class="card-body" style="max-width:480px">
        <div class="form-row"><label class="form-label">PROVIDER</label>
          <select class="form-control"><?php foreach($MM_PROVIDERS as $p): ?><option><?=$p['emoji']?> <?=$p['name']?></option><?php endforeach; ?></select>
        </div>
        <div class="form-row"><label class="form-label">PHONE NUMBER</label><input class="form-control" type="tel" placeholder="+250 78X XXX XXX"></div>
        <div class="form-row"><label class="form-label">AMOUNT (USD)</label><input class="form-control" type="number" placeholder="100.00"></div>
        <button class="btn btn-primary" onclick="toast('Payout Initiated','Processing via Mobile Money...','success')">📱 Send to Mobile Money</button>
      </div>
    </div>
  </section>

  <!-- ==================== BANK ==================== -->
  <section class="page" id="page-bank">
    <div class="page-header"><div><h2 class="page-h2">🏦 Bank Transfer</h2><p class="text-muted">SWIFT, SEPA & local rails</p></div></div>
    <div class="glass-card">
      <div class="card-head"><div class="card-title">Transfer Details</div></div>
      <div class="card-body" style="max-width:560px">
        <div class="alert alert-info mb">ℹ️ Bank transfers settle in 1–3 business days. Use Mobile Money for faster Rwanda payouts.</div>
        <?php foreach([['Recipient Full Name','text','John Doe'],['Bank Name','text','Bank of Kigali'],['Account Number / IBAN','text','RW60 1234 5678 9012 3456'],['SWIFT / BIC Code','text','BKIGRWRW']] as $f): ?>
        <div class="form-row"><label class="form-label"><?=strtoupper($f[0])?></label><input class="form-control" type="<?=$f[1]?>" placeholder="<?=$f[2]?>"></div>
        <?php endforeach; ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-row"><label class="form-label">AMOUNT</label><input class="form-control" type="number" placeholder="500.00"></div>
          <div class="form-row"><label class="form-label">CURRENCY</label><select class="form-control"><?php foreach($CURRENCIES as $c=>$d): ?><option><?=$d['flag']?> <?=$c?></option><?php endforeach; ?></select></div>
        </div>
        <button class="btn btn-primary" onclick="toast('Bank Transfer Initiated','ETA: 1-3 business days.','success')">🏦 Initiate Transfer</button>
      </div>
    </div>
  </section>

  <!-- ==================== API ==================== -->
  <section class="page" id="page-api">
    <div class="page-header"><div><h2 class="page-h2">🔌 API Access</h2><p class="text-muted">Integrate NexaPay into your own app</p></div></div>
    <div class="two-col">
      <div class="glass-card">
        <div class="card-head"><div class="card-title">API Keys</div></div>
        <div class="card-body">
          <div class="alert alert-warning mb">⚠️ Never expose API keys in client-side code.</div>
          <div class="form-row"><label class="form-label">LIVE KEY</label>
            <div style="display:flex;gap:8px">
              <input class="form-control" type="password" id="akey" value="nx_live_sk_4a9b2c8d7e3f1a5b" style="font-family:monospace">
              <button class="btn btn-ghost btn-sm" onclick="var f=document.getElementById('akey');f.type=f.type==='password'?'text':'password'">👁</button>
              <button class="btn btn-ghost btn-sm" onclick="toast('Copied!','API key copied.','success')">Copy</button>
            </div>
          </div>
          <div class="form-row"><label class="form-label">WEBHOOK URL</label><input class="form-control" placeholder="https://yourapp.com/webhooks/nexapay"></div>
          <button class="btn btn-primary btn-sm" onclick="toast('Saved','API settings updated.','success')">Save</button>
        </div>
      </div>
      <div class="glass-card">
        <div class="card-head"><div class="card-title">Endpoints</div></div>
        <div class="card-body">
          <?php $eps=[['GET','index.php?api=convert&amount=100&from=USD&to=RWF','Convert currency'],['GET','index.php?api=transactions','List transactions']]; ?>
          <?php foreach($eps as $e): ?>
          <div class="ep-row">
            <span class="ep-method ep-<?=strtolower($e[0])?>"><?=$e[0]?></span>
            <code class="ep-url"><?=htmlspecialchars($e[1])?></code>
            <a href="<?=htmlspecialchars($e[1])?>" target="_blank" class="btn btn-ghost btn-sm">Try →</a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== SETTINGS ==================== -->
  <section class="page" id="page-settings">
    <div class="page-header"><div><h2 class="page-h2">⚙️ Settings</h2></div></div>
    <div class="two-col">
      <div class="glass-card">
        <div class="card-head"><div class="card-title">Business Profile</div></div>
        <div class="card-body">
          <div class="form-row"><label class="form-label">BUSINESS NAME</label><input class="form-control" value="NexaPay Ltd"></div>
          <div class="form-row"><label class="form-label">EMAIL</label><input class="form-control" value="admin@nexapay.rw"></div>
          <div class="form-row"><label class="form-label">COUNTRY</label><select class="form-control"><option>🇷🇼 Rwanda</option><option>🇰🇪 Kenya</option><option>🇺🇬 Uganda</option></select></div>
          <button class="btn btn-primary btn-sm" onclick="toast('Saved','Profile updated.','success')">Save Changes</button>
        </div>
      </div>
      <div class="glass-card">
        <div class="card-head"><div class="card-title">Notifications</div></div>
        <div class="card-body">
          <?php foreach(['Email on transfer','SMS confirmation','Webhook events','Daily report','Low balance alert'] as $n): ?>
          <div class="toggle-row"><span><?=$n?></span><label class="toggle"><input type="checkbox" checked><span class="tslider"></span></label></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

</main>
</div><!-- /.app-shell -->

<script>
// ===== PARTICLE BACKGROUND =====
(function(){
  const cv=document.getElementById('bg-canvas'), cx=cv.getContext('2d');
  let W,H,P=[];
  function resize(){W=cv.width=window.innerWidth;H=cv.height=window.innerHeight;}
  window.addEventListener('resize',resize); resize();
  function init(){P=[];const n=Math.floor(W*H/14000);for(let i=0;i<n;i++)P.push({x:Math.random()*W,y:Math.random()*H,r:Math.random()*1.4+0.3,vx:(Math.random()-.5)*.3,vy:(Math.random()-.5)*.3,a:Math.random()*.5+.1,h:Math.random()<.6?210:(Math.random()<.5?190:160)});}
  window.addEventListener('resize',init); init();
  function draw(){
    cx.clearRect(0,0,W,H);
    for(let i=0;i<P.length;i++)for(let j=i+1;j<P.length;j++){const dx=P[i].x-P[j].x,dy=P[i].y-P[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<110){cx.beginPath();cx.strokeStyle=`rgba(0,140,255,${(1-d/110)*.1})`;cx.lineWidth=.5;cx.moveTo(P[i].x,P[i].y);cx.lineTo(P[j].x,P[j].y);cx.stroke();}}
    P.forEach(p=>{cx.beginPath();cx.arc(p.x,p.y,p.r,0,Math.PI*2);cx.fillStyle=`hsla(${p.h},80%,70%,${p.a})`;cx.fill();p.x+=p.vx;p.y+=p.vy;if(p.x<0||p.x>W)p.vx*=-1;if(p.y<0||p.y>H)p.vy*=-1;});
    requestAnimationFrame(draw);
  }
  draw();
})();

// ===== NAVIGATION =====
const PAGES={dashboard:'Dashboard',send:'Send Money',fx:'FX Rates',transactions:'Transactions',wallets:'Wallets',mobilemoney:'Mobile Money',bank:'Bank Transfer',api:'API Access',settings:'Settings'};

function nav(id){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  const pg=document.getElementById('page-'+id);
  if(pg) pg.classList.add('active');
  document.querySelectorAll(`.nav-item[data-page="${id}"]`).forEach(n=>n.classList.add('active'));
  const lbl=PAGES[id]||id;
  document.getElementById('page-title').textContent=lbl;
  document.getElementById('breadcrumb').textContent=lbl;
  if(id==='dashboard'||id==='fx') setTimeout(initCharts,120);
  if(window.innerWidth<900) document.getElementById('sidebar').classList.remove('open');
}

document.querySelectorAll('.nav-item').forEach(n=>{
  n.addEventListener('click',()=>nav(n.dataset.page));
});
document.getElementById('menu-btn').addEventListener('click',()=>{
  document.getElementById('sidebar').classList.toggle('open');
});

// ===== TOAST =====
function toast(msg,title,type){
  const icons={success:'✅',error:'❌',info:'💡',warning:'⚠️'};
  const t=document.createElement('div');
  t.className=`toast toast-${type}`;
  t.innerHTML=`<span class="t-icon">${icons[type]||'💡'}</span><div><div class="t-title">${title||''}</div><div class="t-msg">${msg}</div></div>`;
  document.getElementById('toasts').appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),300);},3500);
}

// ===== TICKER =====
setInterval(()=>{
  document.querySelectorAll('.t-rate').forEach(el=>{
    const b=parseFloat(el.dataset.base), dp=parseInt(el.dataset.dp)||4;
    const n=b+(Math.random()-.5)*b*.0004;
    el.dataset.base=n; el.textContent=n.toFixed(dp);
  });
},3000);

// ===== FX CALC =====
const RATES={USD:1,RWF:1350,EUR:0.918,GBP:0.785,KES:132.5,UGX:3750,GHS:12.8,NGN:1580,XOF:601,ZAR:18.9};

function calcFX(amount,from,to){
  const rate=RATES[to]/RATES[from];
  return {result:amount*rate, rate:rate, fee:amount*.02};
}

function quickConvert(){
  const amt=parseFloat(document.getElementById('qc-amount').value)||0;
  const fr=document.getElementById('qc-from').value;
  const to=document.getElementById('qc-to').value;
  const {result,rate,fee}=calcFX(amt,fr,to);
  const dp=result>1000?0:2;
  document.getElementById('qc-result').textContent=result.toFixed(dp)+' '+to;
  document.getElementById('qc-rate').textContent=`1 ${fr} = ${rate.toFixed(4)} ${to}`;
  document.getElementById('qc-fee').textContent=`Fee: $${fee.toFixed(2)}`;
  document.getElementById('qc-receive').value=result.toFixed(dp);
}

function swapCurrencies(){
  const f=document.getElementById('qc-from'),t=document.getElementById('qc-to'),tmp=f.value;
  f.value=t.value; t.value=tmp; quickConvert();
}

// ===== SEND LIVE CALC =====
function liveCalc(){
  const amt=parseFloat(document.getElementById('s-amount').value)||0;
  const fr=document.getElementById('s-from').value;
  const to=document.getElementById('s-to').value;
  const {result,rate,fee}=calcFX(amt,fr,to);
  const dp=result>1000?0:2;
  document.getElementById('s-receive').value=result.toFixed(dp)+' '+to;
  // live summary
  const set=(id,v)=>{const el=document.getElementById(id);if(el)el.textContent=v;};
  set('ls-send',`${amt.toFixed(2)} ${fr}`);
  set('ls-rate',`1 ${fr} = ${rate.toFixed(4)} ${to}`);
  set('ls-fee',`${fee.toFixed(2)} ${fr}`);
  set('ls-total',`${(amt+fee).toFixed(2)} ${fr}`);
  set('ls-receive',`${result.toFixed(dp)} ${to}`);
  // review panel
  set('rev-send',`${amt.toFixed(2)} ${fr}`);
  set('rev-rate',`1 ${fr} = ${rate.toFixed(4)} ${to}`);
  set('rev-fee',`${fee.toFixed(2)} ${fr}`);
  set('rev-total',`${(amt+fee).toFixed(2)} ${fr}`);
  set('rev-receive',`${result.toFixed(dp)} ${to}`);
  set('confirm-amt',`${amt.toFixed(2)} ${fr} → ${result.toFixed(dp)} ${to}`);
}

// ===== STEP FLOW =====
let curStep=1;
function goStep(n){
  curStep=n;
  document.querySelectorAll('.step-panel').forEach((p,i)=>{p.classList.toggle('hidden',i+1!==n);});
  document.querySelectorAll('.step').forEach((s,i)=>{
    s.classList.remove('active','done');
    if(i+1===n) s.classList.add('active');
    if(i+1<n)   s.classList.add('done');
  });
}

function doSend(){
  const btn=document.getElementById('send-btn');
  btn.innerHTML='<span class="spinner"></span> Processing...'; btn.disabled=true;
  setTimeout(()=>{
    btn.innerHTML='✅ Payment Sent!';
    toast('Transfer complete!','Your payment is being processed.','success');
    setTimeout(()=>{btn.innerHTML='Send Money 🚀';btn.disabled=false;goStep(1);nav('transactions');},1500);
  },2500);
}

// ===== CHARTS =====
const chartI={};
function initCharts(){
  initVolChart();
  document.querySelectorAll('.mini-c').forEach(c=>{
    const id=c.id, pts=JSON.parse(c.dataset.pts||'[]'), trend=c.dataset.trend;
    if(chartI[id]) chartI[id].destroy();
    const col=trend==='up'?'#00c896':'#ff4757';
    const ctx=c.getContext('2d');
    const g=ctx.createLinearGradient(0,0,0,40);g.addColorStop(0,col+'40');g.addColorStop(1,col+'00');
    chartI[id]=new Chart(ctx,{type:'line',data:{labels:pts.map((_,i)=>i),datasets:[{data:pts,borderColor:col,backgroundColor:g,borderWidth:1.5,fill:true,tension:.4,pointRadius:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{enabled:false}},scales:{x:{display:false},y:{display:false}}}});
  });
}

function initVolChart(){
  const cv=document.getElementById('vol-chart'); if(!cv) return;
  if(chartI['vol']) chartI['vol'].destroy();
  const ctx=cv.getContext('2d');
  const g1=ctx.createLinearGradient(0,0,0,280);g1.addColorStop(0,'rgba(0,102,255,.4)');g1.addColorStop(1,'rgba(0,102,255,.02)');
  const g2=ctx.createLinearGradient(0,0,0,280);g2.addColorStop(0,'rgba(0,212,255,.3)');g2.addColorStop(1,'rgba(0,212,255,.02)');
  chartI['vol']=new Chart(ctx,{type:'line',data:{labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],datasets:[{label:'Total Volume',data:[42000,58000,51000,74000,66000,89000,95000],borderColor:'#0066ff',backgroundColor:g1,borderWidth:2.5,fill:true,tension:.4,pointRadius:4,pointHoverRadius:6},{label:'Settled',data:[30000,42000,38000,55000,48000,67000,72000],borderColor:'#00d4ff',backgroundColor:g2,borderWidth:2,fill:true,tension:.4,pointRadius:3}]},options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},plugins:{legend:{labels:{color:'#8892b0',font:{size:12},usePointStyle:true}},tooltip:{backgroundColor:'rgba(13,18,40,.95)',borderColor:'rgba(0,102,255,.3)',borderWidth:1,titleColor:'#f0f4ff',bodyColor:'#8892b0',padding:12,callbacks:{label:c=>' $'+c.parsed.y.toLocaleString()}}},scales:{x:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'#4a5568',font:{size:12}}},y:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'#4a5568',font:{size:12},callback:v=>'$'+(v/1000).toFixed(0)+'k'}}}}});
}

// ===== CHART TABS =====
document.querySelectorAll('.ctab').forEach(b=>{
  b.addEventListener('click',()=>{b.closest('.chart-tabs').querySelectorAll('.ctab').forEach(x=>x.classList.remove('active'));b.classList.add('active');});
});

// ===== INIT =====
document.addEventListener('DOMContentLoaded',()=>{
  liveCalc(); quickConvert();
  setTimeout(initCharts,200);
});
</script>
</body>
</html>
