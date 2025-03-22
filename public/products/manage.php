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

// Separate active and inactive products
$active_products = [];
$inactive_products = [];

foreach ($products as $product) {
  if ($product->is_active) {
    $active_products[] = $product;
  } else {
    $inactive_products[] = $product;
  }
}
?>

<main role="main" class="container mt-4">
  <!-- Page Heading -->
  <header class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-primary">Manage Products</h1>
    <a href="new.php" class="btn btn-primary" aria-label="Add New Product">+ Add Product</a>
  </header>

  <!-- Session Message -->
  <?php echo display_session_message(); ?>

  <!-- Active Products Table -->
  <h2 class="text-success">Active Products</h2>
  <div class="table-responsive">
    <table class="table table-striped table-bordered product-table">
      <thead class="table-dark">
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">Category</th>
          <th scope="col">Price</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($active_products as $product) { ?>
          <tr>
            <td data-label="Product Name"><?php echo h($product->product_name); ?></td>
            <td data-label="Category"><?php echo h($product->get_category_name()); ?></td>
            <td data-label="Price">
              <?php
              $price_units = ProductPriceUnit::find_by_product_id($product->product_id);
              foreach ($price_units as $unit) {
                $formatted_price = "$" . number_format($unit->price, 2);
                $unit_name = strtolower($unit->get_unit_name());
                $display_unit = ($unit_name === "each") ? " " . h($unit->get_unit_name()) : " per " . h($unit->get_unit_name());
                echo h($formatted_price . $display_unit) . "<br>";
              }
              ?>
            </td>
            <td class="text-nowrap">
              <a href="view.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary btn-sm">View</a>
              <a href="edit.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
              <button class="btn btn-danger btn-sm delete-btn"
                data-bs-toggle="modal"
                data-bs-target="#delete-modal"
                data-entity="product"
                data-entity-id="<?= h($product->product_id); ?>"
                data-entity-name="<?= h($product->product_name); ?>"
                data-delete-url="<?= url_for('/products/delete.php'); ?>">
                Delete
              </button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- Inactive Products Table -->
  <h2 class="text-danger mt-5">Inactive Products</h2>
  <div class="table-responsive">
    <table class="table table-striped table-bordered product-table">
      <thead class="table-dark">
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">Category</th>
          <th scope="col">Price</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($inactive_products as $product) { ?>
          <tr>
            <td data-label="Product Name"><?php echo h($product->product_name); ?></td>
            <td data-label="Category"><?php echo h($product->get_category_name()); ?></td>
            <td data-label="Price">
              <?php
              $price_units = ProductPriceUnit::find_by_product_id($product->product_id);
              foreach ($price_units as $unit) {
                $formatted_price = "$" . number_format($unit->price, 2);
                $unit_name = strtolower($unit->get_unit_name());
                $display_unit = ($unit_name === "each") ? " " . h($unit->get_unit_name()) : " per " . h($unit->get_unit_name());
                echo h($formatted_price . $display_unit) . "<br>";
              }
              ?>
            </td>
            <td class="text-nowrap">
              <a href="view.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary btn-sm">View</a>
              <a href="edit.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
              <button class="btn btn-danger btn-sm delete-btn"
                data-bs-toggle="modal"
                data-bs-target="#delete-modal"
                data-entity="product"
                data-entity-id="<?= h($product->product_id); ?>"
                data-entity-name="<?= h($product->product_name); ?>"
                data-delete-url="<?= url_for('/products/delete.php'); ?>">
                Delete
              </button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</main>

<?php include(SHARED_PATH . '/modals/delete_modal.php'); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>
