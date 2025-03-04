<?php
$entity_type = $entity_type ?? 'vendor';
$delete_url = $delete_url ?? '';
$entity_id = $entity_id ?? 0;
$entity_name = $entity_name ?? 'Unknown';
?>

<div class="modal fade" id="delete-modal-<?php echo h($entity_type . '-' . $entity_id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this <?php echo h($entity_type); ?>: "<strong><?php echo h($entity_name); ?></strong>"?</p>
      </div>
      <div class="modal-footer">
        <form action="<?php echo h($delete_url); ?>" method="POST">
          <input type="hidden" name="entity_type" value="<?php echo h($entity_type); ?>">
          <input type="hidden" name="entity_id" class="delete-entity-id" value="<?php echo h($entity_id); ?>">
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
          <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
