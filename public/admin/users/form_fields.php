<?php
if (!isset($user)) {
  $user = new User();
}
?>

<fieldset>
  <legend class="h4 mb-3">User Information</legend>

  <div class="row">
    <!-- First Name -->
    <div class="col-md-6 mb-3">
      <label for="first_name" class="form-label">
        First Name <span class="text-danger">*</span>
      </label>
      <input type="text" name="user[first_name]" id="first_name"
        class="form-control <?php if (!empty($errors['first_name'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->first_name); ?>" required aria-required="true" />
      <?php if (!empty($errors['first_name'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['first_name']); ?></div>
      <?php endif; ?>
    </div>

    <!-- Last Name -->
    <div class="col-md-6 mb-3">
      <label for="last_name" class="form-label">
        Last Name <span class="text-danger">*</span>
      </label>
      <input type="text" name="user[last_name]" id="last_name"
        class="form-control <?php if (!empty($errors['last_name'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->last_name); ?>" required aria-required="true" />
      <?php if (!empty($errors['last_name'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['last_name']); ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <!-- Email Address -->
    <div class="col-md-12 mb-3">
      <label for="email_address" class="form-label">
        Email Address <span class="text-danger">*</span>
      </label>
      <input type="email" name="user[email_address]" id="email_address"
        class="form-control <?php if (!empty($errors['email_address'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->email_address); ?>" required aria-required="true" />
      <?php if (!empty($errors['email_address'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['email_address']); ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <!-- Password -->
    <div class="col-md-6 mb-3">
      <label for="password" class="form-label">
        Password <?php if (!isset($user->user_id)) { ?><span class="text-danger">*</span><?php } ?>
      </label>
      <input type="password" name="user[password]" id="password"
        class="form-control <?php if (!empty($errors['password'])) echo 'is-invalid'; ?>"
        <?php echo isset($user->user_id) ? '' : 'required aria-required="true"'; ?> />
      <?php if (!empty($errors['password'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['password']); ?></div>
      <?php endif; ?>
    </div>

    <!-- Confirm Password -->
    <div class="col-md-6 mb-3">
      <label for="confirm_password" class="form-label">
        Confirm Password <?php if (!isset($user->user_id)) { ?><span class="text-danger">*</span><?php } ?>
      </label>
      <input type="password" name="user[confirm_password]" id="confirm_password"
        class="form-control <?php if (!empty($errors['confirm_password'])) echo 'is-invalid'; ?>"
        <?php echo isset($user->user_id) ? '' : 'required aria-required="true"'; ?> />
      <?php if (!empty($errors['confirm_password'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['confirm_password']); ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <!-- Phone Number -->
    <div class="col-md-12 mb-3">
      <label for="phone_number" class="form-label">
        Phone Number <span class="text-danger">*</span>
      </label>
      <input type="tel" name="user[phone_number]" id="phone_number"
        class="form-control <?php if (!empty($errors['phone_number'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->phone_number); ?>" required aria-required="true" />
      <?php if (!empty($errors['phone_number'])): ?>
        <div class="invalid-feedback"><?php echo h($errors['phone_number']); ?></div>
      <?php endif; ?>
    </div>
  </div>
</fieldset>
