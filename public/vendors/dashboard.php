<?php
require_once('../../private/initialize.php');

// Prevent unauthorized access
if (!$session->is_logged_in()) {
  redirect_to(url_for('/login.php'));
}

if (!$session->is_vendor()) {
  redirect_to(url_for('/login.php'));
}

$user_id = $session->get_user_id();
$vendor = Vendor::find_by_user_id($user_id);


$page_title = "{$vendor->business_name}'s Dashboard";
include(SHARED_PATH . '/vendor_header.php');
?>

<main role="main" id="main">
  <h1>Welcome to <?= h($vendor->business_name); ?>'s Dashboard</h1>
  <p>Here, you can manage your products, profile, and market attendance.</p>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
