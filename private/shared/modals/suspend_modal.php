<div id="suspend-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header text-center">
        <h5 class="modal-title w-100">Confirm Suspension</h5>
        <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <p class="suspend-message">Are you sure you want to suspend <strong id="suspend-entity-name"></strong>?</p>
      </div>

      <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
        <form id="suspend-form" method="POST" class="w-100">
          <!-- JS will inject the appropriate hidden fields below -->
        </form>
        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
