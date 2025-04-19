<?php

/**
 * Redirects to the login page if the current session is not authenticated.
 *
 * @global Session $session
 */
function require_login()
{
  global $session;
  if (!$session->is_logged_in()) {
    redirect_to(url_for('/login.php'));
  }
}

/**
 * Generates an HTML block to display a list of validation errors.
 *
 * @param array $errors An array of error messages.
 * @return string HTML-formatted list of errors.
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
 * Displays and clears a success message stored in the session.
 *
 * @global Session $session
 * @return string HTML-formatted success alert, or an empty string if no message exists.
 */
function display_session_message()
{
  global $session;
  $msg = $session->message();

  if (!empty($msg)) {
    $session->clear_message();
    return '
      <div class="d-flex justify-content-center">
        <div class="alert alert-success alert-dismissible fade show alert-centered" role="alert">
          ' . h($msg) . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>';
  }

  return '';
}
