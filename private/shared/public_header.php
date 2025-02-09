<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? h($page_title) . ' | Village Market' : 'Village Market'; ?></title>
  <link rel="stylesheet" href="<?php echo url_for('../../assets/styles/styles.css'); ?>">
</head>

<body>
  <header>
    <nav>
      <ul>
        <li><a href="<?php echo url_for('/index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url_for('/browse-products.php'); ?>">Browse Products</a></li>
        <li><a href="<?php echo url_for('/browse-vendors.php') ?>">Browse Vendors</a></li>
        <li><a href="<?php echo url_for('/login.php') ?>">Login</a></li>
      </ul>
    </nav>
  </header>
