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

<main role="main" class="container mt-4">
  <!-- Page Heading -->
  <header class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-primary">Manage Products</h1>
    <a href="new.php" class="btn btn-primary" aria-label="Add New Product">+ Add Product</a>
  </header>

  <!-- Session Message -->
  <?php echo display_session_message(); ?>

  <!-- Products Table -->
  <div class="table-responsive">
    <table class="table table-striped table-bordered product-table">
      <thead class="table-dark">
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">Category</th>
          <th scope="col">Price</th>
          <th scope="col">Status</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
          <tr>
            <td data-label="Product Name"><?php echo h($product->product_name); ?></td>
            <td data-label="Category"><?php echo h($product->get_category_name()); ?></td>
            <td data-label="price">
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
            <td data-label="Actions">
              <span class="badge <?php echo $product->is_active ? 'bg-success' : 'bg-danger'; ?>">
                <?php echo $product->is_active ? 'Active' : 'Inactive'; ?>
              </span>
            </td>
            <td class="text-nowrap">
              <a href="view.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary btn-sm">View</a>
              <a href="edit.php?id=<?php echo h($product->product_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
              <button class="btn btn-danger btn-sm delete-btn"
                data-bs-toggle="modal"
                data-bs-target="#delete-modal-product-<?= h($product->product_id); ?>"
                data-entity="product"
                data-entity-id="<?= h($product->product_id); ?>"
                data-entity-name="<?= h($product->product_name); ?>"
                data-delete-url="<?= url_for('/products/delete.php'); ?>">
                Delete
              </button>

              <?php display_delete_modal('product', url_for('/products/delete.php'), $product->product_id, $product->product_name); ?>

            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
