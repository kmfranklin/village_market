<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || !$session->is_super_admin()) {
  redirect_to(url_for('/login.php'));
  exit;
}

if (is_post_request()) {
  $user_id = $_POST['user_id'] ?? null;

  $user = User::find_by_id($user_id);

  if ($user && $user->is_admin()) {
    $user->account_status = 'suspended';

    if ($user->update()) {
      $session->message("Admin account for {$user->full_name()} has been suspended.");
    } else {
      $session->message("Error: Could not suspend admin account.");
    }
  } else {
    $session->message("Invalid admin user.");
  }
} else {
  $session->message("Invalid request method.");
}

redirect_to(url_for('/admin/users/manage.php'));
