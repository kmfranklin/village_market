<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$page_title = 'Manage Products';
$is_admin = $session->is_admin() || $session->is_super_admin();
include_header($session);

// Get filter dropdowns
require_once(SHARED_PATH . '/product_filters.php');
$filter_results = get_filter_dropdowns($database);
$vendor_result = $filter_results['vendor_result'];
$category_result = $filter_results['category_result'];

// Get filtered products based on role
$options = [
  'is_admin' => $is_admin,
  'current_vendor_id' => null
];

// Vendors only see their own products, admins see all
if (!$is_admin) {
  $vendor = Vendor::find_by_user_id($session->get_user_id());
  if (!$vendor) {
    $_SESSION['message'] = "Error: No associated vendor account found.";
    redirect_to(url_for('/dashboard.php'));
  }
  $options['current_vendor_id'] = $vendor->vendor_id;
}

$sql = get_filtered_products_query($database, $options);
$products = Product::find_by_sql($sql);

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

  <?php echo display_session_message(); ?>

  <!-- Filter Form -->
  <?php
  $show_vendor_filter = $is_admin;
  include(SHARED_PATH . '/product_filter_form.php');
  ?>

  <!-- Active Products Table -->
  <h2 class="text-success">Active Products</h2>
  <div class="table-responsive">
    <?php if (empty($active_products)) { ?>
      <div class="alert alert-success">
        <i class="bi bi-info-circle"></i> No active products match your search criteria.
      </div>
    <?php } else { ?>
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
            <tr data-name="<?= h(strtolower($product->product_name)) ?>"
              data-category="<?= h($product->category_id) ?>"
              data-vendor="<?= h($product->vendor_id) ?>">
              <td data-label="Product Name"><?php echo h($product->product_name); ?></td>
              <td data-label="Category"><?php echo h($product->get_category_name()); ?></td>
              <td data-label="Price">
                <?php
                $price_units = ProductPriceUnit::find_by_product_id($product->product_id);
                if (!empty($price_units)) {
                  foreach ($price_units as $unit) {
                    $formatted_price = "$" . number_format($unit->price, 2);
                    $display_unit = ($unit->get_unit_name() === "each") ? " each" : " per " . h($unit->get_unit_name());
                    echo $formatted_price . $display_unit . "<br>";
                  }
                } else {
                  echo "<em class='text-muted'>No price set</em>";
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
    <?php } ?>
  </div>

  <!-- Inactive Products Table -->
  <h2 class="text-danger mt-5">Inactive Products</h2>
  <div class="table-responsive">
    <?php if (empty($inactive_products)) { ?>
      <div class="alert alert-success">
        <i class="bi bi-info-circle"></i> No inactive products match your search criteria.
      </div>
    <?php } else { ?>
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
            <tr data-name="<?= h(strtolower($product->product_name)) ?>"
              data-category="<?= h($product->category_id) ?>"
              data-vendor="<?= h($product->vendor_id) ?>">
              <td data-label="Product Name"><?php echo h($product->product_name); ?></td>
              <td data-label="Category"><?php echo h($product->get_category_name()); ?></td>
              <td data-label="Price">
                <?php
                $price_units = ProductPriceUnit::find_by_product_id($product->product_id);
                if (!empty($price_units)) {
                  foreach ($price_units as $unit) {
                    $formatted_price = "$" . number_format($unit->price, 2);
                    $display_unit = ($unit->get_unit_name() === "each") ? " each" : " per " . h($unit->get_unit_name());
                    echo $formatted_price . $display_unit . "<br>";
                  }
                } else {
                  echo "<em class='text-muted'>No price set</em>";
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
    <?php } ?>
  </div>
</main>

<?php include(SHARED_PATH . '/modals/delete_modal.php'); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>
