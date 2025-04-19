<?php
require_once '../private/initialize.php';
$page_title = "Page Not Found";
require_once(SHARED_PATH . '/include_header.php');

echo display_session_message();
?>

<div class="container my-5">
  <h1 class="display-4">404</h1>
  <p class="lead">You've strayed from the stalls — let's head back to the market.</p>
  <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
