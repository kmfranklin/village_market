<?php
require_once('../private/initialize.php');

// Initialize variables
$errors = [];
$email_address = '';
$password = '';

if (is_post_request()) {
  // Retrieve form values
  $email_address = $_POST['email_address'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validate form input
  if (is_blank($email_address)) {
    $errors[] = "Email address cannot be blank.";
  }
  if (is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  if (empty($errors)) {
    // Find user by email
    $user = User::find_by_email(strtolower($email_address));

    if (!$user) {
      // User not found
      $errors[] = "Invalid login credentials.";
    } elseif (!$user->verify_password($password)) {
      // Incorrect password
      $errors[] = "Invalid login credentials.";
    } else {
      // Check account activation
      if ($user->is_active == 0) {
        if ($user->is_vendor()) {
          $errors[] = "Your vendor account has not been approved yet. Please try again later.";
        } elseif ($user->is_admin() || $user->is_super_admin()) {
          $errors[] = "Your account has been deactivated. Please contact an Administrator.";
        } else {
          $errors[] = "Your account is inactive. Please contact an Administrator.";
        }
      } else {
        // Successful login
        $session->login($user);

        // Redirect based on role
        if ($user->is_super_admin()) {
          redirect_to(url_for('/admin/dashboard.php'));
        } elseif ($user->is_admin()) {
          redirect_to(url_for('/admin/dashboard.php'));
        } elseif ($user->is_vendor()) {
          redirect_to(url_for('/vendors/dashboard.php'));
        } else {
          $errors[] = "Unexpected error: Your account role cannot be recognized. Please contact an Administrator.";
        }
      }
    }
  }
}

$page_title = 'Log In';
include(SHARED_PATH . '/public_header.php');
?>

<main role="main" id="main">
  <h1>Log In</h1>

  <?php echo display_errors($errors); ?>

  <form action="login.php" method="post">
    <label for="email_address">Email Address:</label>
    <input type="email" id="email_address" name="email_address" value="<?php echo h($email_address); ?>"><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br>
    <button type="submit">Log In</button>
    <p><a href="forgot_password.php">Forgot your password?</a></p>
  </form>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
