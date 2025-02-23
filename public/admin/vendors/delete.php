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

$vendor = Vendor::find_by_user_id($user_id);
$user = User::find_by_id($user_id);

if (!$vendor || !$user) {
  $_SESSION['message'] = "Vendor not found.";
  redirect_to(url_for('/admin/vendors/manage.php'));
  exit;
}

if ($vendor->delete() && $user->delete()) {
  $_SESSION['message'] = "Vendor and associated user account deleted successfully.";
} else {
  $_SESSION['message'] = "Error deleting vendor and user.";
}

redirect_to(url_for('/admin/vendors/manage.php'));
exit;
