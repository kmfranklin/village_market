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

$page_title = h($product->product_name) . ": Product Details";
require_once(SHARED_PATH . '/include_header.php');
?>

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="text-primary mb-1">Product Details</h1>
      <h2 class="text-secondary m-0"><?= h($product->product_name); ?></h2>
    </div>

    <?php if ($can_manage) : ?>
      <div class="d-flex flex-column" style="min-width: 120px;">
        <a href="edit.php?id=<?= h($product->product_id); ?>" class="btn btn-sm btn-primary mb-2 w-100">
          Edit
        </a>
        <button class="btn btn-sm btn-danger w-100 delete-btn"
          data-bs-toggle="modal"
          data-bs-target="#delete-modal"
          data-entity="product"
          data-entity-id="<?= h($product->product_id); ?>"
          data-entity-name="<?= h($product->product_name); ?>"
          data-delete-url="<?= url_for('/products/delete.php'); ?>"
          data-user-id="<?= h($vendor->user_id); ?>">
          Delete
        </button>
      </div>
    <?php endif; ?>
  </div>


  <!-- Product Details -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row">
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
          <?php if (!empty($product->product_image_url)) : ?>
            <!-- Clickable image that opens modal -->
            <a href="#" data-bs-toggle="modal" data-bs-target="#productImageModal">
              <img src="<?= htmlspecialchars($product->product_image_url); ?>"
                class="img-fluid rounded shadow-sm mb-2"
                alt="<?= htmlspecialchars($product->product_name); ?> Image">
            </a>

            <!-- Bootstrap Modal for Fullscreen Image -->
            <div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered img-modal">
                <div class="img-modal-content bg-transparent border-0">
                  <div class="modal-body text-center">
                    <img src="<?= htmlspecialchars($product->product_image_url); ?>"
                      class="img-fluid rounded shadow"
                      alt="<?= htmlspecialchars($product->product_name); ?> Image">
                  </div>
                  <div class="modal-footer border-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          <?php else : ?>
            <p class="text-muted">No image available</p>

          <?php endif; ?> <!-- Closes the main image check -->
        </div>
      </div>
    </div>
  </div>

  <!-- Back Link -->
  <div class="mt-4">
    <?php if ($can_manage) : ?>
      <a href="manage.php" class="btn btn-outline-secondary">
        ⬅ Back to Product Management
      </a>
    <?php else : ?>
      <a href="<?= url_for('/products/index.php'); ?>" class="btn btn-outline-secondary">
        ⬅ Back to Products
      </a>
    <?php endif; ?>
  </div>
</div>
</main>

<?php include(SHARED_PATH . '/modals/delete_modal.php'); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>
