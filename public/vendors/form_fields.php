<?php
if (!isset($vendor)) {
  $vendor = new Vendor(); // Ensure $vendor is always set
}
?>

<fieldset class="mb-4">
  <legend class="h4 mb-3">Business Information</legend>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="business_name" class="form-label">
        Business Name <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[business_name]" id="business_name"
        class="form-control <?php if (!empty($errors['business_name'])) echo 'is-invalid'; ?>"
        value="<?php echo h($vendor->business_name); ?>" required aria-required="true">
      <?php if (!empty($errors['business_name'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['business_name']); ?></div>
      <?php endif; ?>
    </div>

    <div class="col-md-6 mb-3">
      <label for="business_email" class="form-label">
        Business Email <span class="text-danger">*</span>
      </label>
      <input type="email" name="vendor[business_email_address]" id="business_email"
        class="form-control <?php if (!empty($errors['business_email_address'])) echo 'is-invalid'; ?>"
        value="<?php echo h($vendor->business_email_address); ?>" required aria-required="true">
      <?php if (!empty($errors['business_email_address'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['business_email_address']); ?></div>
      <?php endif; ?>
    </div>

    <div class="col-md-6 mb-3">
      <label for="business_phone" class="form-label">
        Business Phone Number <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[business_phone_number]" id="business_phone"
        class="form-control <?php if (!empty($errors['business_phone_number'])) echo 'is-invalid'; ?>"
        value="<?php echo h($vendor->business_phone_number); ?>" required aria-required="true">
      <?php if (!empty($errors['business_phone_number'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['business_phone_number']); ?></div>
      <?php endif; ?>
    </div>

    <div class="col-md-6 mb-3">
      <label for="street_address" class="form-label">
        Street Address <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[street_address]" id="street_address"
        class="form-control <?php if (!empty($errors['street_address'])) echo 'is-invalid'; ?>"
        value="<?php echo h($vendor->street_address); ?>" required aria-required="true">
      <?php if (!empty($errors['street_address'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['street_address']); ?></div>
      <?php endif; ?>
    </div>

    <div class="col-md-4 mb-3">
      <label for="city" class="form-label">
        City <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[city]" id="city"
        class="form-control <?php if (!empty($errors['city'])) echo 'is-invalid'; ?>"
        value="<?php echo h($vendor->city); ?>" required aria-required="true">
      <?php if (!empty($errors['city'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['city']); ?></div>
      <?php endif; ?>
    </div>

    <div class="col-md-4 mb-3">
      <label for="state" class="form-label">
        State <span class="text-danger">*</span>
      </label>
      <select name="vendor[state_id]" id="state"
        class="form-select <?php if (!empty($errors['state_id'])) echo 'is-invalid'; ?>" required aria-required="true">
        <option value="">Select State</option>
        <?php foreach (get_states() as $state) { ?>
          <option value="<?php echo h($state['state_id']); ?>"
            <?php if ($vendor->state_id == $state['state_id']) echo 'selected'; ?>>
            <?php echo h($state['state_name']); ?>
          </option>
        <?php } ?>
      </select>
      <?php if (!empty($errors['state_id'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['state_id']); ?></div>
      <?php endif; ?>
    </div>

    <div class="col-md-4 mb-3">
      <label for="zip_code" class="form-label">
        ZIP Code <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[zip_code]" id="zip_code"
        class="form-control <?php if (!empty($errors['zip_code'])) echo 'is-invalid'; ?>"
        value="<?php echo h($vendor->zip_code); ?>" required aria-required="true">
      <?php if (!empty($errors['zip_code'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['zip_code']); ?></div>
      <?php endif; ?>
    </div>

    <!-- Business Description and Privacy Settings -->
    <div class="row align-items-start">
      <div class="col-md-8 mb-3">
        <label for="business_description" class="form-label">
          Business Description
        </label>
        <textarea name="vendor[business_description]" id="business_description"
          class="form-control <?php if (!empty($errors['business_description'])) echo 'is-invalid'; ?>"
          rows="6" placeholder="Describe your business, products, and mission..."><?php echo h($vendor->business_description); ?></textarea>
        <?php if (!empty($errors['business_description'])): ?>
          <div class="invalid-feedback"><?php echo h($errors['business_description']); ?></div>
        <?php endif; ?>
      </div>

      <div class="col-md-4 mb-3">
        <div class="card shadow-sm p-3 h-100">
          <h5 class="card-title">Privacy Settings</h5>
          <p class="text-muted small mb-3">Choose which contact info is visible to the public.</p>

          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="vendor[show_email]" id="show_email"
              value="1" <?php echo ($vendor->show_email == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="show_email">Show email address</label>
          </div>

          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="vendor[show_phone]" id="show_phone"
              value="1" <?php echo ($vendor->show_phone == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="show_phone">Show phone number</label>
          </div>

          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="vendor[show_address]" id="show_address"
              value="1" <?php echo ($vendor->show_address == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="show_address">Show physical address</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Business Logo Upload -->
    <div class="col-md-6">
      <h4 class="my-3">Business Logo</h4>
      <p class="text-muted small">
        This logo will appear beside your business name on listings and vendor directories.
        A square or circular logo works best.
      </p>
      <div class="card p-3 shadow-sm">
        <label class="form-label">Current Logo</label>
        <?php if (!empty($vendor->business_logo_url)) : ?>
          <img src="<?= h($vendor->business_logo_url); ?>" class="img-fluid rounded mb-2 profile-logo-preview" alt="Business Logo">
          <div class="d-grid gap-2">
            <button type="submit" name="delete_logo" class="btn btn-outline-danger">Remove Logo</button>
          </div>
        <?php else : ?>
          <p class="text-muted">No logo uploaded.</p>
        <?php endif; ?>
        <label class="form-label mt-3">Upload New Logo</label>
        <input type="file" name="logo" class="form-control">
      </div>
    </div>

    <!-- Business Image Upload -->
    <div class="col-md-6">
      <h4 class="my-3">Farm / Business Image</h4>
      <p class="text-muted small">
        This image will be displayed at the top of your public profile as a full-width banner.
        Choose a photo that visually represents your farm/business, products, or market booth.
      </p>
      <div class="card p-3 shadow-sm">
        <label class="form-label">Current Image</label>
        <?php if (!empty($vendor->business_image_url)) : ?>
          <img src="<?= h($vendor->business_image_url); ?>" class="img-fluid rounded mb-2 profile-image-preview" alt="Business Image">
          <div class="d-grid gap-2">
            <button type="submit" name="delete_business_image" class="btn btn-outline-danger">Remove Image</button>
          </div>
        <?php else : ?>
          <p class="text-muted">No image uploaded.</p>
        <?php endif; ?>
        <label class="form-label mt-3">Upload New Image</label>
        <input type="file" name="business_image" class="form-control">
      </div>
    </div>
</fieldset>
