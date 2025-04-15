<?php
require_once '../../private/initialize.php';

$page_title = "Edit My Profile";
include_header($session, $page_title);
echo display_session_message();

// Check if the user is signed in and is a vendor or admin
if (
  !$session->is_logged_in() ||
  (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())
) {
  redirect_to(url_for('/login.php'));
}

$user_id = $session->is_admin() || $session->is_super_admin()
  ? ($_GET['id'] ?? null)
  : $session->get_user_id();

if (!$user_id || !is_numeric($user_id)) {
  $session->message("Invalid or missing vendor ID.");
  redirect_to(url_for('/index.php'));
}

$user = User::find_by_id($user_id);
$vendor = Vendor::find_by_user_id($user_id);

if (!$user || !$vendor) {
  $session->message("Vendor profile not found.");
  redirect_to(url_for('/index.php'));
}

if (isset($_POST['delete_logo'])) {
  $vendor->business_logo_url = '';
  $vendor->update();
  $session->message("Logo removed.");
  $redirect_url = ($session->is_admin() || $session->is_super_admin())
    ? url_for('/vendors/profile.php?id=' . h($user_id))
    : url_for('/vendors/profile.php');
  redirect_to($redirect_url);
}

if (isset($_POST['delete_business_image'])) {
  $vendor->business_image_url = '';
  $vendor->update();
  $session->message("Business image removed.");
  $redirect_url = ($session->is_admin() || $session->is_super_admin())
    ? url_for('/vendors/profile.php?id=' . h($user_id))
    : url_for('/vendors/profile.php');
  redirect_to($redirect_url);
}

$errors = [];

if (is_post_request()) {
  // Merge user and vendor form inputs
  $user->merge_attributes($_POST['user'] ?? []);
  $vendor->merge_attributes($_POST['vendor'] ?? []);

  // Upload logo (if provided)
  if (!empty($_FILES['logo']['tmp_name'])) {
    $upload_result = ImageUploader::upload($_FILES['logo'], 'vendor_logos', 'vendor_logo_');
    if ($upload_result['success']) {
      $vendor->business_logo_url = $upload_result['url'];
    }
  }

  // Upload business image (if provided)
  if (!empty($_FILES['business_image']['tmp_name'])) {
    $upload_result = ImageUploader::upload($_FILES['business_image'], 'vendor_images', 'vendor_img_');
    if ($upload_result['success']) {
      $vendor->business_image_url = $upload_result['url'];
    }
  }

  // Save both user and vendor
  $valid = $user->update();
  $valid = $vendor->update() && $valid;


  // Redirect to the correct page based on user role after update
  if ($valid) {
    $session->message("Profile updated successfully.");
    // If the current user updated their own profile, refresh session display values
    if ($session->user_id == $user->user_id) {
      $_SESSION['first_name'] = $user->first_name;
      $_SESSION['last_name'] = $user->last_name;
    }
    $redirect_url = ($session->is_admin() || $session->is_super_admin())
      ? url_for('/admin/vendors/manage.php')
      : url_for('/vendors/dashboard.php');
    redirect_to($redirect_url);
  } else {
    $errors = array_merge($user->errors, $vendor->errors);
  }
}
?>

<main role="main" class="container mt-4">
  <h1 class="text-primary">
    <?= $session->is_admin() || $session->is_super_admin() ? 'Edit Vendor Profile' : 'Update Your Profile'; ?>
  </h1>

  <p class="lead">Use the form below to update personal and business information.</p>
  <form action="<?= url_for('/vendors/profile.php' . ($session->is_admin() || $session->is_super_admin() ? '?id=' . h($user_id) : '')); ?>"
    method="post" enctype="multipart/form-data" class="my-5">

    <?php include('../admin/users/form_fields.php'); ?>
    <?php include('./form_fields.php'); ?>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Save Profile</button>
    </div>
  </form>

  <?php include(SHARED_PATH . '/footer.php'); ?>
