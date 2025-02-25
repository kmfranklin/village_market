<?php

require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access. Please log in.";
  redirect_to(url_for('/login.php'));
  exit;
}

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
  $_SESSION['message'] = "Error: Vendor ID is missing.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

$user = User::find_by_id($user_id);

// Ensure the user exists and is a vendor
if (!$user || !$user->is_vendor()) {
  $_SESSION['message'] = "Error: Vendor not found.";
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
