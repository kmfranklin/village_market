<?php

require_once('../../private/initialize.php');

// Fetch states for dropdown
$sql = "SELECT * FROM state";
$result = DatabaseObject::$database->query($sql);
$states = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $states[] = $row;
  }
} else {
  die("Database query failed: " . DatabaseObject::$database->error);
}

$errors = [];
$args = [];
$user = new User();
$vendor = new Vendor();

if (is_post_request()) {
  // Convert emails to lowercase before storing
  $args['email_address'] = strtolower(trim($_POST['email_address'] ?? ''));
  $args['business_email_address'] = strtolower(trim($_POST['business_email_address'] ?? ''));

  // Capitalize first letter of names and addresses before storing
  $args['first_name'] = ucwords(strtolower(trim($_POST['first_name'] ?? '')));
  $args['last_name'] = ucwords(strtolower(trim($_POST['last_name'] ?? '')));
  $args['business_name'] = ucwords(strtolower(trim($_POST['business_name'] ?? '')));
  $args['street_address'] = ucwords(strtolower(trim($_POST['street_address'] ?? '')));
  $args['city'] = ucwords(strtolower(trim($_POST['city'] ?? '')));

  $args['password'] = $_POST['password'] ?? '';
  $args['confirm_password'] = $_POST['confirm_password'] ?? '';
  $args['phone_number'] = trim($_POST['phone_number'] ?? '');
  $args['role_id'] = User::VENDOR;
  $args['state_id'] = isset($_POST['state_id']) ? (int) $_POST['state_id'] : null;
  $args['zip_code'] = trim($_POST['zip_code'] ?? '');
  $args['business_phone_number'] = trim($_POST['business_phone_number'] ?? '');

  // Create and validate User
  $user = new User($args);
  $errors = array_merge($errors, $user->validate());

  // Create and validate Vendor
  $vendor = new Vendor($args);
  $errors = array_merge($errors, $vendor->validate());

  if (empty($errors)) {
    DatabaseObject::$database->begin_transaction();

    try {
      if ($user->create()) {
        $args['user_id'] = $user->user_id;
        $vendor = new Vendor($args);

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
} elseif (is_post_request()) {
  $errors[] = 'User creation failed.';
}

$page_title = "Vendor Registration";
include(SHARED_PATH . '/public_header.php');
?>

<main>
  <h1>Register as a Vendor</h1>
  <?php echo display_errors($errors); ?>
  <p>Use the form below to register as a vendor with the Village Market. An admin will review your submission and respond shortly.</p>

  <form action="register.php" method="post">
    <?php include('../admin/users/form_fields.php'); ?>
    <?php include('./form_fields.php'); ?>
    <input type="submit" value="Register">
  </form>
</main>
