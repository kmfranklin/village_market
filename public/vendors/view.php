<?php
require_once('../../private/initialize.php');

$page_title = 'Vendor Profile';
require_once(SHARED_PATH . '/include_header.php');

// Get vendor ID from URL
$user_id = $_GET['id'] ?? '';
if (!$user_id || !is_numeric($user_id)) {
  redirect_to(url_for('/vendors.php'));
}

// Fetch vendor & user details
$user = User::find_by_id($user_id);
$vendor = Vendor::find_by_user_id($user_id);

// Check if the current viewer is the vendor or an admin
$current_vendor = Vendor::find_by_user_id($session->get_user_id());

$is_admin = $session->is_admin() || $session->is_super_admin();
$is_owner = $session->is_vendor() && $current_vendor && $current_vendor->vendor_id == $vendor->vendor_id;
$can_edit = $is_admin || $is_owner;

// Fetch state name
$state_name = "Unknown";
if ($vendor->state_id) {
  $sql = "SELECT state_name FROM state WHERE state_id = ?";
  $stmt = DatabaseObject::$database->prepare($sql);
  $stmt->bind_param("i", $vendor->state_id);
  $stmt->execute();
  $stmt->bind_result($state_name);
  $stmt->fetch();
  $stmt->close();
}

?>
<section id="hero">
  <img src="<?= h(!empty($vendor->business_image_url) ? $vendor->business_image_url : get_homepage_hero_image_url()); ?>" alt="Vendor Hero Image">
  <div class="hero-content">
    <h1 class="hero-heading"><?= h($vendor->business_name); ?></h1>
    <p class="hero-subheading"><?= h($vendor->city); ?>, <?= h($state_name); ?></p>
    <?php if (!empty($vendor->business_email_address) && $vendor->show_email == 1) : ?>
      <div class="hero-buttons">
        <a href="mailto:<?= h($vendor->business_email_address); ?>" class="hero-btn">Contact Vendor</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php if ($can_edit): ?>
  <div class="text-center my-4">
    <a href="<?= url_for('/vendors/profile.php?id=' . h($user->user_id)); ?>" class="btn btn-primary">
      Edit Profile
    </a>
  </div>
<?php endif; ?>

<div class="container my-5">
  <h1 class="text-primary mb-4"><?php echo h($vendor->business_name); ?> - Vendor Details</h1>

  <div class="row">
    <!-- Vendor Information -->
    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h3 class="card-title">Business Information</h3>
          <table class="table table-striped">
            <tbody>
              <tr>
                <th>Business Name:</th>
                <td><?php echo h($vendor->business_name); ?></td>
              </tr>

              <tr>
                <th>Owner Name:</th>
                <td><?php echo h($user->full_name()); ?></td>
              </tr>

              <?php if ($is_admin || $is_owner || $vendor->show_email): ?>
                <tr>
                  <th>Email:</th>
                  <td><?php echo h($user->email_address); ?></td>
                </tr>
              <?php endif; ?>

              <?php if ($is_admin || $is_owner || $vendor->show_phone): ?>
                <tr>
                  <th>Phone:</th>
                  <td><?php echo Vendor::format_phone($vendor->business_phone_number); ?></td>
                </tr>
              <?php endif; ?>

              <?php if ($is_admin || $is_owner || $vendor->show_address): ?>
                <tr>
                  <th>Address:</th>
                  <td>
                    <?php echo h($vendor->street_address); ?><br>
                    <?php echo h($vendor->city); ?>, <?php echo h($state_name); ?> <?php echo h($vendor->zip_code); ?>
                  </td>
                </tr>
              <?php endif; ?>

              <tr>
                <th>Description:</th>
                <td><?php echo nl2br(h($vendor->business_description)); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Business Images -->
    <div class="col-lg-4">
      <?php if (!empty($vendor->business_logo_url)) { ?>
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <img src="<?php echo h($vendor->business_logo_url); ?>" class="img-fluid rounded shadow" style="max-width: 150px;">
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <?php if ($session->is_admin() || $session->is_super_admin()) : ?>
    <a href="<?= url_for('/admin/vendors/manage.php'); ?>" class="btn btn-outline-secondary">
      &larr; Back to Vendor Management
    </a>
  <?php else : ?>
    <a href="<?= url_for('/vendors.php'); ?>" class="btn btn-outline-secondary">
      &larr; Back to Vendors
    </a>
  <?php endif; ?>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
