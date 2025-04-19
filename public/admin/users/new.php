<?php
require_once('../../../private/initialize.php');
$page_title = 'Add Admin';
require_once(SHARED_PATH . '/include_header.php');

if (!$session->is_logged_in() || !$session->is_super_admin()) {
  redirect_to(url_for('/login.php'));
}

$user = new User();
$user->role_id = User::ADMIN;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_args = $_POST['user'] ?? [];
  $user_args['role_id'] = User::ADMIN;
  $user_args['account_status'] = 'active';
  $user = new User($user_args);

  if ($user->create()) {
    $_SESSION['message'] = "Admin successfully added and activated.";
    redirect_to('manage.php');
  } else {
    $errors = $user->errors;
  }
}
?>

<div class="container my-5">
  <h1 class="text-primary">Add Admin</h1>
  <p class="lead">Use the form below to add and activate a new admin user.</p>

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

      <div class="d-flex justify-content-between mt-4">
        <a href="manage.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Back to Admin Management
        </a>
        <button type="submit" class="btn btn-primary">Add Admin</button>
      </div>
    </div>
  </form>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
