<?php
if (!isset($vendor)) {
  $vendor = new Vendor(); // Ensure $vendor is always set
}
?>

<fieldset>
  <legend>Business Information</legend>
  <dl>
    <dt><label for="business_name">Business Name</label></dt>
    <dd><input type="text" name="business_name" id="business_name" value="<?php echo h($vendor->business_name); ?>" required /></dd>

    <dt><label for="business_description">Business Description</label></dt>
    <dd><textarea name="business_description" id="business_description" required><?php echo h($vendor->business_description); ?></textarea></dd>

    <dt><label for="street_address">Street Address</label></dt>
    <dd><input type="text" name="street_address" id="street_address" value="<?php echo h($vendor->street_address); ?>" required /></dd>

    <dt><label for="city">City</label></dt>
    <dd><input type="text" name="city" id="city" value="<?php echo h($vendor->city); ?>" required /></dd>

    <dt><label for="state_id">State</label></dt>
    <dd>
      <select name="state_id" id="state_id" required>
        <option value="">Select a State</option>
        <?php foreach ($states as $state): ?>
          <option value="<?php echo (int)$state['state_id']; ?>"
            <?php if (($vendor->state_id ?? '') == $state['state_id']) echo 'selected'; ?>>
            <?php echo h($state['state_name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </dd>


    <dt><label for="zip_code">ZIP Code</label></dt>
    <dd><input type="text" name="zip_code" id="zip_code" value="<?php echo h($vendor->zip_code); ?>" required /></dd>

    <dt><label for="business_phone_number">Business Phone Number</label></dt>
    <dd><input type="tel" name="business_phone_number" id="business_phone_number" value="<?php echo h($vendor->business_phone_number); ?>" required /></dd>

    <dt><label for="business_email_address">Business Email Address</label></dt>
    <dd><input type="email" name="business_email_address" id="business_email_address" value="<?php echo h($vendor->business_email_address); ?>" required /></dd>
  </dl>
</fieldset>
