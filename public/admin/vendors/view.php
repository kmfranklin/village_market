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

<main role="main" id="main">
  <h1>Vendor Details</h1>

  <table>
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
      <th>Street Address:</th>
      <td><?php echo h($vendor->street_address); ?></td>
    </tr>
    <tr>
      <th>City:</th>
      <td><?php echo h($vendor->city); ?></td>
    </tr>
    <tr>
      <th>State:</th>
      <td><?php echo h($state_name); ?></td>
    </tr>
    <tr>
      <th>ZIP Code:</th>
      <td><?php echo h($vendor->zip_code); ?></td>
    </tr>
    <tr>
      <th>Business Description:</th>
      <td><?php echo nl2br(h($vendor->business_description)); ?></td>
    </tr>
    <?php if (!empty($vendor->business_image_url)) { ?>
      <tr>
        <th>Business Image:</th>
        <td><img src="<?php echo h($vendor->business_image_url); ?>" width="200"></td>
      </tr>
    <?php } ?>
    <?php if (!empty($vendor->business_logo_url)) { ?>
      <tr>
        <th>Business Logo:</th>
        <td><img src="<?php echo h($vendor->business_logo_url); ?>" width="150"></td>
      </tr>
    <?php } ?>
  </table>

  <br>
  <a href="manage.php">⬅ Back to Vendor Management</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
