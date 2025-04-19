<?php
require_once('../../private/initialize.php');

$is_admin = $session->is_logged_in() && ($session->is_admin() || $session->is_super_admin());
$errors = [];
$user = new User();
$vendor = new Vendor();

if (is_post_request()) {
  $user_args = $_POST['user'];
  $vendor_args = $_POST['vendor'];

  // Normalize & sanitize
  $user_args['email_address'] = strtolower(trim($user_args['email_address'] ?? ''));
  $vendor_args['business_email_address'] = strtolower(trim($vendor_args['business_email_address'] ?? ''));

  $user_args['first_name'] = ucwords(strtolower(trim($user_args['first_name'] ?? '')));
  $user_args['last_name'] = ucwords(strtolower(trim($user_args['last_name'] ?? '')));
  $vendor_args['business_name'] = ucwords(strtolower(trim($vendor_args['business_name'] ?? '')));
  $vendor_args['street_address'] = ucwords(strtolower(trim($vendor_args['street_address'] ?? '')));
  $vendor_args['city'] = ucwords(strtolower(trim($vendor_args['city'] ?? '')));
  $user_args['phone_number'] = trim($user_args['phone_number'] ?? '');
  $user_args['role_id'] = User::VENDOR;
  $vendor_args['state_id'] = isset($vendor_args['state_id']) ? (int)$vendor_args['state_id'] : null;
  $vendor_args['zip_code'] = trim($vendor_args['zip_code'] ?? '');
  $vendor_args['business_phone_number'] = trim($vendor_args['business_phone_number'] ?? '');

  // Set account status: admin-created = active, otherwise pending
  $user_args['account_status'] = $is_admin ? 'active' : 'pending';

  // Handle password
  $user_args['password'] = $_POST['user']['password'] ?? '';
  $user_args['confirm_password'] = $_POST['user']['confirm_password'] ?? '';

  // Validate
  $user = new User($user_args);
  $user_errors = $user->validate();

  $vendor = new Vendor($vendor_args);
  $vendor_errors = $vendor->validate();

  $errors = array_merge($user_errors, $vendor_errors);

  // Check reCAPTCHA
  $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

  if (empty($recaptcha_response)) {
    $errors[] = "Please complete the CAPTCHA.";
  } else {
    $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_SECRET_KEY . "&response=" . $recaptcha_response;
    $response_data = json_decode(file_get_contents($verify_url), true);

    if (!$response_data['success']) {
      $errors[] = "CAPTCHA verification failed. Please try again.";
    }
  }

  if (empty($errors)) {
    DatabaseObject::$database->begin_transaction();
    try {
      if ($user->create()) {
        $vendor_args['user_id'] = $user->user_id;
        $vendor = new Vendor($vendor_args);
        if ($vendor->create()) {
          DatabaseObject::$database->commit();

          if ($is_admin) {
            $session->message("Vendor successfully added and activated.");
            redirect_to(url_for('/admin/vendors/manage.php'));
          } else {
            $session->message("Vendor registration successful! Please wait for admin approval.");
            redirect_to(url_for('/index.php'));
          }
        } else {
          throw new Exception("Vendor creation failed.");
        }
      } else {
        throw new Exception("User creation failed.");
      }
    } catch (Exception $e) {
      DatabaseObject::$database->rollback();
      $errors[] = $e->getMessage();
    }
  }
}

$page_title = $is_admin ? 'Add Vendor' : 'Vendor Registration';
require_once(SHARED_PATH . '/include_header.php');
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="mb-3 text-center"><?= $is_admin ? 'Add Vendor' : 'Register as a Vendor' ?></h1>
          <p class="text-center">
            <?= $is_admin
              ? 'Use the form below to add and activate a new vendor.'
              : 'Use the form below to register as a vendor with the Village Market. An admin will review your submission shortly.' ?>
          </p>

          <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
              <strong>Error:</strong> Please fix the following issues:
              <ul class="mb-0">
                <?php foreach ($errors as $error) : ?>
                  <li><?= h($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form action="<?= h($_SERVER['PHP_SELF']); ?>" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
            <?php include('../admin/users/form_fields.php'); ?>
            <?php include('./form_fields.php'); ?>
            <div class="mb-3 d-flex justify-content-end">
              <div class="g-recaptcha" data-sitekey="<?php echo h(RECAPTCHA_SITE_KEY); ?>"></div>
            </div>

            <div class="d-flex justify-content-between mt-3">
              <a href="<?= url_for($is_admin ? '/admin/vendors/manage.php' : '/index.php'); ?>" class="btn btn-outline-secondary">
                <?= $is_admin ? '<i class="bi bi-arrow-left"></i> Back to Vendor Management' : 'Cancel'; ?>
              </a>
              <button type="submit" class="btn btn-primary mx-3"><?= $is_admin ? 'Add Vendor' : 'Register'; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
