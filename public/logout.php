<?php
require_once('../private/initialize.php');

if (!$session->is_logged_in()) {
  redirect_to(url_for('/login.php'));
}

// Determine header and dashboard based on user role
if ($session->is_super_admin() || $session->is_admin()) {
  include(SHARED_PATH . '/admin_header.php');
  $dashboard_url = url_for('/admin/dashboard.php');
} elseif ($session->is_vendor()) {
  include(SHARED_PATH . '/vendor_header.php');
  $dashboard_url = url_for('/vendors/dashboard.php');
} else {
  redirect_to(url_for('/login.php'));
}

// Handle logout request
if (is_post_request()) {
  $session->logout();
  redirect_to(url_for('/index.php'));
}

$page_title = 'Log Out';
?>

<main role="main" id="main">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 text-center">
          <h1 class="mb-3">Log Out</h1>
          <p class="fw-bold"><?php echo h($session->first_name) . ", are you sure you want to log out?" ?></p>

          <form action="logout.php" method="post">
            <button type="submit" class="btn btn-danger w-100 mb-2">Yes, Log Out</button>
            <a href="<?php echo $dashboard_url; ?>" class="btn btn-outline-secondary w-100">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
