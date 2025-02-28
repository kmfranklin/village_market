<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$errors = [];
$vendor = new Vendor();
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_args = $_POST['user'];
  $user_args['role_id'] = User::VENDOR;
  $user_args['account_status'] = 'active';
  $user = new User($user_args);

  if ($user->create()) {
    $vendor_args = $_POST['vendor'];
    $vendor_args['user_id'] = $user->user_id;
    $vendor = new Vendor($vendor_args);

    if ($vendor->create()) {
      $_SESSION['message'] = "Vendor successfully added and activated.";
      redirect_to('manage.php');
    } else {
      $errors = $vendor->errors;
    }
  } else {
    $errors = $user->errors;
  }
}

$page_title = 'Add Vendor';
include_header($session);
?>

<main role="main" id="main">
  <h1>Add Vendor</h1>
  <p>Use the form below to add and activate a new vendor.</p>
  <?php echo display_errors($errors); ?>

  <form action="new.php" method="post">
    <?php include('../users/form_fields.php'); ?>
    <?php include('../../vendors/form_fields.php'); ?>
    <input type="submit" value="Add Vendor">
  </form>

  <br>
  <a href="manage.php">⬅ Back to Vendor Management</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
