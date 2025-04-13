<?php

require_once '../private/initialize.php';
$page_title = "Page Not Found";
include_header($session, $page_title);

echo display_session_message();
?>

<main class="container my-5 text-center page-404">
  <h1 class="display-4">404</h1>
  <p class="lead">You've strayed from the stalls — let's head back to the market.</p>
  <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
