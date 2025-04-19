<div id="restore-modal" class="modal fade" tabindex="-1" role="dialog" aria-modal="true">
  <div class="modal-dialog modal-dialog-centered" role="document" aria-labelledby="restoreModalLabel">
    <div class="modal-content">

      <div class="modal-header text-center">
        <h5 class="modal-title w-100" id="restoreModalLabel">Confirm Restore</h5>
        <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <p class="restore-message">Are you sure you want to restore <strong id="restore-entity-name"></strong>?</p>
      </div>

      <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
        <form id="restore-form" method="POST" class="w-100">
          <input type="hidden" name="user_id" id="restore-user-id">
          <button type="submit" class="btn btn-primary w-100">Yes, Restore</button>
        </form>
        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
