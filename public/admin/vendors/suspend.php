<?php

require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to(url_for('/login.php'));
  exit;
}

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
  $_SESSION['message'] = "Vendor ID is missing.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

$user = User::find_by_id($user_id);

if (!$user || !$user->is_vendor()) {
  $_SESSION['message'] = "Vendor not found.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

// Update the account_status to 'suspended'
$user->account_status = 'suspended';
if ($user->update()) {
  $_SESSION['message'] = "Vendor suspended successfully.";
} else {
  $_SESSION['message'] = "Error suspending vendor.";
}

redirect_to(url_for('/admin/vendors/manage.php'));
exit;
