<?php
require_once('../../private/initialize.php');
if (!$session->is_logged_in() || (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$page_title = 'Manage Products';
$is_admin = $session->is_admin() || $session->is_super_admin();
include_header($session);

// Vendors only see their own products, admins see all
if ($is_admin) {
  $products = Product::find_all();
} else {
  $vendor = Vendor::find_by_user_id($session->get_user_id());
  if (!$vendor) {
    $_SESSION['message'] = "Error: No associated vendor account found.";
    redirect_to(url_for('/dashboard.php'));
  }
  $products = Product::find_by_vendor($vendor->vendor_id);
}
?>

<main role="main" id="main">
  <h1>Manage Products</h1>

  <a href="new.php" class="button">+ Add Product</a>

  <?php echo display_session_message(); ?>

  <table class="list">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
        <tr>
          <td><?php echo h($product->product_name); ?></td>
          <td><?php echo h($product->get_category_name()); ?></td>
          <td>
            <?php
            $price_units = ProductPriceUnit::find_by_product_id($product->product_id);
            foreach ($price_units as $unit) {
              // Format price with a dollar sign and two decimal places
              $formatted_price = "$" . number_format($unit->price, 2);

              // Get the unit name
              $unit_name = strtolower($unit->get_unit_name());

              // Determine display format
              $display_unit = ($unit_name === "each") ? " " . h($unit->get_unit_name()) : " per " . h($unit->get_unit_name());

              echo h($formatted_price . $display_unit) . "<br>";
            }
            ?>
          </td>
          <td><?php echo $product->is_active ? 'Active' : 'Inactive'; ?></td>
          <td>
            <a href="view.php?id=<?php echo h($product->product_id); ?>">View</a> |
            <a href="edit.php?id=<?php echo h($product->product_id); ?>">Edit</a> |
            <a href="delete.php?id=<?php echo h($product->product_id); ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
