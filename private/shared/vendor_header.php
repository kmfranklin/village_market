<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
  <title><?= isset($page_title) ? h($page_title) . ' | Vendor Panel — Village Market' : 'Vendor Panel — Village Market'; ?></title>
  <link rel="stylesheet" href="<?= url_for('assets/styles/styles.css'); ?>">
</head>

<body>
  <header>
    <nav>
      <ul>
        <li><a href="<?= url_for('vendors/dashboard.php'); ?>">Your Dashboard</a></li>
        <li><a href="<?= url_for('/products/index.php'); ?>">Manage Your Products</a></li>
        <li><a href="<?= url_for('/logout.php'); ?>">Logout</a></li>
      </ul>
    </nav>
  </header>

</body>

</html>
