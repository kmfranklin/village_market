<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in()) {
  redirect_to(url_for('/login.php'));
}

// Get product ID from URL
$product_id = $_GET['id'] ?? '';
if (!$product_id) {
  redirect_to('manage.php');
}

// Fetch the product
/** @var Product $product */
$product = Product::find_by_id($product_id);
if (!$product) {
  $_SESSION['message'] = "Product not found.";
  redirect_to('manage.php');
}

// Ensure only the product owner or an admin can edit
$can_edit = $session->is_admin() || $session->is_super_admin() ||
  ($session->is_vendor() && Vendor::find_by_user_id($session->get_user_id())->vendor_id == $product->vendor_id);

if (!$can_edit) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to('manage.php');
}

// Fetch price units associated with this product
$product_price_units = ProductPriceUnit::find_by_product_id($product->product_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Update product attributes
  $product->product_name = $_POST['product']['product_name'] ?? $product->product_name;
  $product->product_description = $_POST['product']['product_description'] ?? $product->product_description;
  $product->category_id = $_POST['product']['category_id'] ?? $product->category_id;
  $product->is_active = isset($_POST['product']['is_active']) ? 1 : 0;

  // Handle price units
  if (isset($_POST['product_price_unit'])) {
    ProductPriceUnit::update_product_prices($product->product_id, $_POST['product_price_unit']);
  }

  // Handle image removal
  if (!empty($_POST['delete_image']) && !empty($product->product_image_url)) {
    $image_path = PUBLIC_PATH . $product->product_image_url;
    if (file_exists($image_path)) {
      unlink($image_path);
    }
    $product->product_image_url = '';
  }

  // Handle new image upload
  if (!empty($_FILES['product_image']['name'])) {
    $upload_result = $product->upload_image($_FILES['product_image']);
    if (!$upload_result['success']) {
      $product->errors[] = $upload_result['message'];
    }
  }

  // Save changes
  if (empty($product->errors) && $product->update()) {
    $_SESSION['message'] = "Product updated successfully.";
    redirect_to("manage.php");
    exit;
  }
}

$page_title = "Edit Product: " . h($product->product_name);
include_header($session);
?>

<main role="main" id="main">
  <div class="container my-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="mb-4">Edit Product</h1>

        <?php echo display_session_message(); ?>

        <form action="edit.php?id=<?php echo h($product->product_id); ?>" method="post" enctype="multipart/form-data">
          <div class="row">
            <?php include('form_fields.php'); ?>
          </div>


          <!-- Save & Cancel Buttons -->
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
