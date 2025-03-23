<?php
require_once '../../private/initialize.php';

$page_title = "Edit My Profile";
include(SHARED_PATH . '/vendor_header.php');

if (!$session->is_logged_in() || !$session->is_vendor()) {
  redirect_to(url_for('/login.php'));
}

$user_id = $session->get_user_id();
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
  redirect_to(url_for('/vendors/profile.php'));
}

if (isset($_POST['delete_business_image'])) {
  $vendor->business_image_url = '';
  $vendor->update();
  $session->message("Business image removed.");
  redirect_to(url_for('/vendors/profile.php'));
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

  if ($valid) {
    $session->message("Profile updated successfully.");
    redirect_to(url_for('/vendors/dashboard.php'));
  } else {
    $errors = array_merge($user->errors, $vendor->errors);
  }
}
?>

<main role="main" class="container mt-4">
  <h1 class="text-primary">Update Your Profile</h1>
  <p class="lead">Use the form below to update your personal and business information.</p>
  <form action="<?= url_for('/vendors/profile.php'); ?>" method="post" enctype="multipart/form-data" class="my-5">

    <?php include(PUBLIC_PATH . '../admin/users/form_fields.php'); ?>
    <?php include(PUBLIC_PATH . '/vendors/form_fields.php'); ?>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Save Profile</button>
    </div>
  </form>

  <?php include(SHARED_PATH . '/footer.php'); ?>
