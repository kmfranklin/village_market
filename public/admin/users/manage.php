<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || !$session->is_super_admin()) {
  redirect_to(url_for('/login.php'));
  exit;
}

$page_title = 'Manage Admin Users';
include_header($session);

// Fetch admin users by status
$active_admins = User::find_by_status('active', User::ADMIN);
$suspended_admins = User::find_by_status('suspended', User::ADMIN);
?>

<main role="main" class="container mt-4">
  <!-- Page Heading -->
  <header class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-primary">Manage Admins</h1>
    <a href="new.php" class="btn btn-primary" aria-label="Add New Admin">+ Add Admin</a>
  </header>

  <!-- Session Message -->
  <?php echo display_session_message(); ?>

  <!-- Tabs for Admin Management -->
  <ul class="nav nav-tabs" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">Active</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="suspended-tab" data-bs-toggle="tab" data-bs-target="#suspended" type="button" role="tab">Suspended</button>
    </li>
  </ul>

  <div class="tab-content mt-3" id="adminTabsContent">

    <!-- Active Admins Tab -->
    <div class="tab-pane fade show active" id="active" role="tabpanel">
      <h2>Active Admins</h2>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($active_admins as $admin) : ?>
              <tr>
                <td><?= h($admin->full_name()); ?></td>
                <td><?= h($admin->email_address); ?></td>
                <td><?= h($admin->phone_number); ?></td>
                <td>
                  <a href="edit.php?id=<?= h($admin->user_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>

                  <!-- Suspend Button -->
                  <button class="btn btn-danger btn-sm suspend-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#suspend-modal"
                    data-user-id="<?= h($admin->user_id); ?>"
                    data-entity-name="<?= h($admin->full_name()); ?>"
                    data-suspend-url="<?= url_for('/admin/users/suspend.php'); ?>">
                    Suspend
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Suspended Admins Tab -->
    <div class="tab-pane fade" id="suspended" role="tabpanel">
      <h2>Suspended Admins</h2>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($suspended_admins as $user): ?>
              <?php if (!$user) continue; ?>
              <tr>
                <td><?php echo h($user->full_name()); ?></td>
                <td><?php echo h($user->email_address); ?></td>
                <td><?php echo h($user->phone_number); ?></td>
                <td>
                  <button class="btn btn-success btn-sm restore-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#restore-modal"
                    data-user-id="<?= h($user->user_id); ?>"
                    data-entity-name="<?= h($user->full_name()); ?>"
                    data-restore-url="<?= url_for('/admin/users/restore.php'); ?>">
                    Restore
                  </button>
                  <button class="btn btn-danger btn-sm delete-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#delete-modal"
                    data-entity="admin"
                    data-entity-id="<?= h($user->user_id); ?>"
                    data-user-id="<?= h($user->user_id); ?>"
                    data-entity-name="<?= h($user->full_name()); ?>"
                    data-delete-url="<?= url_for('/admin/users/delete.php'); ?>">
                    Delete
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>

<!-- Shared modals -->
<?php include(SHARED_PATH . '/modals/suspend_modal.php'); ?>
<?php include(SHARED_PATH . '/modals/restore_modal.php'); ?>
<?php include(SHARED_PATH . '/modals/delete_modal.php'); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>
