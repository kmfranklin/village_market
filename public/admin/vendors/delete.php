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

  // Debugging: Log incoming IDs
  error_log("DEBUG: Vendor ID received: " . print_r($vendor_id, true));
  error_log("DEBUG: User ID received: " . print_r($user_id, true));

  if (!$vendor_id || !$user_id) {
    $_SESSION['message'] = "Error: Vendor ID or User ID is missing.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Find the vendor by ID
  $vendor = Vendor::find_by_id($vendor_id);
  if (!$vendor) {
    $_SESSION['message'] = "Error: Vendor not found.";
    error_log("DEBUG: Vendor with ID {$vendor_id} not found.");
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

  // Debugging: Log user status
  error_log("DEBUG: Vendor {$vendor_id} is associated with User ID {$user_id} having account_status: {$user->account_status}");

  // Ensure vendor is suspended or rejected before allowing deletion
  if (!in_array($user->account_status, ['suspended', 'rejected'])) {
    $_SESSION['message'] = "Error: Only suspended or rejected vendors can be deleted.";
    redirect_to(url_for('/admin/vendors/manage.php'));
    exit;
  }

  // Delete vendor first to avoid foreign key constraints
  if ($vendor->delete()) {
    // If vendor deleted successfully, also delete the associated user
    if ($user->delete()) {
      $_SESSION['message'] = "Vendor and associated user account deleted successfully.";
    } else {
      $_SESSION['message'] = "Vendor deleted, but unable to delete associated user.";
      error_log("DEBUG: User deletion failed for ID {$user_id}.");
    }
  } else {
    $_SESSION['message'] = "Error: Unable to delete vendor.";
    error_log("DEBUG: Vendor deletion failed for ID {$vendor_id}.");
  }

  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}
