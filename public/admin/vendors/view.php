<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$user_id = $_GET['id'] ?? '';
if (!$user_id) {
  redirect_to('manage.php');
}

$user = User::find_by_id($user_id);
$vendor = Vendor::find_by_user_id($user_id);

if (!$user || !$vendor) {
  $_SESSION['message'] = "Vendor not found.";
  redirect_to('manage.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($user->account_status === 'pending') {
    if ($action === 'approve') {
      if ($user->approve_vendor()) {
        $_SESSION['message'] = "Vendor approved successfully!";
      } else {
        $_SESSION['message'] = "Error: Unable to approve vendor.";
      }
    } elseif ($action === 'reject') {
      if ($user->reject_vendor()) {
        $_SESSION['message'] = "Vendor rejected.";
      } else {
        $_SESSION['message'] = "Error: Unable to reject vendor.";
      }
    }
  }
  redirect_to("manage.php");
}

$page_title = "Vendor Details: " . h($vendor->business_name);
include(SHARED_PATH . '/admin_header.php');
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
      <td><?php echo h($vendor->state_id); ?></td>
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

  <?php if ($user->account_status === 'pending') { ?>
    <form action="view.php?id=<?php echo h($user->user_id); ?>" method="post">
      <button type="submit" name="action" value="approve">Approve Vendor</button>
      <button type="submit" name="action" value="reject">Reject Vendor</button>
    </form>
  <?php } else { ?>
    <p>Status: <strong><?php echo ucfirst(h($user->account_status)); ?></strong></p>
  <?php } ?>

  <br>
  <a href="manage.php">⬅ Back to Vendor Management</a>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
