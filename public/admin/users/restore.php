<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || !$session->is_super_admin()) {
  redirect_to(url_for('/login.php'));
  exit;
}

if (is_post_request()) {
  $user_id = $_POST['user_id'] ?? null;

  $user = User::find_by_id($user_id);

  if ($user && $user->is_admin() && $user->account_status === 'suspended') {
    $user->account_status = 'active';

    if ($user->update()) {
      $session->message("Admin account for {$user->full_name()} has been restored.");
    } else {
      $session->message("Error: Unable to restore admin.");
    }
  } else {
    $session->message("Invalid or non-suspended admin.");
  }
} else {
  $session->message("Invalid request.");
}

redirect_to(url_for('/admin/users/manage.php'));
