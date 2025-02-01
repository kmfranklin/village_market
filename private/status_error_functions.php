<?php

/**
 * Require a user to be logged in.
 * Redirects to the login page if not authenticated.
 *
 * @return void
 */
function require_login()
{
  global $session;
  if (!$session->is_logged_in()) {
    redirect_to(url_for('/login.php')); // Updated path for Farmers Market
  }
}

/**
 * Display a formatted list of errors.
 *
 * @param array $errors Array of error messages.
 * @return string HTML output displaying errors.
 */
function display_errors($errors = array())
{
  $output = '';
  if (!empty($errors)) {
    $output .= "<div class=\"errors\">";
    $output .= "Please fix the following errors:";
    $output .= "<ul>";
    foreach ($errors as $error) {
      $output .= "<li>" . h($error) . "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

/**
 * Display a session message and clear it afterward.
 *
 * @return string|null The formatted session message or null if no message.
 */
function display_session_message()
{
  global $session;
  $msg = $session->message();
  if (isset($msg) && $msg != '') {
    $session->clear_message();
    return '<div id="message">' . h($msg) . '</div>';
  }
  return null;
}
