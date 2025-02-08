<?php
if (!isset($user)) {
  $user = new User(); // Ensure $user is always set
}
?>

<fieldset>
  <legend>User Information</legend>
  <dl>
    <dt><label for="first_name">First Name</label></dt>
    <dd><input type="text" name="first_name" id="first_name" value="<?php echo h($user->first_name); ?>" required /></dd>

    <dt><label for="last_name">Last Name</label></dt>
    <dd><input type="text" name="last_name" id="last_name" value="<?php echo h($user->last_name); ?>" required /></dd>

    <dt><label for="email_address">Email Address</label></dt>
    <dd><input type="email" name="email_address" id="email_address" value="<?php echo h($user->email_address); ?>" required /></dd>

    <dt><label for="password">Password</label></dt>
    <dd><input type="password" name="password" id="password" required /></dd>

    <dt><label for="confirm_password">Confirm Password</label></dt>
    <dd><input type="password" name="confirm_password" id="confirm_password" required /></dd>

    <dt><label for="phone_number">Phone Number</label></dt>
    <dd><input type="tel" name="phone_number" id="phone_number" value="<?php echo h($user->phone_number); ?>" required /></dd>
  </dl>
</fieldset>
