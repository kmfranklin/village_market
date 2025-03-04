<?php
require_once('../../private/initialize.php');

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

<main role="main" class="container mt-4">

  <header>
    <h1 class="display-4 text-primary"><?= h($vendor->business_name); ?> Dashboard</h1>
    <p class="lead">Manage your products, profile, and market attendance with ease.</p>
  </header>

  <section class="row mt-4">

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="card-title h4">Manage Products</h2>
          <p class="card-text">Add, edit, or remove products from your inventory.</p>
          <a href="<?= url_for('/products/manage.php'); ?>" class="btn btn-primary" aria-label="Go to Manage Products">
            Manage Products
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="card-title h4">Edit Profile</h2>
          <p class="card-text">Update your business details and contact information.</p>
          <a href="<?= url_for('/vendors/profile.php'); ?>" class="btn btn-primary" aria-label="Edit Your Profile">
            Edit Profile
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="card-title h4">Market Attendance</h2>
          <p class="card-text">Confirm which markets you’ll be attending.</p>
          <a href="<?= url_for('/vendors/attendance.php'); ?>" class="btn btn-primary" aria-label="Manage Market Attendance">
            Manage Attendance
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
