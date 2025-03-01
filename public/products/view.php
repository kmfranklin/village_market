<?php
require_once('../../private/initialize.php');

$is_logged_in = $session->is_logged_in();

// Get product ID from URL
$product_id = $_GET['id'] ?? '';
if (!$product_id) {
  redirect_to('manage.php');
}

// Fetch product details
/** @var Product|null $product */
$product = Product::find_by_id($product_id);
if (!$product) {
  $_SESSION['message'] = "Product not found.";
  redirect_to('manage.php');
}

/** @var Vendor|null $vendor */
// Fetch vendor details
$vendor = Vendor::find_by_id($product->vendor_id);
$vendor_name = $vendor->business_name;

// Fetch category name
$category_name = $product->get_category_name();

// Fetch price units
$price_units = ProductPriceUnit::find_by_product_id($product->product_id);

// Fetch currently logged-in vendor (if applicable)
$logged_in_vendor = $session->is_vendor() ? Vendor::find_by_user_id($session->get_user_id()) : null;

// Check if the logged-in user is an admin or the product owner
$can_manage = $session->is_admin() || $session->is_super_admin() ||
  ($session->is_vendor() && $logged_in_vendor && $logged_in_vendor->vendor_id == $vendor->vendor_id);

$page_title = "Product Details: " . h($product->product_name);
include_header($session);
?>

<main role="main" id="main">
  <h1>Product Details</h1>

  <table>
    <tr>
      <th>Product Name:</th>
      <td><?php echo h($product->product_name); ?></td>
    </tr>
    <tr>
      <th>Category:</th>
      <td><?php echo h($category_name); ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo nl2br(h($product->product_description)); ?></td>
    </tr>
    <?php if (!$session->is_vendor() || ($logged_in_vendor && $logged_in_vendor->vendor_id !== $vendor->vendor_id)) { ?>
      <tr>
        <th>Vendor:</th>
        <td><?php echo h($vendor_name); ?></td>
      </tr>
    <?php } ?>
    <tr>
      <th>Price:</th>
      <td>
        <?php foreach ($price_units as $unit) {
          $formatted_price = "$" . number_format($unit->price, 2);
          $unit_name = strtolower($unit->get_unit_name());
          $display_unit = ($unit_name === "each") ? " " . h($unit->get_unit_name()) : " per " . h($unit->get_unit_name());
          echo h($formatted_price . $display_unit) . "<br>";
        } ?>
      </td>
    </tr>
    <tr>
      <th>Availability:</th>
      <td><?php echo $product->is_active ? 'Available' : 'Unavailable'; ?></td>
    </tr>
    <?php if (!empty($product->product_image_url)) { ?>
      <tr>
        <td>
          <img src="<?php echo h(url_for($product->product_image_url)); ?>" width="200" alt="Product Image">
        </td>
      </tr>
    <?php } ?>

  </table>

  <?php if ($can_manage) { ?>
    <div class="actions">
      <a href="edit.php?id=<?php echo h($product->product_id); ?>" class="button">Edit</a>
      <a href="delete.php?id=<?php echo h($product->product_id); ?>" class="button delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
    </div>
  <?php } ?>

  <br>
  <a href="manage.php">⬅ Back to Product Management</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
