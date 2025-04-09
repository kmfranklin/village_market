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
      <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
      <input type="text" name="user[first_name]" id="first_name"
        class="form-control <?php if (!empty($errors['first_name'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->first_name); ?>" placeholder="John" required aria-required="true">
    </div>

    <!-- Last Name -->
    <div class="col-md-6 mb-3">
      <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
      <input type="text" name="user[last_name]" id="last_name"
        class="form-control <?php if (!empty($errors['last_name'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->last_name); ?>" placeholder="Doe" required aria-required="true">
    </div>

    <!-- Email Address -->
    <div class="col-md-6 mb-3">
      <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
      <input type="email" name="user[email_address]" id="email_address"
        class="form-control <?php if (!empty($errors['email_address'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->email_address); ?>" placeholder="you@example.com" required aria-required="true">
    </div>

    <!-- Phone Number -->
    <div class="col-md-6 mb-3">
      <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
      <input type="tel" name="user[phone_number]" id="phone_number"
        class="form-control <?php if (!empty($errors['phone_number'])) echo 'is-invalid'; ?>"
        value="<?php echo h($user->phone_number); ?>"
        placeholder="e.g. 123-456-7890"
        pattern="^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$"
        required aria-required="true">
    </div>

    <!-- Password -->
    <div class="col-md-6 mb-3">
      <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
      <input type="password" name="user[password]" id="password"
        class="form-control <?php if (!empty($errors['password'])) echo 'is-invalid'; ?>"
        placeholder="Create a secure password"
        <?php echo empty($user->user_id) ? 'required aria-required="true"' : ''; ?>
        autocomplete="new-password">

      <!-- Password Strength Meter -->
      <div id="password-strength-meter" class="progress mt-2 d-none" style="height: 6px;">
        <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
      </div>

      <!-- Password Requirements Checklist -->
      <ul id="password-checklist" class="list-unstyled small text-muted d-none mt-2 mb-0">
        <li id="check-length">Minimum 12 characters</li>
        <li id="check-uppercase">At least 1 uppercase letter</li>
        <li id="check-lowercase">At least 1 lowercase letter</li>
        <li id="check-number">At least 1 number</li>
        <li id="check-special">At least 1 special character</li>
      </ul>
    </div>

    <!-- Confirm Password -->
    <div class="col-md-6 mb-3">
      <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
      <input type="password" name="user[confirm_password]" id="confirm_password"
        class="form-control <?php if (!empty($errors['confirm_password'])) echo 'is-invalid'; ?>"
        placeholder="Re-type your password"
        <?php echo empty($user->user_id) ? 'required aria-required="true"' : ''; ?>
        autocomplete="new-password">
      <div id="confirm-password-feedback" class="form-text d-none mt-1"></div>
    </div>
  </div>
</fieldset>
