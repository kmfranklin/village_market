<?php

require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

// Get user ID from URL
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
  $_SESSION['message'] = "Vendor ID missing.";
  redirect_to('manage.php');
}

// Fetch existing vendor and user details
$user = User::find_by_id($user_id);
$vendor = Vendor::find_by_user_id($user_id);

if (!$user || !$vendor) {
  $_SESSION['message'] = "Vendor not found.";
  redirect_to('manage.php');
}

$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_args = $_POST['user'];
  $vendor_args = $_POST['vendor'];

  $user_args['user_id'] = $user->user_id;
  $vendor_args['user_id'] = $vendor->user_id;

  if (empty($user_args['password'])) {
    unset($user_args['password'], $user_args['confirm_password']);
  }

  $user->merge_attributes($user_args);
  $vendor->merge_attributes($vendor_args);

  if ($user->update() && $vendor->update()) {
    $_SESSION['message'] = "Vendor details updated successfully.";
    redirect_to('manage.php');
  } else {
    $errors = array_merge($user->errors, $vendor->errors);
  }
}

$page_title = 'Edit Vendor';
include_header($session);
?>

<main role="main" id="main">
  <h1>Edit Vendor</h1>
  <?php echo display_errors($errors); ?>

  <form action="edit.php?id=<?php echo h($user_id); ?>" method="post">
    <?php include('../users/form_fields.php'); ?>
    <?php include('../../vendors/form_fields.php'); ?>
    <input type="submit" value="Save Changes">
  </form>

  <br>
  <a href="manage.php">⬅ Back to Vendor Management</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
