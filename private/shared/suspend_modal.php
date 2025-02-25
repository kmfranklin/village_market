<?php

$entity_type = $entity_type ?? 'vendor';
$suspend_url = $suspend_url ?? '';
$entity_id = $entity_id ?? 0;
$user_id = $user_id ?? 0;
$entity_name = $entity_name ?? 'Unknown Vendor';

?>

<div id="suspend-modal" class="modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Confirm Suspension</h2>
    <p id="suspend-message">Are you sure you want to suspend this vendor? They will not be able to log in but can be restored later.</p>
    <form id="suspend-form" action="<?php echo h($suspend_url); ?>" method="POST">
      <input type="hidden" name="vendor_id" id="suspend-vendor-id" value="<?php echo h($entity_id); ?>">
      <input type="hidden" name="user_id" id="suspend-user-id" value="<?php echo h($user_id); ?>">
      <div class="modal-buttons">
        <button type="submit" class="warning-button">Yes, Suspend</button>
        <button type="button" class="cancel-button close-modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
