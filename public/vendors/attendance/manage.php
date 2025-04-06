<?php require_once('../../../private/initialize.php');

// Redirect if not logged in or not a vendor
if (!$session->is_logged_in()) {
  redirect_to(url_for('/login.php'));
}

if (!$session->is_vendor()) {
  redirect_to(url_for('/login.php'));
}

$vendor = Vendor::find_by_user_id($session->user_id);
if (!$vendor) {
  redirect_to(url_for('/vendor/index.php'));
}

if (is_post_request()) {
  $submitted_dates = $_POST['market_dates'] ?? [];
  $submitted_dates = array_map('intval', $submitted_dates);

  MarketAttendance::delete_all_for_vendor($vendor->vendor_id);

  foreach ($submitted_dates as $market_date_id) {
    $attendance = new MarketAttendance();
    $attendance->vendor_id = $vendor->vendor_id;
    $attendance->market_date_id = $market_date_id;
    $attendance->is_confirmed = 1;
    $attendance->attendance_id = null;
    $attendance->save();
  }

  $_SESSION['message'] = "Attendance updated successfully.";
  redirect_to(url_for('/vendors/attendance/manage.php'));
}

$upcoming_dates = MarketDate::upcoming();
$selected_dates = MarketAttendance::find_by_vendor($vendor->vendor_id);

$page_title = 'Manage Attendance';
include(SHARED_PATH . '/vendor_header.php');
?>

<main class="container my-4">
  <h1 class="mb-3">Manage Your Attendance</h1>
  <p>Select the market dates you plan to attend.</p>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
      <?php echo h($_SESSION['message']); ?>
      <?php unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <div class="row">
    <!-- Attendance Form (Left Column) -->
    <div class="col-lg-8">
      <form action="<?php echo url_for('/vendors/attendance/manage.php'); ?>" method="post">
        <?php include('form_fields.php'); ?>

        <button type="submit" class="btn btn-primary mt-3">Save Attendance</button>
        <a href="<?php echo url_for('/vendors/dashboard.php'); ?>" class="btn btn-secondary mt-3 ms-2">Return to Dashboard</a>
      </form>
    </div>

    <!-- Calendar Sidebar (Right Column) -->
    <div class="col-lg-4 mt-4 mt-lg-0 d-flex justify-content-center align-items-start">
      <div id="market-calendar" class="w-100 flatpickr-input" readonly></div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
