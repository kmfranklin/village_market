<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$errors = [];
$product = new Product();
$is_admin = $session->is_admin() || $session->is_super_admin();
$product_price_units = []; // Array to store multiple price units

$header_file = $is_admin ? 'admin_header.php' : 'vendor_header.php';
include(SHARED_PATH . '/' . $header_file);

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

    if ($product->create()) {
      $new_product_id = $product->product_id;

      // Loop through selected price units
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
      $errors = $product->errors;
    }
  }
}
?>

<main role="main" id="main">
  <h1>Add Product</h1>
  <p>Use the form below to add a new product.</p>
  <?php echo display_errors($errors); ?>

  <form action="new.php" method="post" enctype="multipart/form-data">
    <?php if ($is_admin) { ?>
      <label for="vendor_id">Vendor:</label>
      <select name="vendor_id" id="vendor_id" required>
        <option value="">Select a Vendor</option>
        <?php
        $vendors = Vendor::find_all();
        foreach ($vendors as $vendor) {
          echo "<option value=\"{$vendor->vendor_id}\">{$vendor->business_name}</option>";
        }
        ?>
      </select>
    <?php } ?>

    <?php include('form_fields.php'); ?>

    <input type="submit" value="Add Product">
  </form>

  <br>
  <a href="manage.php">⬅ Back to Product Management</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
