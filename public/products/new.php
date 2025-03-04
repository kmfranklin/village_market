<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$errors = [];
$product = new Product();
$is_admin = $session->is_admin() || $session->is_super_admin();
$product_price_units = []; // Array to store multiple price units

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

  if (empty($errors)) {
    $product = new Product($product_args);

    // **Handle Image Upload**
    if (!empty($_FILES['product_image']['name'])) {
      $upload_result = $product->upload_image($_FILES['product_image']);

      if (!$upload_result['success']) {
        $errors[] = $upload_result['message'];
      }
    }

    if (empty($errors) && $product->create()) {
      $new_product_id = $product->product_id;

      // **Loop through selected price units**
      foreach ($_POST['product_price_unit'] as $unit_id => $unit_data) {
        if (isset($unit_data['selected']) && isset($unit_data['price']) && $unit_data['price'] > 0) {
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
    } else {
      $errors = array_merge($errors, $product->errors);
    }
  }
}
?>

<main role="main" id="main">
  <div class="container mt-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h3 mb-4">Add Product</h1>
        <p>Use the form below to add a new product.</p>

        <?php echo display_errors($errors); ?>

        <form action="new.php" method="post" enctype="multipart/form-data">
          <div class="row">
            <!-- Vendor Selection for Admins -->
            <?php if ($is_admin) { ?>
              <div class="mb-3">
                <label for="vendor_id" class="form-label">Vendor</label>
                <select name="vendor_id" id="vendor_id" class="form-select" required>
                  <option value="">Select a Vendor</option>
                  <?php
                  $vendors = Vendor::find_all();
                  foreach ($vendors as $vendor) {
                    echo "<option value=\"{$vendor->vendor_id}\">{$vendor->business_name}</option>";
                  }
                  ?>
                </select>
              </div>
            <?php } ?>

            <!-- Include Form Fields -->
            <?php include('form_fields.php'); ?>
          </div>

          <!-- Submit & Cancel Buttons -->
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
