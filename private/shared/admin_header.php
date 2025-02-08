<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? h($page_title) . ' | Admin Panel — Village Market' : 'Admin Panel — Village Market'; ?></title>
  <link rel="stylesheet" href="<?php echo url_for('../../assets/styles/styles.css'); ?>">
</head>

<body>
  <header>
    <nav>
      <ul>
        <li><a href="<?= url_for('/admin/dashboard.php'); ?>">Your Dashboard</a></li>
        <li><a href="<?= url_for('/admin/manage_homepage.php'); ?>">Manage Homepage</a></li>
        <?php if ($session->is_super_admin()): ?>
          <li><a href="<?= url_for('/admin/users.php'); ?>">Manage Admins</a></li>
        <?php endif; ?>
        <li><a href="<?= url_for('/vendors/manage.php'); ?>">Manage Vendors</a></li>
        <li><a href="<?= url_for('/logout.php'); ?>">Logout</a></li>
      </ul>
    </nav>
  </header>

</body>

</html>
