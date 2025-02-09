<?php

require_once('../private/initialize.php');

$page_title = "Forgot Password";
require_once('../private/shared/public_header.php');
?>

<main role="main" id="main">
  <h1>Forgot Your Password?</h1>
  <p>Enter your email address and we'll send you a password reset link.</p>

  <?php if (isset($_SESSION['message'])): ?>
    <p><?php echo $_SESSION['message']; ?></p>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <form action="process_forgot_password.php" method="POST">
    <label for="email">Email Address:</label>
    <input type="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>
</main>
