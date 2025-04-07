<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || !$session->is_super_admin()) {
  redirect_to(url_for('/login.php'));
}

// Require POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect_to(url_for('/admin/users/manage.php'));
}

// Get user_id from form
$user_id = $_POST['user_id'] ?? null;
if (!$user_id) {
  $session->message('Invalid request: Missing user ID.');
  redirect_to(url_for('/admin/users/manage.php'));
}

$user = User::find_by_id($user_id);

if (!$user) {
  $session->message('User not found.');
  redirect_to(url_for('/admin/users/manage.php'));
}

// Prevent deleting non-Admins or Super Admins
if (!$user->is_admin()) {
  $session->message('Only Admins can be deleted.');
  redirect_to(url_for('/admin/users/manage.php'));
}

// Proceed with deletion
if ($user->delete()) {
  $session->message('Admin deleted successfully.');
} else {
  $session->message('Error: Unable to delete admin.');
}

redirect_to(url_for('/admin/users/manage.php'));
