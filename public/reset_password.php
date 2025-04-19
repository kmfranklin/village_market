<?php
require_once('../private/initialize.php');
$page_title = "Reset Password";
require_once(SHARED_PATH . '/include_header.php');

$token = $_GET['token'] ?? '';

// Check if the token exists and is valid
$sql = "SELECT user_id, expires_at FROM password_reset WHERE token = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$reset_request = $result->fetch_assoc();

if (!$reset_request || strtotime($reset_request['expires_at']) < time()) {
  $_SESSION['message'] = "Invalid or expired token.";
  redirect_to("forgot_password.php");
}

$user_id = $reset_request['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_password = $_POST['new_password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Validate passwords
  if (strlen($new_password) < 12) {
    $_SESSION['message'] = "Password must be at least 12 characters.";
  } elseif ($new_password !== $confirm_password) {
    $_SESSION['message'] = "Passwords do not match.";
  } else {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the user's password
    $sql = "UPDATE user SET password_hashed = ? WHERE user_id = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $user_id);

    if ($stmt->execute()) {
      // Remove the token from password_reset table
      $sql = "DELETE FROM password_reset WHERE token = ?";
      $stmt = $database->prepare($sql);
      $stmt->bind_param("s", $token);
      $stmt->execute();

      $_SESSION['message'] = "Password reset successfully! You can now log in.";
      redirect_to("login.php");
    } else {
      $_SESSION['message'] = "Error updating password. Please try again.";
    }
  }
}
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm p-4">
        <h1 class="mb-3 text-center">Reset Password</h1>

        <?php if (isset($_SESSION['message'])): ?>
          <div class="alert alert-danger text-center">
            <?php echo h($_SESSION['message']); ?>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="reset_password.php?token=<?php echo h($token); ?>" method="POST">
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" minlength="12" required>
          </div>

          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="12" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
