<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  $_SESSION['message'] = "Unauthorized access.";
  redirect_to(url_for('/login.php'));
  exit;
}

// Get vendor ID from URL
$user_id = $_GET['id'] ?? '';
if (!$user_id) {
  redirect_to('manage.php');
}

// Fetch vendor & user details
$user = User::find_by_id($user_id);
$vendor = Vendor::find_by_user_id($user_id);

if (!$user || !$vendor) {
  $_SESSION['message'] = "Vendor not found.";
  redirect_to('manage.php');
}

// Fetch state name
$state_name = "Unknown";
if ($vendor->state_id) {
  $sql = "SELECT state_name FROM state WHERE state_id = ?";
  $stmt = DatabaseObject::$database->prepare($sql);
  $stmt->bind_param("i", $vendor->state_id);
  $stmt->execute();
  $stmt->bind_result($state_name);
  $stmt->fetch();
  $stmt->close();
}

$page_title = "Vendor Details: " . h($vendor->business_name);
include_header($session);
?>

<main role="main" class="container mt-4">
  <header class="mb-4">
    <h1 class="text-primary"><?php echo h($vendor->business_name); ?> - Vendor Details</h1>
  </header>

  <div class="row">
    <!-- Vendor Information -->
    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h3 class="card-title">Business Information</h3>
          <table class="table table-striped">
            <tbody>
              <tr>
                <th>Business Name:</th>
                <td><?php echo h($vendor->business_name); ?></td>
              </tr>
              <tr>
                <th>Owner Name:</th>
                <td><?php echo h($user->full_name()); ?></td>
              </tr>
              <tr>
                <th>Email:</th>
                <td><?php echo h($user->email_address); ?></td>
              </tr>
              <tr>
                <th>Phone:</th>
                <td><?php echo h($vendor->business_phone_number); ?></td>
              </tr>
              <tr>
                <th>Address:</th>
                <td>
                  <?php echo h($vendor->street_address); ?><br>
                  <?php echo h($vendor->city); ?>, <?php echo h($state_name); ?> <?php echo h($vendor->zip_code); ?>
                </td>
              </tr>
              <tr>
                <th>Description:</th>
                <td><?php echo nl2br(h($vendor->business_description)); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Business Images -->
    <div class="col-lg-4">
      <?php if (!empty($vendor->business_image_url)) { ?>
        <div class="card shadow-sm mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Business Image</h5>
            <img src="<?php echo h($vendor->business_image_url); ?>" class="img-fluid rounded shadow">
          </div>
        </div>
      <?php } ?>

      <?php if (!empty($vendor->business_logo_url)) { ?>
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Business Logo</h5>
            <img src="<?php echo h($vendor->business_logo_url); ?>" class="img-fluid rounded shadow" style="max-width: 150px;">
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <a href="manage.php" class="btn btn-outline-secondary">
    &larr; Back to Vendor Management
  </a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
