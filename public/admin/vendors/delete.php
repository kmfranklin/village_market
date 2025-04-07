<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vendor_id = $_POST['entity_id'] ?? null; // entity_id is the vendor_id
  $user_id = $_POST['user_id'] ?? null; // Capture user_id

  if (!$vendor_id || !$user_id) {
    $_SESSION['message'] = "Error: Vendor ID or User ID is missing.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Find the vendor by ID
  $vendor = Vendor::find_by_id($vendor_id);
  if (!$vendor) {
    $_SESSION['message'] = "Error: Vendor not found.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Find the associated user by user_id from the vendor record
  $user = User::find_by_id($user_id);
  if (!$user) {
    $_SESSION['message'] = "Error: Associated user not found. Cannot delete vendor.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Ensure vendor is suspended or rejected before allowing deletion
  if (!in_array($user->account_status, ['suspended', 'rejected'])) {
    $_SESSION['message'] = "Error: Only suspended or rejected vendors can be deleted.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Delete vendor first to avoid foreign key constraints
  if ($vendor->delete()) {
    $_SESSION['message'] = "Vendor and associated user account deleted successfully.";
  } else {
    $_SESSION['message'] = "Error: Unable to delete vendor.";
  }

  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}
