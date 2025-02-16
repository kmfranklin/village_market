<?php
require_once('../private/initialize.php');

$errors = [];
$email_address = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email_address = $_POST['email_address'] ?? '';
  $password = $_POST['password'] ?? '';

  if (is_blank($email_address)) {
    $errors[] = "Email address cannot be blank.";
  }
  if (is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  if (empty($errors)) {
    // Find user by email
    $user = User::find_by_email(strtolower($email_address));

    if (!$user || !$user->verify_password($password)) {
      // User not found or incorrect password
      $errors[] = "Invalid login credentials.";
    } else {
      // Check account status
      $account_message = check_account_status($user);
      if ($account_message) {
        $errors[] = $account_message;
      } else {
        // Successful login
        $session->login($user);
        redirect_user($user);
      }
    }
  }
}

/**
 * Check if the user's account status prevents login.
 */
function check_account_status($user)
{
  switch ($user->account_status) {
    case 'pending':
      return $user->is_vendor()
        ? "Your vendor account has not been approved yet. Please try again later."
        : "Your account is inactive. Please contact an Administrator.";
    case 'suspended':
      return "Your account has been suspended. Please contact an Administrator.";
    case 'rejected':
      return "Your registration was rejected. You cannot log in. Check your email for details.";
    default:
      return null; // Account is active
  }
}

/**
 * Redirect user based on their role.
 */
function redirect_user($user)
{
  if ($user->is_super_admin() || $user->is_admin()) {
    redirect_to(url_for('/admin/dashboard.php'));
  } elseif ($user->is_vendor()) {
    redirect_to(url_for('/vendors/dashboard.php'));
  } else {
    global $errors;
    $errors[] = "Unexpected error: Your account role cannot be recognized. Please contact an Administrator.";
  }
}

$page_title = 'Log In';
include(SHARED_PATH . '/public_header.php');
?>

<main role="main" id="main">
  <h1>Log In</h1>

  <?php if (!empty($errors)) { ?>
    <div class="error-messages">
      <ul>
        <?php foreach ($errors as $error) { ?>
          <li><?php echo h($error); ?></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>

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
