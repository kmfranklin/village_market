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
  <?php $page_title = $page_title ?? 'Vendor Panel'; ?>
  <title><?= h($page_title) ?> | Village Market</title>
  <?php include(SHARED_PATH . '/includes/head_assets.php'); ?>
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
            <!-- Products Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle <?= (strpos($current_page, '/products/') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/products/manage.php'); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Products
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="<?= url_for('/products/index.php'); ?>">
                    Browse Products
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="<?= url_for('/products/manage.php'); ?>">
                    Manage Your Products
                  </a>
                </li>
              </ul>
            </li>
            <!-- User Dropdown -->
            <li class="nav-item dropdown">
              <button class="nav-link dropdown-toggle user-dropdown-btn" id="vendorDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-initials"><?= h($first_initial . $last_initial); ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vendorDropdown">
                <li>
                  <p class="dropdown-header"><?= htmlspecialchars($first_name . ' ' . $last_name); ?></p>
                </li>
                <li><a class="dropdown-item" href="<?= url_for('/vendors/profile.php'); ?>">Edit Profile</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a href="<?php echo url_for('/logout.php'); ?>" class="dropdown-item text-danger logout-link">
                    Logout
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main role="main" id="main">
