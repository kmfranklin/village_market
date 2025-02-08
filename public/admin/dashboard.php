<?php
require_once('../../private/initialize.php');

// Prevent unauthorized access
if (!$session->is_logged_in()) {
  redirect_to(url_for('/login.php'));
}

// Redirect non-admin users
if (!$session->is_admin() && !$session->is_super_admin()) {
  redirect_to(url_for('/index.php'));
}

// Page content for admins and super admins
$page_title = "{$session->first_name}'s Dashboard";
include(SHARED_PATH . '/admin_header.php');
?>

<main role="main" id="main">
  <h1><?= h($session->first_name); ?>'s Dashboard</h1>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
