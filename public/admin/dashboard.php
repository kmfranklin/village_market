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
include_header($session);
?>

<main role="main" class="container mt-4 dashboard">
  <header class="text-center">
    <h1 class="display-4 text-primary"><?= h($session->first_name); ?>'s Admin Dashboard</h1>
    <p class="lead">Manage vendors, products, and market details efficiently.</p>
  </header>

  <section class="row mt-4 justify-content-center">

    <div class="col-md-4">
      <div class="card h-100 d-flex flex-column shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
          <h2 class="card-title h4">Vendor Management</h2>
          <p class="card-text">Manage vendor registrations and approvals, updates, and account status.</p>
          <a href="<?= url_for('/admin/vendors/manage.php'); ?>" class="btn btn-primary mt-auto align-self-center" aria-label="View Vendors">
            View Vendors
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 d-flex flex-column shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-box-seam display-4 text-primary mb-3"></i>
          <h2 class="card-title h4">Manage Products</h2>
          <p class="card-text">Monitor and update listed products.</p>
          <a href="<?= url_for('/products/manage.php'); ?>" class="btn btn-primary mt-auto align-self-center" aria-label="View Products">
            View Products
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 d-flex flex-column shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-pencil-square display-4 text-primary mb-3"></i>
          <h2 class="card-title h4">Update Market Info</h2>
          <p class="card-text">Edit homepage content and hours.</p>
          <a href="<?= url_for('admin/manage_homepage.php'); ?>" class="btn btn-primary mt-auto align-self-center" aria-label="Edit Homepage">
            Edit Homepage
          </a>
        </div>
      </div>
    </div>

  </section>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
