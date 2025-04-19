<?php
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php $page_title = $page_title ?? 'Village Market'; ?>
  <title><?= h($page_title) ?> | Village Market</title>
  <?php include(SHARED_PATH . '/includes/head_assets.php'); ?>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= url_for('/index.php'); ?>">
          <img src="<?= url_for('/assets/images/village_market_logo_nav.png'); ?>"
            alt="Village Market Logo" height="60">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-label="Toggle navigation">
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
              <a class="nav-link <?= ($current_path === '/village_market/public/vendors/index.php') ? 'active' : '' ?>"
                href="<?= url_for('/vendors/index.php'); ?>">Browse Vendors</a>
            </li>

            <li class="nav-item"><a class="btn btn-primary text-white px-3" href="<?php echo url_for('/login.php'); ?>">Login</a></li>
            <li class="nav-item"><a class="btn btn-secondary text-white px-3 ms-2" href="<?php echo url_for('/vendors/register.php'); ?>">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main role="main" id="main">
