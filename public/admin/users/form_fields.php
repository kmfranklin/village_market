<?php
if (!isset($user)) {
  $user = new User();
}
?>

<fieldset>
  <legend>User Information</legend>
  <dl>
    <dt><label for="first_name">First Name</label></dt>
    <dd><input type="text" name="user[first_name]" id="first_name" value="<?php echo h($user->first_name); ?>" required /></dd>

    <dt><label for="last_name">Last Name</label></dt>
    <dd><input type="text" name="user[last_name]" id="last_name" value="<?php echo h($user->last_name); ?>" required /></dd>

    <dt><label for="email_address">Email Address</label></dt>
    <dd><input type="email" name="user[email_address]" id="email_address" value="<?php echo h($user->email_address); ?>" required /></dd>

    <dt><label for="password">Password</label></dt>
    <dd>
      <input type="password" name="user[password]" id="password"
        <?php echo isset($user->user_id) ? '' : 'required'; ?> />
    </dd>

    <dt><label for="confirm_password">Confirm Password</label></dt>
    <dd>
      <input type="password" name="user[confirm_password]" id="confirm_password"
        <?php echo isset($user->user_id) ? '' : 'required'; ?> />
    </dd>

    <dt><label for="phone_number">Phone Number</label></dt>
    <dd><input type="tel" name="user[phone_number]" id="phone_number" value="<?php echo h($user->phone_number); ?>" required /></dd>
  </dl>
</fieldset>
