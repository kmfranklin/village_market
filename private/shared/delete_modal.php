<?php

$entity_type = $entity_type ?? 'vendor';
$delete_url = $delete_url ?? '';
$entity_id = $entity_id ?? 0;
$user_id = $user_id ?? 0;
$entity_name = $entity_name ?? 'Unknown Vendor';

?>

<div id="delete-modal" class="modal">
  <?php echo "<!-- DEBUG: Rendering modal content -->"; ?>
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Confirm Deletion</h2>
    <p id="delete-message">Are you sure you want to delete this vendor?</p>
    <form id="delete-form" action="<?php echo h($delete_url); ?>" method="POST">
      <input type="hidden" name="vendor_id" id="delete-vendor-id" value="<?php echo h($entity_id); ?>">
      <input type="hidden" name="user_id" id="delete-user-id" value="<?php echo h($user_id); ?>">
      <div class="modal-buttons">
        <button type="submit" class="danger-button">Yes, Delete</button>
        <button type="button" class="cancel-button close-modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
