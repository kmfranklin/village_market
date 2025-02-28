<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

// Process approval or rejection
if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $user_id = $_GET['id'];

  $user = User::find_by_id($user_id);

  if ($user && $user->is_vendor() && $user->account_status === 'pending') {
    if ($action === 'approve') {
      $_SESSION['message'] = $user->approve_vendor() ? "Vendor approved successfully!" : "Error: Unable to approve vendor.";
    } elseif ($action === 'reject') {
      $_SESSION['message'] = $user->reject_vendor() ? "Vendor rejected." : "Error: Unable to reject vendor.";
    }
  }
}

$page_title = 'Manage Vendors';
include_header($session);

// Fetch vendors
$pending_vendors = Vendor::find_vendors_by_status('pending');
$active_vendors = Vendor::find_vendors_by_status('active');
$suspended_vendors = Vendor::find_vendors_by_status('suspended');
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
        if (!$vendor) continue;
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
        if (!$vendor) continue;
      ?>
        <tr>
          <td><?php echo h($vendor->business_name); ?></td>
          <td><?php echo h($user->full_name()); ?></td>
          <td><?php echo h($user->email_address); ?></td>
          <td>
            <a href="view.php?id=<?php echo h($user->user_id); ?>">View</a>
            <a href="edit.php?id=<?php echo h($user->user_id); ?>">Edit</a>
            <a href="#"
              class="suspend-btn btn warning"
              data-vendor-id="<?php echo h($vendor->vendor_id); ?>"
              data-user-id="<?php echo h($user->user_id); ?>"
              data-entity-name="<?php echo h($vendor->business_name); ?>"
              data-suspend-url="<?php echo url_for('/admin/vendors/suspend.php'); ?>">
              Suspend
            </a>
            <?php display_suspend_modal('vendor', url_for('/admin/vendors/suspend.php'), $vendor->vendor_id, $user->user_id, $vendor->business_name); ?>

            <a href="#"
              class="delete-btn btn danger"
              data-vendor-id="<?php echo h($vendor->vendor_id); ?>"
              data-user-id="<?php echo h($user->user_id); ?>"
              data-entity-name="<?php echo h($vendor->business_name); ?>"
              data-delete-url="<?php echo url_for('/admin/vendors/delete.php'); ?>">
              Delete
            </a>
            <?php display_delete_modal('vendor', url_for('/admin/vendors/delete.php'), $vendor->vendor_id, $user->user_id, $vendor->business_name); ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <h2>Suspended Vendors</h2>
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
      <?php foreach ($suspended_vendors as $user) {
        $vendor = Vendor::find_by_user_id($user->user_id);
        if (!$vendor) continue;
      ?>
        <tr>
          <td><?php echo h($vendor->business_name); ?></td>
          <td><?php echo h($user->full_name()); ?></td>
          <td><?php echo h($user->email_address); ?></td>
          <td>
            <a href="view.php?id=<?php echo h($user->user_id); ?>">View</a>
            <a href="#"
              class="restore-btn btn success"
              data-user-id="<?php echo h($user->user_id); ?>"
              data-entity-name="<?php echo h($vendor->business_name); ?>">
              Restore
            </a>

            <?php display_restore_modal('vendor', url_for('/admin/vendors/restore.php'), $user->user_id, $vendor->business_name); ?>

            <a href="#"
              class="delete-btn btn danger"
              data-vendor-id="<?php echo h($vendor->vendor_id); ?>"
              data-user-id="<?php echo h($user->user_id); ?>"
              data-entity-name="<?php echo h($vendor->business_name); ?>"
              data-delete-url="<?php echo url_for('/admin/vendors/delete.php'); ?>">
              Delete
            </a>
            <?php display_delete_modal('vendor', url_for('/admin/vendors/delete.php'), $vendor->vendor_id, $user->user_id, $vendor->business_name); ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
