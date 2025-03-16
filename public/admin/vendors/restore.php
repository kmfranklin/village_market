<?php

require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access. Please log in.";
  redirect_to(url_for('/login.php'));
  exit;
}

$user_id = (int) ($_POST['user_id'] ?? 0);

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

// Restore based on current status
if ($user->account_status === 'suspended') {
  $user->account_status = 'active';
  $restore_message = "Vendor restored successfully.";
} elseif ($user->account_status === 'rejected') {
  $user->account_status = 'pending';
  $restore_message = "Vendor application restored to pending.";
} else {
  $_SESSION['message'] = "Error: This vendor cannot be restored.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

// Update the database
if ($user->update()) {
  $_SESSION['message'] = $restore_message;
} else {
  $_SESSION['message'] = "Error restoring vendor.";
}

redirect_to(url_for('/admin/vendors/manage.php'));
exit;
