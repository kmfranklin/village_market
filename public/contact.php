<?php
require_once '../private/initialize.php';
require_once '../private/controllers/contact_controller.php';

$page_title = "Contact Us";
require_once(SHARED_PATH . '/include_header.php');
?>

<div class="container my-5">
  <h1>Contact Us</h1>
  <p class="lead">Use the form below to contact an administrator. We will assist you as soon as we can!</p>

  <?php echo display_session_message(); ?>
  <?php echo display_errors($errors); ?>

  <form action="contact.php" method="post" novalidate>
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo h($contact['name']); ?>" required>
        <?php if (in_array("Name is required.", $errors)) : ?>
          <div class="text-danger small">Name is required.</div>
        <?php endif; ?>
      </div>

      <div class="col-md-6">
        <label for="business_name" class="form-label">Business Name <small class="text-muted">(optional)</small></label>
        <input type="text" name="business_name" id="business_name" class="form-control" value="<?php echo h($contact['business_name']); ?>">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo h($contact['email']); ?>" required>
        <?php if (in_array("A valid email is required.", $errors)) : ?>
          <div class="text-danger small">A valid email is required.</div>
        <?php endif; ?>
      </div>

      <div class="col-md-6">
        <label for="reason" class="form-label">Reason for Contact <span class="text-danger">*</span></label>
        <select name="reason" id="reason" class="form-select" required>
          <option value="" disabled <?php echo $contact['reason'] === '' ? 'selected' : ''; ?>>-- Select a reason --</option>
          <option value="General Question" <?php echo $contact['reason'] === 'General Question' ? 'selected' : ''; ?>>General Question</option>
          <option value="Vendor Registration Inquiry" <?php echo $contact['reason'] === 'Vendor Registration Inquiry' ? 'selected' : ''; ?>>Vendor Registration Inquiry</option>
          <option value="Attendance or Schedule Issue" <?php echo $contact['reason'] === 'Attendance or Schedule Issue' ? 'selected' : ''; ?>>Attendance or Schedule Issue</option>
          <option value="Report a Bug" <?php echo $contact['reason'] === 'Report a Bug' ? 'selected' : ''; ?>>Report a Bug</option>
          <option value="Other" <?php echo $contact['reason'] === 'Other' ? 'selected' : ''; ?>>Other</option>
        </select>
        <?php if (in_array("Please select a reason for your message.", $errors)) : ?>
          <div class="text-danger small">Please select a reason.</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
      <textarea name="message" id="message" rows="6" class="form-control" required><?php echo h($contact['message']); ?></textarea>
      <?php if (in_array("Message is required.", $errors)) : ?>
        <div class="text-danger small">Message is required.</div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <div class="g-recaptcha" data-sitekey="<?php echo $_ENV['RECAPTCHA_SITE_KEY']; ?>"></div>
      <?php if (in_array("Please complete the CAPTCHA challenge.", $errors) || in_array("CAPTCHA verification failed. Please try again.", $errors)) : ?>
        <div class="text-danger small mt-1">
          <?php
          foreach ($errors as $error) {
            if (str_contains($error, 'CAPTCHA')) {
              echo h($error);
              break;
            }
          }
          ?>
        </div>
      <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Send Message</button>
  </form>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
