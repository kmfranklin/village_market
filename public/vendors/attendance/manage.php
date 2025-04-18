<?php require_once('../../../private/initialize.php');

// Redirect if not logged in or not a vendor or admin
if (
  !$session->is_logged_in() ||
  (!$session->is_vendor() && !$session->is_admin() && !$session->is_super_admin())
) {
  redirect_to(url_for('/login.php'));
}

// Resolve vendor ID
if ($session->is_vendor()) {
  $user_id = $session->get_user_id();
  $vendor = Vendor::find_by_user_id($user_id);
  $vendor_id = $vendor ? $vendor->vendor_id : null;
} elseif ($session->is_admin() || $session->is_super_admin()) {
  $vendor_id = $_GET['vendor_id'] ?? null;
  $vendor = $vendor_id ? Vendor::find_by_id($vendor_id) : null;
}

/** @var Vendor $vendor */

if (!isset($vendor) || !$vendor) {
  $session->message("Vendor not found.");
  redirect_to(url_for('/index.php'));
}

// Handle form submission
if (is_post_request()) {
  $submitted_dates = $_POST['market_dates'] ?? [];
  $submitted_dates = array_map('intval', $submitted_dates);

  MarketAttendance::delete_all_for_vendor($vendor->vendor_id);

  foreach ($submitted_dates as $market_date_id) {
    $attendance = new MarketAttendance();
    $attendance->vendor_id = $vendor->vendor_id;
    $attendance->market_date_id = $market_date_id;
    $attendance->is_confirmed = 1;
    $attendance->save();
  }

  $_SESSION['message'] = "Attendance updated successfully.";
  redirect_to(url_for(
    '/vendors/attendance/manage.php' .
      ($session->is_admin() || $session->is_super_admin() ? '?vendor_id=' . $vendor->vendor_id : '')
  ));
}

$upcoming_dates = MarketDate::upcoming();
$selected_dates = MarketAttendance::find_by_vendor($vendor->vendor_id);

$page_title = 'Manage Attendance';
include(SHARED_PATH . '/vendor_header.php');
?>

<main class="container my-4">
  <h1 class="mb-3">Manage Attendance for <?php echo h($vendor->business_name); ?></h1>
  <p>Select the market dates this vendor plans to attend.</p>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
      <?php echo h($_SESSION['message']); ?>
      <?php unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <div class="row">
    <!-- Attendance Form -->
    <div class="col-lg-8">
      <form action="<?php echo url_for('/vendors/attendance/manage.php' . ($session->is_admin() || $session->is_super_admin() ? '?vendor_id=' . h($vendor->vendor_id) : '')); ?>" method="post">
        <?php include('form_fields.php'); ?>

        <button type="submit" class="btn btn-primary mt-3">Save Attendance</button>
        <?php
        $return_url = $session->is_admin() || $session->is_super_admin()
          ? url_for('/admin/vendors/manage.php')
          : url_for('/vendors/dashboard.php');
        ?>
        <a href="<?php echo $return_url; ?>" class="btn btn-secondary mt-3 ms-2">Return to Dashboard</a>
      </form>
    </div>

    <!-- Calendar Sidebar -->
    <div class="col-lg-4 mt-4 mt-lg-0 d-flex justify-content-center align-items-start">
      <div id="market-calendar" class="w-100 flatpickr-input" readonly></div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
