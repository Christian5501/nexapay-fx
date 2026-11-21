<?php $activePage = $activePage ?? 'dashboard'; ?>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">

  <div class="sidebar-logo">
    <a class="logo-mark" href="index.php">
      <div class="logo-icon">💱</div>
      <div class="logo-text">
        <div class="name"><?= APP_NAME ?></div>
        <div class="tagline">Cross-Border FX</div>
      </div>
    </a>
  </div>

  <nav class="sidebar-nav">

    <div class="nav-section-label">Main</div>

    <a class="nav-item <?= $activePage==='dashboard'?'active':'' ?>" onclick="showPage('dashboard')">
      <span class="icon">📊</span> Dashboard
    </a>

    <a class="nav-item <?= $activePage==='send'?'active':'' ?>" onclick="showPage('send')">
      <span class="icon">💸</span> Send Money
      <span class="nav-badge">New</span>
    </a>

    <a class="nav-item <?= $activePage==='fx'?'active':'' ?>" onclick="showPage('fx')">
      <span class="icon">📈</span> FX Rates
    </a>

    <a class="nav-item <?= $activePage==='transactions'?'active':'' ?>" onclick="showPage('transactions')">
      <span class="icon">🔄</span> Transactions
    </a>

    <a class="nav-item <?= $activePage==='wallets'?'active':'' ?>" onclick="showPage('wallets')">
      <span class="icon">👛</span> Wallets
    </a>

    <div class="nav-section-label" style="margin-top:8px">Payouts</div>

    <a class="nav-item <?= $activePage==='mobilemoney'?'active':'' ?>" onclick="showPage('mobilemoney')">
      <span class="icon">📱</span> Mobile Money
    </a>

    <a class="nav-item <?= $activePage==='bank'?'active':'' ?>" onclick="showPage('bank')">
      <span class="icon">🏦</span> Bank Transfer
    </a>

    <div class="nav-section-label" style="margin-top:8px">Account</div>

    <a class="nav-item" onclick="showPage('settings')">
      <span class="icon">⚙️</span> Settings
    </a>

    <a class="nav-item" onclick="showPage('api')">
      <span class="icon">🔌</span> API Access
    </a>

  </nav>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">NP</div>
      <div class="user-info">
        <div class="name">NexaPay Admin</div>
        <div class="role">Platform Owner</div>
      </div>
    </div>
  </div>

</aside>
