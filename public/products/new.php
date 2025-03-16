<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$errors = [];
$product = new Product($_POST['product'] ?? []);
$is_admin = $session->is_admin() || $session->is_super_admin();

include_header($session);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_args = $_POST['product'];

  if ($is_admin) {
    $product_args['vendor_id'] = $_POST['vendor_id'];
  } else {
    $vendor = Vendor::find_by_user_id($session->get_user_id());
    $product_args['vendor_id'] = $vendor->vendor_id ?? null;
    if (!$product_args['vendor_id']) {
      $errors[] = "Error: No associated vendor account found.";
    }
  }

  $product = new Product($product_args);

  if (!empty($_FILES['product_image']['name'])) {
    $upload_result = $product->upload_image($_FILES['product_image']);
    if (!$upload_result['success']) {
      $errors[] = $upload_result['message'];
    }
  }

  // **Ensure at least one price is set**
  if (empty($_POST['product_price_unit']) || !array_filter($_POST['product_price_unit'], function ($unit) {
    return isset($unit['price']) && floatval($unit['price']) > 0;
  })) {
    $errors[] = "At least one price must be set.";
  }

  if (empty($errors) && $product->create()) {
    $new_product_id = $product->product_id;

    foreach ($_POST['product_price_unit'] as $unit_id => $unit_data) {
      if (!empty($unit_data['price']) && floatval($unit_data['price']) > 0) {
        $price_unit_args = [
          'product_id' => $new_product_id,
          'price_unit_id' => $unit_id,
          'price' => $unit_data['price']
        ];
        $product_price_unit = new ProductPriceUnit($price_unit_args);
        $product_price_unit->create();
      }
    }

    $_SESSION['message'] = "Product successfully added.";
    redirect_to('manage.php');
  }
}
?>

<main role="main" id="main">
  <div class="container mt-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="mb-4">Add Product</h1>
        <p>Use the form below to add a new product.</p>

        <?php echo display_errors($errors); ?>

        <form action="new.php" method="post" enctype="multipart/form-data">
          <div class="row">
            <?php if ($is_admin) { ?>
              <div class="mb-3">
                <label for="vendor_id" class="form-label">Vendor</label>
                <select name="vendor_id" id="vendor_id" class="form-select" required>
                  <option value="">Select a Vendor</option>
                  <?php foreach (Vendor::find_all() as $vendor) { ?>
                    <option value="<?= h($vendor->vendor_id); ?>" <?= (isset($_POST['vendor_id']) && $_POST['vendor_id'] == $vendor->vendor_id) ? 'selected' : ''; ?>>
                      <?= h($vendor->business_name); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            <?php } ?>

            <?php include('form_fields.php'); ?>
          </div>

          <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="manage.php" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
