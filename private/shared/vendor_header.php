<?php
$current_page = $_SERVER['REQUEST_URI'];
$first_name = trim($_SESSION['first_name'] ?? 'User');
$last_name = trim($_SESSION['last_name'] ?? '');

// Get initials
$first_initial = strtoupper(substr($first_name, 0, 1));
$last_initial = strtoupper(substr($last_name, 0, 1));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? h($page_title) . ' | Vendor Panel — Village Market' : 'Vendor Panel — Village Market'; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= url_for('/assets/scss/main.min.css'); ?>">
  <script type="module" src="<?= url_for('/assets/scripts/script.js'); ?>" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= url_for('/public/index.php'); ?>">
          <img src="<?= url_for('/assets/images/village_market_logo_nav.png'); ?>" alt="Village Market Logo" height="60">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#vendorNav" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="vendorNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/index.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/index.php'); ?>">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/vendors/dashboard.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/vendors/dashboard.php'); ?>">Your Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/products/manage.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/products/manage.php'); ?>">Manage Your Products</a>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
              <button class="nav-link dropdown-toggle user-dropdown-btn" id="vendorDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                echo '<span class="user-initials">' . $first_initial . $last_initial . '</span>';
                ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vendorDropdown">
                <li>
                  <p class="dropdown-header"><?= htmlspecialchars($first_name . ' ' . $last_name); ?></p>
                </li>
                <li><a class="dropdown-item" href="<?= url_for('/profile.php'); ?>">Profile</a></li>
                <li><a class="dropdown-item" href="<?= url_for('/change_password.php'); ?>">Change Password</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item dropdown-logout text-danger" href="<?= url_for('/logout.php'); ?>">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
