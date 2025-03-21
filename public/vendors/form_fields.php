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
      <input type="text" name="vendor[business_name]" id="business_name" class="form-control"
        value="<?php echo h($vendor->business_name); ?>" required aria-required="true">
    </div>

    <div class="col-md-6 mb-3">
      <label for="business_email" class="form-label">
        Business Email <span class="text-danger">*</span>
      </label>
      <input type="email" name="vendor[business_email_address]" id="business_email" class="form-control"
        value="<?php echo h($vendor->business_email_address); ?>" required aria-required="true">
    </div>

    <div class="col-md-6 mb-3">
      <label for="business_phone" class="form-label">
        Business Phone Number <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[business_phone_number]" id="business_phone" class="form-control"
        value="<?php echo h($vendor->business_phone_number); ?>" required aria-required="true">
    </div>

    <div class="col-md-6 mb-3">
      <label for="street_address" class="form-label">
        Street Address <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[street_address]" id="street_address" class="form-control"
        value="<?php echo h($vendor->street_address); ?>" required aria-required="true">
    </div>

    <div class="col-md-4 mb-3">
      <label for="city" class="form-label">
        City <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[city]" id="city" class="form-control"
        value="<?php echo h($vendor->city); ?>" required aria-required="true">
    </div>

    <div class="col-md-4 mb-3">
      <label for="state" class="form-label">
        State <span class="text-danger">*</span>
      </label>
      <select name="vendor[state_id]" id="state" class="form-select" required aria-required="true">
        <option value="">Select State</option>
        <?php foreach (get_states() as $state) { ?>
          <option value="<?php echo h($state['state_id']); ?>"
            <?php if ($vendor->state_id == $state['state_id']) echo 'selected'; ?>>
            <?php echo h($state['state_name']); ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label for="zip_code" class="form-label">
        ZIP Code <span class="text-danger">*</span>
      </label>
      <input type="text" name="vendor[zip_code]" id="zip_code" class="form-control"
        value="<?php echo h($vendor->zip_code); ?>" required aria-required="true">
    </div>

    <div class="col-12 mb-3">
      <label for="business_description" class="form-label">
        Business Description
      </label>
      <textarea name="vendor[business_description]" id="business_description" class="form-control" rows="4"
        placeholder="Describe the vendor's business, products, and mission..."><?php echo h($vendor->business_description); ?></textarea>
    </div>
  </div>
</fieldset>
