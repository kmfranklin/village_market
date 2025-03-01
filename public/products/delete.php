<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_vendor())) {
  redirect_to(url_for('/login.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = $_POST['entity_id'] ?? 0;

  if (!$product_id) {
    $_SESSION['message'] = "Error: Product ID is missing.";
    redirect_to(url_for('/products/manage.php'));
  }

  // Find and delete the product
  $product = Product::find_by_id($product_id);
  if ($product && $product->delete()) {
    $_SESSION['message'] = "Product deleted successfully.";
    redirect_to(url_for('/products/manage.php'));
  } else {
    $_SESSION['message'] = "Error: Unable to delete product.";
    redirect_to(url_for('/products/manage.php'));
  }
}
