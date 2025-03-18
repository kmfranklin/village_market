<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

$vendor_id = $_POST['vendor_id'] ?? null;

if (!$vendor_id) {
  $_SESSION['message'] = "Error: Vendor ID is missing.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

$vendor = Vendor::find_by_id($vendor_id);

// 🔹 Check if vendor exists before proceeding
if (!$vendor) {
  $_SESSION['message'] = "Error: Vendor not found.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

/** @var Vendor $vendor */
$user = User::find_by_id($vendor->user_id);

if (!$user) {
  $_SESSION['message'] = "Error: Associated user not found.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

$user->account_status = 'suspended';

if ($user->update()) {
  $_SESSION['message'] = "Vendor suspended successfully.";
} else {
  $_SESSION['message'] = "Error suspending vendor.";
}

redirect_to(url_for('/admin/vendors/manage.php'));
exit;
