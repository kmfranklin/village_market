<?php
require_once('../../private/initialize.php');

if (!$session->is_logged_in() || (!$session->is_admin() && !$session->is_super_admin())) {
  redirect_to(url_for('/login.php'));
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $market_hours = $_POST['market_hours'] ?? '';
  $contact_phone = $_POST['contact_phone'] ?? '';
  $contact_email = $_POST['contact_email'] ?? '';
  $contact_mailing_address = $_POST['contact_mailing_address'] ?? '';
  $announcement_text = $_POST['announcement_text'] ?? '';
  $hero_image_id = $_POST['hero_image_id'] ?? null;

  // Handle Hero Image Upload
  if (!empty($_FILES['hero_image_upload']['name'])) {
    $upload_result = MarketManager::upload_hero_image($_FILES['hero_image_upload']);

    if ($upload_result['success']) {
      $new_image_url = $upload_result['url'];

      $query = "INSERT INTO cms_image (image_url, uploaded_at) VALUES (?, NOW())";
      $stmt = $database->prepare($query);
      $stmt->bind_param("s", $new_image_url);

      if ($stmt->execute()) {
        $hero_image_id = $database->insert_id;
        $_SESSION['message'] = "Image uploaded successfully!";
      } else {
        $_SESSION['error'] = "Failed to save image to database.";
      }
    } else {
      $_SESSION['error'] = "Image upload failed: " . $upload_result['message'];
    }
  }

  // Update Homepage Content
  $query = "UPDATE homepage_content SET 
              market_hours = ?, 
              contact_phone = ?, 
              contact_email = ?, 
              contact_mailing_address = ?, 
              announcement_text = ?, 
              hero_image_id = ? 
            WHERE homepage_id = 1";

  $stmt = $database->prepare($query);
  $stmt->bind_param(
    "sssssi",
    $market_hours,
    $contact_phone,
    $contact_email,
    $contact_mailing_address,
    $announcement_text,
    $hero_image_id
  );

  if ($stmt->execute()) {
    $_SESSION['message'] = "Homepage content updated successfully!";
    redirect_to(url_for('/admin/manage_homepage.php'));
    exit;
  } else {
    $_SESSION['error'] = "Error updating homepage.";
  }
}

// Fetch Homepage Content
$query = "SELECT * FROM homepage_content WHERE homepage_id = 1";
$result = $database->query($query);
$homepage = $result->fetch_assoc();

// Fetch All Hero Images
$image_query = "SELECT * FROM cms_image ORDER BY uploaded_at DESC";
$image_result = $database->query($image_query);

include_header($session);
?>

<main role="main" class="container my-4">
  <header>
    <h1 class="display-5 text-primary">Manage Homepage Content</h1>
    <p class="lead">Update market information, contact details, and the hero image.</p>
  </header>

  <?php if (!empty($_SESSION['message'])) : ?>
    <div class="alert alert-success"><?php echo $_SESSION['message'];
                                      unset($_SESSION['message']); ?></div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error'])) : ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="manage_homepage.php" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
      <label for="market_hours" class="form-label">Market Hours</label>
      <input type="text" name="market_hours" id="market_hours" class="form-control"
        value="<?php echo htmlspecialchars($homepage['market_hours']); ?>">
    </div>

    <div class="mb-3">
      <label for="contact_phone" class="form-label">Contact Phone</label>
      <input type="text" name="contact_phone" id="contact_phone" class="form-control"
        value="<?php echo htmlspecialchars($homepage['contact_phone']); ?>">
    </div>

    <div class="mb-3">
      <label for="contact_email" class="form-label">Contact Email</label>
      <input type="email" name="contact_email" id="contact_email" class="form-control"
        value="<?php echo htmlspecialchars($homepage['contact_email']); ?>">
    </div>

    <div class="mb-3">
      <label for="contact_mailing_address" class="form-label">Mailing Address</label>
      <input type="text" name="contact_mailing_address" id="contact_mailing_address" class="form-control"
        value="<?php echo htmlspecialchars($homepage['contact_mailing_address']); ?>">
    </div>

    <div class="mb-3">
      <label for="announcement_text" class="form-label">Homepage Announcement (Optional)</label>
      <textarea name="announcement_text" id="announcement_text" class="form-control"><?php echo htmlspecialchars($homepage['announcement_text']); ?></textarea>
    </div>

    <div class="mb-3">
      <label for="hero_image_id" class="form-label">Select Hero Image</label>
      <div class="row">
        <?php while ($image = $image_result->fetch_assoc()) : ?>
          <div class="col-md-4">
            <label class="hero-image-option">
              <input type="radio" name="hero_image_id" value="<?php echo $image['image_id']; ?>"
                <?php if ($image['image_id'] == $homepage['hero_image_id']) echo 'checked'; ?>>
              <img src="<?php echo htmlspecialchars($image['image_url']); ?>" class="img-thumbnail">
            </label>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

    <div class="mb-3">
      <label for="hero_image_upload" class="form-label">Upload New Hero Image</label>
      <input type="file" name="hero_image_upload" id="hero_image_upload" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Homepage</button>
  </form>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
