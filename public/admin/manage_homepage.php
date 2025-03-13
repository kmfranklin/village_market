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
  $contact_city = $_POST['contact_city'] ?? null;
  $contact_state = $_POST['contact_state'] ?? null;
  $contact_zip = $_POST['contact_zip'] ?? null;
  $announcement_text = $_POST['announcement_text'] ?? '';
  $hero_image_id = $_POST['hero_image_id'] ?? null;

  // Handle Hero Image Upload
  if (!empty($_FILES['hero_image_upload']['name'])) {
    $upload_result = MarketManager::upload_hero_image($_FILES['hero_image_upload']);

    if ($upload_result['success']) {
      $new_image_url = $upload_result['url'];
      $alt_text = $_POST['hero_alt_text'] ?? 'Village Market hero image.'; // Default alt text if empty

      $query = "INSERT INTO cms_image (image_url, alt_text, uploaded_at) VALUES (?, ?, NOW())";
      $stmt = $database->prepare($query);
      $stmt->bind_param("ss", $new_image_url, $alt_text);

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
              contact_city = ?, 
              contact_state = ?, 
              contact_zip = ?, 
              announcement_text = ?, 
              hero_image_id = ? 
            WHERE homepage_id = 1";

  $stmt = $database->prepare($query);
  $stmt->bind_param(
    "ssssssssi",
    $market_hours,
    $contact_phone,
    $contact_email,
    $contact_mailing_address,
    $contact_city,
    $contact_state,
    $contact_zip,
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

// Fetch Hero Image from `cms_image`
$hero_image_url = "";
if (!empty($homepage['hero_image_id'])) {
  $hero_query = "SELECT image_url FROM cms_image WHERE image_id = ?";
  $stmt = $database->prepare($hero_query);
  $stmt->bind_param("i", $homepage['hero_image_id']);
  $stmt->execute();
  $hero_result = $stmt->get_result();
  if ($hero_row = $hero_result->fetch_assoc()) {
    $hero_image_url = $hero_row['image_url'];
  }
}

// Fetch All Hero Images
$image_query = "SELECT * FROM cms_image ORDER BY uploaded_at DESC";
$image_result = $database->query($image_query);

include_header($session);
?>

<main role="main" class="container my-4">
  <header>
    <h1 class="text-primary">Manage Homepage Content</h1>
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
    <div class="row">
      <!-- Form Fields -->
      <div class="col-md-6">
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
          <label for="contact_city" class="form-label">City</label>
          <input type="text" name="contact_city" id="contact_city" class="form-control"
            value="<?php echo htmlspecialchars($homepage['contact_city'] ?? ''); ?>">
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label for="contact_state" class="form-label">State</label>
            <select name="contact_state" id="contact_state" class="form-select">
              <option value="">Select State</option>
              <?php foreach (get_states() as $state) { ?>
                <option value="<?php echo h($state['state_id']); ?>"
                  <?php if ($homepage['contact_state'] == $state['state_id']) echo 'selected'; ?>>
                  <?php echo h($state['state_name']); ?>
                </option>
              <?php } ?>
            </select>
          </div>

          <div class="col-md-3">
            <label for="contact_zip" class="form-label">Zip Code</label>
            <input type="text" name="contact_zip" id="contact_zip" class="form-control"
              value="<?php echo htmlspecialchars($homepage['contact_zip'] ?? ''); ?>">
          </div>
        </div>


        <div class="mb-3">
          <label for="announcement_text" class="form-label">Homepage Announcement (Optional)</label>
          <textarea name="announcement_text" id="announcement_text" class="form-control"><?php echo htmlspecialchars($homepage['announcement_text']); ?></textarea>
        </div>
      </div>

      <!-- Image Handling -->
      <div class="col-md-6 d-flex flex-column align-items-center">
        <div class="mb-3 text-center">
          <p class="form-label fw-bold">Current Hero Image</p>
          <div class="hero-image-container">
            <img id="current-hero-image" src="<?php echo htmlspecialchars($hero_image_url); ?>"
              class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($hero_alt_text ?? 'Village Market hero image.'); ?>">
          </div>
        </div>

        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
          Choose from Gallery
        </button>

        <div class="mb-3 mt-2 w-100">
          <label for="hero_alt_text" class="form-label">Hero Image Alt Text
            <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="Alt text describes the image for users who can't see it. Keep it brief and meaningful."></i>
          </label>
          <input type="text" name="hero_alt_text" id="hero_alt_text" class="form-control"
            value="<?php echo htmlspecialchars($hero_alt_text ?? 'Village Market hero image.'); ?>">
        </div>

        <div class="mb-3 w-100">
          <label for="hero_image_upload" class="form-label">Upload New Hero Image</label>
          <input type="file" name="hero_image_upload" id="hero_image_upload" class="form-control">
        </div>
      </div>
      <input type="hidden" name="hero_image_id" id="hero_image_id" value="<?php echo htmlspecialchars($homepage['hero_image_id'] ?? ''); ?>">

      <button type="submit" class="btn btn-primary w-auto">Update Homepage</button>
  </form>

  <!-- Image Gallery Modal -->
  <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="imageGalleryModalLabel">Select a Hero Image</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <fieldset>
            <legend class="form-label">Select a Hero Image</legend>
            <div class="row">
              <?php while ($image = $image_result->fetch_assoc()) : ?>
                <div class="col-md-4">
                  <label class="hero-image-option">
                    <input type="radio" name="hero_image_select" value="<?php echo $image['image_id']; ?>"
                      data-url="<?php echo htmlspecialchars($image['image_url']); ?>"
                      data-alt="<?php echo !empty($image['alt_text']) ? htmlspecialchars($image['alt_text']) : 'Village Market hero image.'; ?>"
                      aria-label="Select hero image">
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" class="img-thumbnail"
                      alt="<?php echo !empty($image['alt_text']) ? htmlspecialchars($image['alt_text']) : 'Village Market hero image.'; ?>">
                  </label>
                </div>
              <?php endwhile; ?>
            </div>
          </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="confirmImageSelection">Confirm</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
