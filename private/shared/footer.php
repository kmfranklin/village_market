<?php
$address_data = get_site_address();

$contact_mailing_address = htmlspecialchars($address_data['contact_mailing_address'] ?? '');
$contact_city = htmlspecialchars($address_data['contact_city'] ?? '');
$contact_state = htmlspecialchars($address_data['contact_state'] ?? '');
$contact_zip = htmlspecialchars($address_data['contact_zip'] ?? '');
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<footer class="bg-light py-4 mt-auto">
  <div class="container">
    <div class="row">

      <div class="col-md-4 text-start">
        <p class="fw-bold">Village Market</p>
        <p class="mb-0"><?php echo $contact_mailing_address; ?></p>
        <p class="mb-0"><?php echo $contact_city . ', ' . $contact_state . ' ' . $contact_zip; ?></p>
        <p class="mb-0">Open Saturdays 9 AM - 6 PM</p>
      </div>

      <div class="col-md-4 text-center">
        <ul class="list-unstyled">
          <li><a href="<?= url_for('/index.php'); ?>">Home</a></li>
          <li><a href="<?= url_for('/browse-vendors.php'); ?>">Browse Vendors</a></li>
          <li><a href="<?= url_for('/browse-products.php'); ?>">Browse Products</a></li>
          <li><a href="<?= url_for('/contact.php'); ?>">Contact Us</a></li>
        </ul>
      </div>

      <div class="col-md-4 text-end">
        <a href="https://github.com/kmfranklin" target="_blank" class="social-icon mx-2" aria-label="GitHub link">
          <i class="bi bi-github"></i>
        </a>
        <a href="https://www.linkedin.com/in/kevin-franklin/" target="_blank" class="social-icon mx-2" aria-label="LinkedIn link">
          <i class="bi bi-linkedin"></i>
        </a>
        <p class="mt-2 mb-0 text-muted">&copy; <?= date('Y'); ?> Village Market</p>
        <p class="mb-0 small">
          <a href="<?= url_for('/tos.php'); ?>">Terms of Service</a> |
          <a href="<?= url_for('/privacy.php'); ?>">Privacy Policy</a>
        </p>
      </div>
    </div>
  </div>
</footer>
</body>
