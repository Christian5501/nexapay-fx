<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?><?= APP_NAME ?></title>
  <meta name="description" content="Cross-border payments & FX platform built for Africa">

  <!-- Preconnect for speed -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Favicon (inline SVG data URI) -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%230066ff'/><text y='.9em' font-size='70' x='12'>💱</text></svg>">

  <!-- Chart.js CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js" defer></script>

  <!-- Main CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Animated background canvas -->
<canvas id="bg-canvas"></canvas>

<!-- Toast container -->
<div class="toast-container" id="toast-container"></div>

<div class="wrapper">
