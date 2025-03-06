document.addEventListener('DOMContentLoaded', function () {
  // Close the modal when clicking "X" or "Cancel"
  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      if (modal) {
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
          bsModal.hide(); // Use Bootstrap's hide() method
        }
      }
    });
  });

  /**
   * Handles suspend button clicks for vendors.
   * Dynamically updates the suspend modal content and form action.
   */
  document.querySelectorAll('.suspend-btn').forEach(button => {
    button.addEventListener('click', function () {
      const vendorId = this.getAttribute('data-vendor-id');
      const userId = this.getAttribute('data-user-id');
      const entityName = this.getAttribute('data-entity-name');
      const suspendUrl = this.getAttribute('data-suspend-url');

      if (!vendorId) return;

      // Find the correct modal
      const modal = document.getElementById(`suspend-modal-vendor-${vendorId}`);
      if (!modal) return;

      // Update modal content dynamically
      modal.querySelector('.suspend-message').innerHTML = `Are you sure you want to suspend "<strong>${entityName}</strong>"?`;

      // Update hidden input fields
      modal.querySelector('.suspend-vendor-id').value = vendorId;
      modal.querySelector('.suspend-user-id').value = userId;
      modal.querySelector('.suspend-form').action = suspendUrl;

      // Use Bootstrap's modal show function
      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    });
  });

  /**
   * Closes the modal when clicking outside of it.
   */
  document.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) {
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
          bsModal.hide();
        }
      }
    });
  });
});
