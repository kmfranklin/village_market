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
$rejected_vendors = Vendor::find_vendors_by_status('rejected');
?>

<main role="main" class="container mt-4">
  <!-- Page Heading -->
  <header class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-primary">Manage Vendors</h1>
    <a href="new.php" class="btn btn-primary" aria-label="Add New Vendor">+ Add Vendor</a>
  </header>

  <!-- Session Message -->
  <?php echo display_session_message(); ?>

  <!-- Tabs for Vendor Management -->
  <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">Active</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
        Pending <span class="badge bg-danger" id="pending-count"><?= count($pending_vendors); ?></span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="suspended-tab" data-bs-toggle="tab" data-bs-target="#suspended" type="button" role="tab">Suspended</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">Rejected</button>
    </li>
  </ul>

  <div class="tab-content mt-3" id="vendorTabsContent">

    <!-- Active Vendors Tab -->
    <div class="tab-pane fade show active" id="active" role="tabpanel">
      <h2>Active Vendors</h2>
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
            <?php foreach ($active_vendors as $user) : ?>
              <?php
              $vendor = Vendor::find_by_user_id($user->user_id);
              if (!$vendor) {
                echo "<tr><td colspan='4' class='text-danger'>Error: Vendor not found for User ID {$user->user_id}</td></tr>";
                continue;
              }
              ?>
              <tr>
                <td><?= h($vendor->business_name); ?></td>
                <td><?= h($user->full_name()); ?></td>
                <td><?= h($user->email_address); ?></td>
                <td>
                  <a href="view.php?id=<?= h($user->user_id); ?>" class="btn btn-outline-secondary btn-sm">View</a>
                  <a href="edit.php?id=<?= h($user->user_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                  <!-- Suspend Button -->
                  <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#suspend-modal-<?= h($vendor->vendor_id); ?>">
                    Suspend
                  </button>

                  <!-- Suspend Modal -->
                  <div class="modal fade" id="suspend-modal-<?= h($vendor->vendor_id); ?>" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Suspend Vendor</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Are you sure you want to suspend <strong><?= h($vendor->business_name); ?></strong>?
                        </div>
                        <div class="modal-footer">
                          <form action="suspend.php" method="POST">
                            <input type="hidden" name="vendor_id" value="<?= h($vendor->vendor_id); ?>">
                            <button type="submit" class="btn btn-danger">Suspend</button>
                          </form>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>

    <!-- Pending Vendors Tab -->
    <div class="tab-pane fade" id="pending" role="tabpanel">
      <h2>Pending Vendor Applications</h2>
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
            <?php foreach ($pending_vendors as $user) : ?>
              <?php
              $vendor = Vendor::find_by_user_id($user->user_id);
              if (!$vendor) {
                echo "<tr><td colspan='4' class='text-danger'>Error: Vendor not found for User ID {$user->user_id}</td></tr>";
                continue;
              }
              ?>
              <tr>
                <td><?= h($vendor->business_name); ?></td>
                <td><?= h($user->full_name()); ?></td>
                <td><?= h($user->email_address); ?></td>
                <td>
                  <a href="view.php?id=<?= h($user->user_id); ?>" class="btn btn-outline-secondary btn-sm">View</a>
                  <a href="manage.php?action=approve&id=<?= h($user->user_id); ?>" class="btn btn-primary btn-sm">Approve</a>
                  <a href="manage.php?action=reject&id=<?= h($user->user_id); ?>" class="btn btn-danger btn-sm">Reject</a>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>

    <!-- Suspended Vendors Tab -->
    <div class="tab-pane fade" id="suspended" role="tabpanel">
      <h2>Suspended Vendors</h2>
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
            <?php foreach ($suspended_vendors as $user) : ?>
              <?php
              $vendor = Vendor::find_by_user_id($user->user_id);
              if (!$vendor) {
                echo "<tr><td colspan='4' class='text-danger'>Error: Vendor not found for User ID {$user->user_id}</td></tr>";
                continue;
              }
              ?>
              <tr>
                <td><?= h($vendor->business_name); ?></td>
                <td><?= h($user->full_name()); ?></td>
                <td><?= h($user->email_address); ?></td>
                <td>
                  <!-- Restore Button -->
                  <button class="btn btn-primary btn-sm restore-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#restore-modal-user-<?= h($user->user_id); ?>">
                    Restore
                  </button>
                  <?php display_restore_modal('vendor', url_for('/admin/vendors/restore.php'), $user->user_id, $vendor->business_name); ?>

                  <!-- Delete Button -->
                  <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#delete-modal-user-<?= h($user->user_id); ?>">
                    Delete
                  </button>
                  <?php display_delete_modal('vendor', url_for('/admin/vendors/delete.php'), $vendor->vendor_id, $vendor->business_name, $user->user_id); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Rejected Vendors Tab -->
    <div class="tab-pane fade" id="rejected" role="tabpanel">
      <h2>Rejected Vendors</h2>
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
            <?php foreach ($rejected_vendors as $user) : ?>
              <?php
              $vendor = Vendor::find_by_user_id($user->user_id);
              if (!$vendor) {
                echo "<tr><td colspan='4' class='text-danger'>Error: Vendor not found for User ID {$user->user_id}</td></tr>";
                continue;
              }
              ?>
              <tr>
                <td><?= h($vendor->business_name); ?></td>
                <td><?= h($user->full_name()); ?></td>
                <td><?= h($user->email_address); ?></td>
                <td>
                  <!-- Restore Button -->
                  <button class="btn btn-primary btn-sm restore-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#restore-modal-user-<?= h($user->user_id); ?>">
                    Restore
                  </button>
                  <?php display_restore_modal('vendor', url_for('/admin/vendors/restore.php'), $user->user_id, $vendor->business_name); ?>

                  <!-- Delete Button -->
                  <button class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#delete-modal-user-<?= h($user->user_id); ?>">
                    Delete
                  </button>
                  <?php display_delete_modal('vendor', url_for('/admin/vendors/delete.php'), $vendor->vendor_id, $vendor->business_name, $user->user_id); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
