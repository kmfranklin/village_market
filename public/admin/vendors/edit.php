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

<main role="main" class="container my-4">
  <header class="mb-4">
    <h1 class="text-primary">Edit Vendor</h1>
  </header>

  <!-- Display Errors -->
  <?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error) : ?>
          <li><?php echo h($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="edit.php?id=<?php echo h($user_id); ?>" method="post" class="mb-4">
    <fieldset class="border p-3 rounded">
      <?php include('../users/form_fields.php'); ?>
    </fieldset>

    <fieldset class="border p-3 rounded mt-3">
      <?php include('../../vendors/form_fields.php'); ?>
    </fieldset>

    <div class="mt-4 d-flex justify-content-between">
      <a href="manage.php" class="btn btn-outline-secondary">
        &larr; Back to Vendor Management
      </a>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
  </form>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
