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
  <?php $page_title = $page_title ?? 'Admin Panel'; ?>
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-label="Open Navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link <?= (strpos($current_page, '/index.php') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/index.php'); ?>">Home</a>
            </li>
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
                  href="<?= url_for('/admin/users/manage.php'); ?>">Manage Admins</a>
              </li>
            <?php endif; ?>

            <!-- Vendors Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle <?= (strpos($current_page, '/vendors/') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/admin/vendors/manage.php'); ?>" role="button">
                Vendors
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="<?= url_for('/vendors/index.php'); ?>">
                    Browse Vendors
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="<?= url_for('/admin/vendors/manage.php'); ?>">
                    Manage Vendors
                  </a>
                </li>
              </ul>
            </li>

            <!-- Products Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle <?= (strpos($current_page, '/products/') !== false) ? 'active' : '' ?>"
                href="<?= url_for('/products/manage.php'); ?>" role="button">
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
                    Manage Products
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <button class="nav-link dropdown-toggle user-dropdown-btn" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo '<span class="user-initials">' . $first_initial . $last_initial . '</span>'; ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                  <p class="dropdown-header"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
                </li>
                <li><a class="dropdown-item" href="<?= url_for('/admin/users/edit.php?id=' . h($session->user_id)); ?>">Edit Profile</a></li>
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
