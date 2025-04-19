<?php
require_once('../private/initialize.php');
$page_title = "Forgot Password";
require_once(SHARED_PATH . '/include_header.php');
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm p-4">
        <h1 class="mb-3 text-center">Forgot Your Password?</h1>
        <p class="text-center">Enter your email address and we'll send you a password reset link.</p>

        <?php if (isset($_SESSION['message'])): ?>
          <div class="alert alert-info text-center">
            <?php echo $_SESSION['message']; ?>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="process_forgot_password.php" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" id="email" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
