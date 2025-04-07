<?php
require_once('../../../private/initialize.php');

if (!$session->is_logged_in() || !$session->is_super_admin()) {
  redirect_to(url_for('/login.php'));
}

$page_title = 'Add Admin';
include_header($session);

$user = new User();
$user->role_id = User::ADMIN; // force role_id

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_args = $_POST['user'] ?? [];
  $user_args['role_id'] = User::ADMIN;
  $user_args['account_status'] = 'active'; // no approval flow for admins
  $user = new User($user_args);

  if ($user->create()) {
    $_SESSION['message'] = "Admin successfully added and activated.";
    redirect_to('manage.php');
  } else {
    $errors = $user->errors;
  }
}
?>

<main role="main" class="container mt-4">
  <header class="mb-4">
    <h1 class="text-primary">Add Admin</h1>
    <p class="lead">Use the form below to add and activate a new admin user.</p>
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

      <div class="d-flex justify-content-between mt-4">
        <a href="manage.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Back to Admin Management
        </a>
        <button type="submit" class="btn btn-primary">Add Admin</button>
      </div>
    </div>
  </form>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
