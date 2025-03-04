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

<main role="main" class="container mt-4">
  <!-- Page Heading -->
  <header class="mb-2 d-flex align-items-center justify-content-between">
    <div>
      <h1 class="display-5 text-primary">Product Details</h1>
      <h2 class="h4 text-secondary"><?= h($product->product_name); ?></h2>
    </div>
    <div class="d-flex flex-column align-items-end">
      <a href="edit.php?id=<?= h($product->product_id); ?>" class="btn btn-sm btn-primary mb-2 w-100 btn-fixed-width">
        Edit
      </a>
      <a href="#" class="delete-btn btn btn-sm btn-danger btn-fixed-width"
        data-entity="product"
        data-entity-id="<?= h($product->product_id); ?>"
        data-entity-name="<?= h($product->product_name); ?>"
        data-delete-url="<?= url_for('/products/delete.php'); ?>">
        Delete
      </a>
      <?php display_delete_modal('product', url_for('/products/delete.php'), $product->product_id, null, $product->product_name); ?>
    </div>
  </header>

  <!-- Product Details -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row">
        <!-- Table for product details -->
        <div class="col-md-8">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th scope="row">Category</th>
                <td><?= h($category_name); ?></td>
              </tr>
              <tr>
                <th scope="row">Description</th>
                <td><?= nl2br(h($product->product_description)); ?></td>
              </tr>
              <tr>
                <th scope="row">Price</th>
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
                <th scope="row">Availability</th>
                <td>
                  <span class="badge <?= $product->is_active ? 'bg-success' : 'bg-danger'; ?>">
                    <?= $product->is_active ? 'Available' : 'Unavailable'; ?>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Product Image & Actions -->
        <div class="col-md-4 text-center">
          <?php if (!empty($product->product_image_url)) { ?>
            <img src="<?= $product->product_image_url; ?>" class="img-fluid rounded shadow-sm mb-2" alt="<?= h($product->product_name); ?> Image">
            <div class="d-flex justify-content-center mt-2">
              <button class="btn btn-outline-primary me-2 px-3">
                Replace Image
              </button>
              <button class="btn btn-outline-danger px-3">
                Remove Image
              </button>
            </div>
          <?php } else { ?>
            <p class="text-muted">No image available</p>
            <button class="btn btn-primary px-3">Upload Image</button>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Back Link -->
  <div class="mt-4">
    <a href="manage.php" class="btn btn-outline-secondary">
      ⬅ Back to Product Management
    </a>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
