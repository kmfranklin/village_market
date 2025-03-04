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
    $user = User::find_by_email(strtolower($email_address));

    if (!$user || !$user->verify_password($password)) {
      $errors[] = "Invalid login credentials.";
    } else {
      $account_message = check_account_status($user);
      if ($account_message) {
        $errors[] = $account_message;
      } else {
        $session->login($user);
        redirect_user($user);
      }
    }
  }
}

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
      return null;
  }
}

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
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4">
          <h1 class="text-center mb-4">Log In</h1>

          <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $error) { ?>
                  <li><?php echo h($error); ?></li>
                <?php } ?>
              </ul>
            </div>
          <?php } ?>

          <form action="login.php" method="post">
            <div class="mb-3">
              <label for="email_address" class="form-label">Email Address</label>
              <input type="email" id="email_address" name="email_address"
                class="form-control" value="<?php echo h($email_address); ?>" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Log In</button>
          </form>

          <div class="text-center mt-3">
            <a href="forgot_password.php" class="btn btn-link">Forgot your password?</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
