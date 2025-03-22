<div id="delete-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header text-center">
        <h5 class="modal-title w-100">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <p id="delete-message">
          Are you sure you want to delete this <span id="delete-entity"></span>?
        </p>
        <p class="fw-bold" id="delete-entity-name"></p>
      </div>

      <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
        <form id="delete-form" method="POST" class="w-100">
          <input type="hidden" name="entity_id" id="delete-entity-id">
          <input type="hidden" name="user_id" id="delete-user-id">
          <button type="submit" class="btn btn-danger w-100">Yes, Delete</button>
        </form>
        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
