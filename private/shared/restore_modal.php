<div id="restore-modal" class="modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Confirm Restore</h2>
    <p id="restore-message">Are you sure you want to restore this vendor?</p>
    <form id="restore-form" action="<?php echo url_for('/admin/vendors/restore.php'); ?>" method="POST">
      <input type="hidden" name="user_id" id="restore-user-id" value="">
      <div class="modal-buttons">
        <button type="submit" class="success-button">Yes, Restore</button>
        <button type="button" class="cancel-button close-modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
