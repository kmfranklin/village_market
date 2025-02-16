<?php
require_once('../../../private/initialize.php');
if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

/**
 * Processes vendor approval or rejection by an admin user.
 * 
 * This function checks the `action` and `id` parameters from the URL 
 * to determine if a vendor should be approved or rejected. If valid, 
 * the vendor's account status is updated accordingly.
 */
if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $user_id = $_GET['id'];

  $user = User::find_by_id($user_id);

  if ($user && $user->is_vendor() && $user->account_status === 'pending') {
    if ($action === 'approve') {
      $user->approve_vendor()
        ? $_SESSION['message'] = "Vendor approved successfully!"
        : $_SESSION['message'] = "Error: Unable to approve vendor.";
    } elseif ($action === 'reject') {
      $user->reject_vendor()
        ? $_SESSION['message'] = "Vendor rejected."
        : $_SESSION['message'] = "Error: Unable to reject vendor.";
    }
  }
}

$page_title = 'Manage Vendors';
include(SHARED_PATH . '/admin_header.php');

// Fetch any pending vendors
$pending_vendors = Vendor::find_vendors_by_status('pending');

// Fetch all active vendors
$active_vendors = Vendor::find_vendors_by_status('active');
?>

<main role="main" id="main">
  <h1>Manage Vendors</h1>

  <?php if (isset($_SESSION['message'])) { ?>
    <div class="alert"><?php echo h($_SESSION['message']); ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php } ?>

  <a href="new.php" class="btn">Add Vendor</a>

  <h2>Pending Vendor Approvals</h2>
  <table>
    <thead>
      <tr>
        <th>Business Name</th>
        <th>Owner</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pending_vendors as $user) {
        $vendor = Vendor::find_by_user_id($user->user_id);
        if (!$vendor) {
          continue;
        }
      ?>
        <tr>
          <td><?php echo h($vendor->business_name); ?></td>
          <td><?php echo h($user->full_name()); ?></td>
          <td><?php echo h($user->email_address); ?></td>
          <td>
            <a href="view.php?id=<?php echo h($user->user_id); ?>">View</a>
            <a href="manage.php?action=approve&id=<?php echo h($user->user_id); ?>">Approve</a>
            <a href="manage.php?action=reject&id=<?php echo h($user->user_id); ?>">Reject</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <h2>Active Vendors</h2>
  <table>
    <thead>
      <tr>
        <th>Business Name</th>
        <th>Owner</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($active_vendors as $user) {
        $vendor = Vendor::find_by_user_id($user->user_id);
        if (!$vendor) {
          continue;
        }
      ?>
        <tr>
          <td><?php echo h($vendor->business_name); ?></td>
          <td><?php echo h($user->full_name()); ?></td>
          <td><?php echo h($user->email_address); ?></td>
          <td>
            <a href="view.php?id=<?php echo h($user->user_id); ?>">View</a>
            <a href="edit.php?id=<?php echo h($user->user_id); ?>">Edit</a>
            <a href="suspend.php?id=<?php echo h($user->user_id); ?>">Suspend</a>
            <a href="delete.php?id=<?php echo h($user->user_id); ?>">Delete</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
