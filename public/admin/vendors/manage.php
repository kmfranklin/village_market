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

<main role="main" class="container mt-4">
  <!-- Page Heading -->
  <header class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-primary">Manage Vendors</h1>
    <a href="new.php" class="btn btn-primary" aria-label="Add New Vendor">+ Add Vendor</a>
  </header>

  <!-- Session Message -->
  <?php echo display_session_message(); ?>

  <!-- Vendor Sections -->
  <?php
  $sections = [
    'Pending Vendor Applications' => $pending_vendors,
    'Active Vendors' => $active_vendors,
    'Suspended Vendors' => $suspended_vendors
  ];

  foreach ($sections as $section_title => $vendors) :
    if (empty($vendors)) continue;
  ?>

    <h2 class="mt-4"><?= $section_title ?></h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered vendor-table">
        <thead class="table-dark">
          <tr>
            <th scope="col">Business Name</th>
            <th scope="col">Owner</th>
            <th scope="col">Email</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($vendors as $user) :
            $vendor = Vendor::find_by_user_id($user->user_id);
            if (!$vendor) continue;
          ?>
            <tr>
              <td><?php echo h($vendor->business_name); ?></td>
              <td><?php echo h($user->full_name()); ?></td>
              <td><?php echo h($user->email_address); ?></td>
              <td class="d-flex justify-content-evenly flex-wrap gap-2">
                <a href="view.php?id=<?= h($user->user_id); ?>" class="btn btn-outline-secondary btn-sm">View</a>

                <?php if ($section_title === 'Pending Vendor Applications') : ?>
                  <a href="manage.php?action=approve&id=<?= h($user->user_id); ?>" class="btn btn-primary btn-sm">Approve</a>
                  <a href="manage.php?action=reject&id=<?= h($user->user_id); ?>" class="btn btn-danger btn-sm">Reject</a>

                <?php elseif ($section_title === 'Active Vendors') : ?>
                  <a href="edit.php?id=<?= h($user->user_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>

                  <!-- Suspend Button -->
                  <button class="btn btn-outline-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#suspend-modal-vendor-<?= h($vendor->vendor_id); ?>">
                    Suspend
                  </button>
                  <?php display_suspend_modal('vendor', url_for('/admin/vendors/suspend.php'), $vendor->vendor_id, $user->user_id, $vendor->business_name); ?>

                <?php elseif ($section_title === 'Suspended Vendors') : ?>
                  <!-- Restore Button -->
                  <button class="btn btn-primary btn-sm restore-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#restore-modal-user-<?= h($user->user_id); ?>"
                    data-user-id="<?= h($user->user_id); ?>"
                    data-entity-name="<?= h($vendor->business_name); ?>">
                    Restore
                  </button>

                  <?php display_restore_modal('vendor', url_for('/admin/vendors/restore.php'), $user->user_id, $vendor->business_name); ?>

                <?php endif; ?>

                <!-- Delete Button -->
                <button class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#delete-modal-vendor-<?= h($vendor->vendor_id); ?>">
                  Delete
                </button>
                <?php display_delete_modal('vendor', url_for('/admin/vendors/delete.php'), $vendor->vendor_id, $vendor->business_name); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
