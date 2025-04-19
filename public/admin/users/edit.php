<?php
require_once '../../../private/initialize.php';
require_login();
$page_title = 'Edit Profile';
require_once(SHARED_PATH . '/include_header.php');

$id = $_GET['id'] ?? null;

// If no ID provided, or the user isn't editing their own profile and isn't an admin, redirect
if (!$id || (!$session->is_admin() && $session->user_id != $id)) {
  redirect_to(url_for('/index.php'));
}

$user = User::find_by_id($id);
if (!$user) {
  redirect_to(url_for('/index.php'));
}

$errors = [];

if (is_post_request()) {
  $args = $_POST['user'] ?? [];

  $user->merge_attributes($args);

  // Only update password if a new one was entered
  if (!is_blank($args['password'])) {
    $user->password = $args['password'];
    $user->confirm_password = $args['confirm_password'];
  }

  if ($user->save()) {
    // Refresh session name if the current user was updated
    if ($session->user_id == $user->user_id) {
      $_SESSION['first_name'] = $user->first_name;
      $_SESSION['last_name'] = $user->last_name;
    }

    $_SESSION['message'] = 'User profile updated successfully.';
    redirect_to(url_for('/admin/dashboard.php'));
  }
}
?>

<div class="container my-5">
  <h1>Edit Your Profile</h1>

  <?php echo display_errors($errors); ?>

  <form action="<?php echo h($_SERVER['PHP_SELF']) . '?id=' . h(u($user->user_id)); ?>" method="post" novalidate>
    <?php include_once 'form_fields.php'; ?>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
  </form>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
