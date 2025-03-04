<?php
$current_page = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? h($page_title) . ' | Admin Panel — Village Market' : 'Admin Panel — Village Market'; ?></title>
  <link rel="stylesheet" href="<?php echo url_for('/assets/styles/custom.css'); ?>">
  <link rel="stylesheet" href="<?= url_for('/assets/styles/styles.css'); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script type="module" src="<?php echo url_for('/assets/scripts/script.js'); ?>" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= url_for('/public/index.php'); ?>">
          <img src="<?= url_for('/assets/images/village_market_logo_nav.png'); ?>"
            alt="Village Market Logo" height="60">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/admin/dashboard.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/admin/dashboard.php'); ?>">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/admin/manage_homepage.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/admin/manage_homepage.php'); ?>">Manage Homepage</a>
            </li>
            <?php if ($session->is_super_admin()): ?>
              <li class="nav-item">
                <a class="nav-link <?= (strpos($current_page, '/admin/users.php') !== false) ? 'active' : '' ?>"
                  href="<?= url_for('/admin/users.php'); ?>">Manage Admins</a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/admin/vendors/manage.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/admin/vendors/manage.php'); ?>">Manage Vendors</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/products/manage.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/products/manage.php'); ?>">Manage Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger fw-bold" href="<?= url_for('/logout.php'); ?>">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
