<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? h($page_title) . ' | Village Market' : 'Village Market'; ?></title>
  <link rel="stylesheet" href="<?php echo url_for('/assets/styles/custom.css'); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?php echo url_for('../../assets/styles/styles.css'); ?>">
  <script type="module" src="<?php echo url_for('/assets/scripts/script.js'); ?>" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo url_for('/index.php'); ?>">
          <img src="<?php echo url_for('/assets/images/village_market_logo_nav.png'); ?>"
            alt="Village Market Logo" height="50">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="<?php echo url_for('/index.php'); ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo url_for('/browse-products.php'); ?>">Browse Products</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo url_for('/browse-vendors.php'); ?>">Browse Vendors</a></li>
            <li class="nav-item"><a class="btn btn-primary text-white px-3" href="<?php echo url_for('/login.php'); ?>">Login</a></li>
            <li class="nav-item"><a class="btn btn-secondary text-white px-3 ms-2" href="<?php echo url_for('/register.php'); ?>">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
