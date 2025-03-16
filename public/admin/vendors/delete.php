<?php
require_once('../../../private/initialize.php');

if (!$session->is_admin() && !$session->is_super_admin()) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vendor_id = $_POST['entity_id'] ?? null;

  if (!$vendor_id) {
    $_SESSION['message'] = "Vendor ID is missing.";
    error_log("DEBUG: Vendor ID is missing in delete.php");
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Fetch vendor and associated user
  $vendor = Vendor::find_by_id($vendor_id);

  if (!$vendor) {
    $_SESSION['message'] = "Vendor not found.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Fetch user account associated with the vendor
  /** @var Vendor $vendor */
  $user = User::find_by_id($vendor->user_id);

  if (!$user) {
    $_SESSION['message'] = "Associated user not found. Cannot delete vendor.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Allow deletion only if vendor is suspended or rejected
  if ($user->account_status !== 'suspended' && $user->account_status !== 'rejected') {
    $_SESSION['message'] = "Error: Only suspended or rejected vendors can be deleted.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Proceed with deletion
  $delete_vendor = $vendor->delete();
  $delete_user = $user->delete();

  if ($delete_vendor && $delete_user) {
    $_SESSION['message'] = "Vendor and associated user deleted successfully.";
  } else {
    $_SESSION['message'] = "Error: Unable to delete vendor.";
  }

  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}
