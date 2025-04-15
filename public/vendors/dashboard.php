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

$selected_market_date_ids = MarketAttendance::find_by_vendor($vendor->vendor_id);
$calendar_dates = [];

foreach ($selected_market_date_ids as $id) {
  $market_date = MarketDate::find_by_id($id);
  if ($market_date && !empty($market_date->market_date)) {
    /** @var MarketDate|null $market_date */
    $calendar_dates[] = $market_date->market_date;
  }
}

$page_title = "{$vendor->business_name}'s Dashboard";
include(SHARED_PATH . '/vendor_header.php');

if ($session->message()) : ?>
  <div class="d-flex justify-content-center">
    <div class="alert alert-success alert-dismissible fade show alert-centered" role="alert">
      <?php echo h($session->message()); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<main role="main" class="container mt-4">

  <!-- Welcome Panel -->
  <div class="mb-4 p-4 bg-white rounded shadow-sm">
    <h2 class="h4 mb-2">Welcome back, <?= h($vendor->business_name); ?>!</h2>
    <p class="mb-0 text-muted">Use your dashboard to manage your products, update your profile, and set your market schedule.</p>
  </div>

  <!-- Quick Action Cards -->
  <section class="row mt-4 mb-5">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="card-title">Manage Products</h2>
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
          <h2 class="card-title">Edit Profile</h2>
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
          <h2 class="card-title">Market Attendance</h2>
          <p class="card-text">Confirm which markets you'll be attending.</p>
          <a href="<?= url_for('/vendors/attendance/manage.php'); ?>" class="btn btn-primary" aria-label="Manage Market Attendance">
            Manage Attendance
          </a>
        </div>
      </div>
    </div>
  </section>

  <div class="row mt-5">
    <!-- Left: FAQ + Tips -->
    <div class="col-md-7">
      <!-- FAQ Callout -->
      <div class="p-4 bg-white rounded shadow-sm mb-4">
        <h3 class="h5 mb-2">Need Help?</h3>
        <p class="mb-3">
          Check out the <a href="<?= url_for('/vendors/faq.php'); ?>">Vendor FAQ</a> for help with product listings, market requirements, and more.
        </p>
        <a href="<?= url_for('/vendors/faq.php'); ?>" class="btn btn-outline-primary btn-sm">View Vendor FAQs</a>
      </div>

      <!-- Tips Block -->
      <div class="p-4 bg-white rounded shadow-sm">
        <h3 class="h5 mb-3">Tips for Today</h3>
        <ul class="mb-0">
          <li>Confirm your availability for next month before the end of the week.</li>
          <li>Upload high-quality product images to improve visibility.</li>
          <li>Double-check your contact details — they appear on your public profile.</li>
        </ul>
      </div>
    </div>

    <!-- Right: Calendar -->
    <div class="col-md-5 mt-4 mt-md-0">
      <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="text-center d-flex flex-column">
          <h3 class="h5 mb-3">Your Upcoming Market Dates</h3>
          <div id="vendor-calendar" class="flatpickr-calendar-wrapper"></div>
        </div>
      </div>
    </div>
  </div>

</main>

<script type="application/json" id="vendor-calendar-dates">
  <?= json_encode($calendar_dates); ?>
</script>

<?php include(SHARED_PATH . '/footer.php'); ?>
