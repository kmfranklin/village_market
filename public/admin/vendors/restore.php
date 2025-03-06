<?php

require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access. Please log in.";
  redirect_to(url_for('/login.php'));
  exit;
}

// DEBUG: Log `$_POST` to verify correct data is passed
error_log(print_r($_POST, true));

$user_id = (int) ($_POST['user_id'] ?? 0); // Ensure `user_id` is an integer

if (!$user_id) {
  $_SESSION['message'] = "Error: User ID is missing.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

// Find the user by ID
$user = User::find_by_id($user_id);

// Validate that the user exists and is a vendor
if (!$user || !$user->is_vendor()) {
  $_SESSION['message'] = "Error: User not found or is not a vendor.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

// Ensure the vendor is actually suspended before restoring
if ($user->account_status !== 'suspended') {
  $_SESSION['message'] = "Error: This vendor is not currently suspended.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

// Change account_status back to 'active'
$user->account_status = 'active';

if ($user->update()) {
  $_SESSION['message'] = "Vendor restored successfully.";
} else {
  $_SESSION['message'] = "Error restoring vendor.";
}

redirect_to(url_for('/admin/vendors/manage.php'));
exit;
