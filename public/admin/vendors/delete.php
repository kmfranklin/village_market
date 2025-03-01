<?php
require_once('../../../private/initialize.php');

if (!$session->is_admin() && !$session->is_super_admin()) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to(url_for('/admin/vendors/manage.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vendor_id = $_POST['entity_id'] ?? null;

  if (!$vendor_id) {
    $_SESSION['message'] = "Vendor ID is missing.";
    error_log("DEBUG: Vendor ID is missing in delete.php");
    redirect_to(url_for('/admin/vendors/manage.php'));
  }

  $vendor = Vendor::find_by_id($vendor_id);

  if (!$vendor) {
    $_SESSION['message'] = "Vendor not found.";
    error_log("DEBUG: Vendor with ID {$vendor_id} not found.");
    redirect_to(url_for('/admin/vendors/manage.php'));
  }

  if ($vendor->delete()) {
    $_SESSION['message'] = "Vendor deleted successfully.";
  } else {
    $_SESSION['message'] = "Error: Unable to delete vendor.";
    error_log("DEBUG: Vendor deletion failed for ID {$vendor_id}.");
  }

  redirect_to(url_for('/admin/vendors/manage.php'));
}
