<?php
require_once('../../private/initialize.php');

$errors = [];
$user = new User();
$vendor = new Vendor();

if (is_post_request()) {
  $user_args = $_POST['user'];
  $vendor_args = $_POST['vendor'];

  // Convert emails to lowercase
  $user_args['email_address'] = strtolower(trim($user_args['email_address'] ?? ''));
  $vendor_args['business_email_address'] = strtolower(trim($vendor_args['business_email_address'] ?? ''));

  // Capitalize first letter of names/addresses
  $user_args['first_name'] = ucwords(strtolower(trim($user_args['first_name'] ?? '')));
  $user_args['last_name'] = ucwords(strtolower(trim($user_args['last_name'] ?? '')));
  $vendor_args['business_name'] = ucwords(strtolower(trim($vendor_args['business_name'] ?? '')));
  $vendor_args['street_address'] = ucwords(strtolower(trim($vendor_args['street_address'] ?? '')));
  $vendor_args['city'] = ucwords(strtolower(trim($vendor_args['city'] ?? '')));

  $user_args['password'] = $_POST['user']['password'] ?? '';
  $user_args['confirm_password'] = $_POST['user']['confirm_password'] ?? '';
  $user_args['phone_number'] = trim($user_args['phone_number'] ?? '');
  $user_args['role_id'] = User::VENDOR;
  $vendor_args['state_id'] = isset($vendor_args['state_id']) ? (int)$vendor_args['state_id'] : null;
  $vendor_args['zip_code'] = trim($vendor_args['zip_code'] ?? '');
  $vendor_args['business_phone_number'] = trim($vendor_args['business_phone_number'] ?? '');

  // Create and validate User
  $user = new User($user_args);
  $user_errors = $user->validate();

  // Create and validate Vendor
  $vendor = new Vendor($vendor_args);
  $vendor_errors = $vendor->validate();

  // Merge field-specific errors
  $errors = array_merge($user_errors, $vendor_errors);

  if (empty($errors)) {
    DatabaseObject::$database->begin_transaction();
    try {
      if ($user->create()) {
        $vendor_args['user_id'] = $user->user_id;
        $vendor = new Vendor($vendor_args);
        if ($vendor->create()) {
          DatabaseObject::$database->commit();
          $session->message('Vendor registration successful! Please wait for admin approval.');
          redirect_to(url_for('/index.php'));
        } else {
          throw new Exception('Vendor creation failed.');
        }
      } else {
        throw new Exception('User creation failed.');
      }
    } catch (Exception $e) {
      DatabaseObject::$database->rollback();
      $errors[] = $e->getMessage();
    }
  }
}

$page_title = "Vendor Registration";
include(SHARED_PATH . '/public_header.php');
?>

<main class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="mb-3 text-center">Register as a Vendor</h1>
          <p class="text-center">Use the form below to register as a vendor with the Village Market. An admin will review your submission and respond shortly.</p>

          <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $error) { ?>
                  <li><?php echo h($error); ?></li>
                <?php } ?>
              </ul>
            </div>
          <?php } ?>

          <form action="register.php" method="post">
            <!-- User Form Fields -->
            <?php include('../admin/users/form_fields.php'); ?>

            <!-- Vendor Form Fields -->
            <?php include('./form_fields.php'); ?>

            <div class="d-flex justify-content-between mt-3">
              <button type="submit" class="btn btn-primary">Register</button>
              <a href="<?php echo url_for('/index.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
