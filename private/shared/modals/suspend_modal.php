<div id="suspend-modal" class="modal fade" tabindex="-1" role="dialog" aria-modal="true">
  <div class="modal-dialog modal-dialog-centered" role="document" aria-labelledby="suspendModalLabel">
    <div class="modal-content">

      <div class="modal-header text-center">
        <h5 class="modal-title w-100" id="suspendModalLabel">Confirm Suspension</h5>
        <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <p class="suspend-message">Are you sure you want to suspend <strong id="suspend-entity-name"></strong>?</p>
      </div>

      <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
        <form id="suspend-form" method="POST" class="w-100">
        </form>
        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
