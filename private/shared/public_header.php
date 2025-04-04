<?php
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? h($page_title) . ' | Village Market' : 'Village Market'; ?></title>
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  </noscript>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= url_for('/assets/scss/main.min.css'); ?>">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>
  <script src="<?= url_for('/assets/scripts/main.min.js'); ?>" defer></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= url_for('/index.php'); ?>">
          <img src="<?= url_for('/assets/images/village_market_logo_nav.png'); ?>"
            alt="Village Market Logo" height="60">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link <?= ($current_path === '/village_market/public/index.php') ? 'active' : '' ?>"
                href="<?= url_for('/index.php'); ?>">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_path === '/village_market/public/products/index.php') ? 'active' : '' ?>"
                href="<?= url_for('/products/index.php'); ?>">Browse Products</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_path === '/village_market/public/vendors.php') ? 'active' : '' ?>"
                href="<?= url_for('/vendors.php'); ?>">Browse Vendors</a>
            </li>

            <li class="nav-item"><a class="btn btn-primary text-white px-3" href="<?php echo url_for('/login.php'); ?>">Login</a></li>
            <li class="nav-item"><a class="btn btn-secondary text-white px-3 ms-2" href="<?php echo url_for('/vendors/register.php'); ?>">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
