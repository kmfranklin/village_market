<?php
require_once('../../private/initialize.php');
$page_title = "Edit Product";
require_once(SHARED_PATH . '/include_header.php');

if (!$session->is_logged_in()) {
  redirect_to(url_for('/login.php'));
}

$product_id = $_GET['id'] ?? '';
if (!$product_id) {
  redirect_to('manage.php');
}

$product = Product::find_by_id($product_id);
/** @var Product $product */
if (!$product) {
  $_SESSION['message'] = "Product not found.";
  redirect_to('manage.php');
}

$can_edit = $session->is_admin() || $session->is_super_admin() ||
  ($session->is_vendor() && Vendor::find_by_user_id($session->get_user_id())->vendor_id == $product->vendor_id);

if (!$can_edit) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to('manage.php');
}

// Fetch price units associated with this product
$product_price_units = ProductPriceUnit::find_by_product_id($product->product_id);
$existing_prices = [];
foreach ($product_price_units as $unit) {
  $existing_prices[$unit->price_unit_id] = $unit->price;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product->product_name = $_POST['product']['product_name'] ?? $product->product_name;
  $product->product_description = $_POST['product']['product_description'] ?? $product->product_description;
  $product->category_id = $_POST['product']['category_id'] ?? $product->category_id;
  $product->is_active = isset($_POST['product']['is_active']) ? 1 : 0;

  // **Ensure at least one price is set**
  if (empty($_POST['product_price_unit']) || !array_filter($_POST['product_price_unit'], function ($unit) {
    return isset($unit['price']) && floatval($unit['price']) > 0;
  })) {
    $errors[] = "At least one price must be set.";
  } else {
    ProductPriceUnit::update_product_prices($product->product_id, $_POST['product_price_unit']);
  }

  if (!empty($_FILES['product_image']['name'])) {
    $upload_result = $product->upload_image($_FILES['product_image']);
    if (!$upload_result['success']) {
      $errors[] = $upload_result['message'];
    } else {
      $product->product_image_url = $upload_result['url'];
    }
  }

  if (empty($errors) && $product->update()) {
    $_SESSION['message'] = "Product updated successfully.";
    redirect_to("manage.php");
    exit;
  }
}
?>

<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="mb-4">Edit Product</h1>

      <?php echo display_session_message(); ?>

      <form action="edit.php?id=<?php echo h($product->product_id); ?>" method="post" enctype="multipart/form-data">
        <div class="row">
          <?php include('form_fields.php'); ?>
        </div>

        <div class="d-flex gap-3 mt-4">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a href="view.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
