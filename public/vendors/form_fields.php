<?php
if (!isset($vendor)) {
  $vendor = new Vendor(); // Ensure $vendor is always set
}
?>

<fieldset>
  <legend>Business Information</legend>
  <dl>
    <dt><label for="business_name">Business Name</label></dt>
    <dd><input type="text" name="vendor[business_name]" id="business_name" value="<?php echo h($vendor->business_name); ?>" required /></dd>

    <dt><label for="business_email">Business Email</label></dt>
    <dd><input type="email" name="vendor[business_email_address]" id="business_email" value="<?php echo h($vendor->business_email_address); ?>" required /></dd>

    <dt><label for="business_phone">Business Phone Number</label></dt>
    <dd><input type="text" name="vendor[business_phone_number]" id="business_phone" value="<?php echo h($vendor->business_phone_number); ?>" required /></dd>

    <dt><label for="street_address">Street Address</label></dt>
    <dd><input type="text" name="vendor[street_address]" id="street_address" value="<?php echo h($vendor->street_address); ?>" required /></dd>

    <dt><label for="city">City</label></dt>
    <dd><input type="text" name="vendor[city]" id="city" value="<?php echo h($vendor->city); ?>" required /></dd>

    <dt><label for="state">State</label></dt>
    <dd>
      <select name="vendor[state_id]" id="state">
        <option value="">Select State</option>
        <?php foreach ($states as $state) { ?>
          <option value="<?php echo h($state['state_id']); ?>" <?php if ($vendor->state_id == $state['state_id']) echo 'selected'; ?>>
            <?php echo h($state['state_name']); ?>
          </option>
        <?php } ?>
      </select>
    </dd>

    <dt><label for="zip_code">ZIP Code</label></dt>
    <dd><input type="text" name="vendor[zip_code]" id="zip_code" value="<?php echo h($vendor->zip_code); ?>" required /></dd>
  </dl>
</fieldset>
