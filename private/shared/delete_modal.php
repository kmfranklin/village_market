<?php
$entity_type = $entity_type ?? 'vendor';
$delete_url = $delete_url ?? '';
$entity_id = $entity_id ?? 0;
$entity_name = $entity_name ?? 'Unknown';
?>

<div id="delete-modal-<?php echo h($entity_type . '-' . $entity_id); ?>" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Confirm Deletion</h2>
    <p class="delete-message">Are you sure you want to delete this <?php echo h($entity_type); ?>: "<strong><?php echo h($entity_name); ?></strong>"?</p>
    <form class="delete-form" action="<?php echo h($delete_url); ?>" method="POST">
      <input type="hidden" name="entity_type" value="<?php echo h($entity_type); ?>">
      <input type="hidden" name="entity_id" class="delete-entity-id" value="<?php echo h($entity_id); ?>">
      <div class="modal-buttons">
        <button type="submit" class="danger-button">Yes, Delete</button>
        <button type="button" class="cancel-button close-modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
