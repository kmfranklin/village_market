<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

$errors = [];
$vendor = new Vendor();
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_args = $_POST['user'];
  $user_args['role_id'] = User::VENDOR;
  $user_args['account_status'] = 'active';
  $user = new User($user_args);

  if ($user->create()) {
    $vendor_args = $_POST['vendor'];
    $vendor_args['user_id'] = $user->user_id;
    $vendor = new Vendor($vendor_args);

    if ($vendor->create()) {
      $_SESSION['message'] = "Vendor successfully added and activated.";
      redirect_to('manage.php');
    } else {
      $errors = $vendor->errors;
    }
  } else {
    $errors = $user->errors;
  }
}

$page_title = 'Add Vendor';
include_header($session);
?>

<main role="main" class="container mt-4">
  <header class="mb-4">
    <h1 class="display-5 text-primary">Add Vendor</h1>
    <p class="lead">Use the form below to add and activate a new vendor.</p>
  </header>

  <?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
      <strong>Error:</strong> Please fix the following issues:
      <ul>
        <?php foreach ($errors as $error) : ?>
          <li><?php echo h($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="new.php" method="post" class="needs-validation" novalidate>
    <div class="card shadow-sm p-4">
      <?php include('../users/form_fields.php'); ?>

      <?php include('../../vendors/form_fields.php'); ?>

      <div class="d-flex justify-content-between mt-4">
        <a href="manage.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Back to Vendor Management
        </a>
        <button type="submit" class="btn btn-primary">Add Vendor</button>
      </div>
    </div>
  </form>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
