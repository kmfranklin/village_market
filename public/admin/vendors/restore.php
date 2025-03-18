<?php

require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access. Please log in.";
  redirect_to(url_for('/login.php'));
  exit;
}

error_log(print_r($_POST, true));

$user_id = (int) ($_POST['user_id'] ?? 0);

if (!$user_id) {
  error_log("ERROR: User ID is missing in restore.php.");
  $_SESSION['message'] = "Error: User ID is missing.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

$user = User::find_by_id($user_id);

if (!$user) {
  error_log("ERROR: User not found for user_id = " . h($user_id));
  $_SESSION['message'] = "Error: User not found.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

if (!$user->is_vendor()) {
  error_log("ERROR: User with ID {$user_id} is not a vendor.");
  $_SESSION['message'] = "Error: User is not a vendor.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

if ($user->account_status === 'suspended') {
  $user->account_status = 'active';
  $restore_message = "Vendor restored successfully.";
} elseif ($user->account_status === 'rejected') {
  $user->account_status = 'pending';
  $restore_message = "Vendor application restored to pending.";
} else {
  error_log("ERROR: User ID {$user_id} cannot be restored from status '{$user->account_status}'");
  $_SESSION['message'] = "Error: This vendor cannot be restored.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

if ($user->update()) {
  $_SESSION['message'] = $restore_message;
  error_log("SUCCESS: User ID {$user_id} restored to status '{$user->account_status}'.");
} else {
  error_log("ERROR: Failed to restore User ID {$user_id}.");
  $_SESSION['message'] = "Error restoring vendor.";
}

redirect_to(url_for('/admin/vendors/manage.php'));
exit;
