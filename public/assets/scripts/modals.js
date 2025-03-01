/**
 * Opens a modal.
 *
 * @param {HTMLElement} modal - The modal element to display.
 */
export function openModal(modal) {
  if (modal) {
    modal.style.display = 'block';
  } else {
    console.error('ERROR: Modal element is undefined.');
  }
}

/**
 * Closes a modal.
 *
 * @param {HTMLElement} modal - The modal element to hide.
 */
export function closeModal(modal) {
  if (modal) {
    modal.style.display = 'none';
  } else {
    console.error('ERROR: Modal element is undefined.');
  }
}

/**
 * Wait for the DOM to fully load before running event listeners.
 */
document.addEventListener('DOMContentLoaded', function () {
  // Close the modal when clicking "X" or "Cancel"
  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      closeModal(modal);
    });
  });

  /**
   * Handles delete button clicks for vendors and products.
   * Dynamically updates the modal content and form action.
   */
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (event) {
      event.preventDefault();

      const entityType = this.getAttribute('data-entity');
      const entityId = this.getAttribute('data-entity-id');
      const entityName = this.getAttribute('data-entity-name');
      const deleteUrl = this.getAttribute('data-delete-url');

      console.log('Delete button clicked for:', entityType, 'ID:', entityId);

      if (!entityId || entityId === '0' || entityId === null) {
        console.error('ERROR: Delete button is missing a valid entity ID.');
        return;
      }

      // Find the correct modal using entityType and entityId
      const modalId = `delete-modal-${entityType}-${entityId}`;
      const modal = document.getElementById(modalId);

      if (!modal) {
        console.error(`ERROR: Delete modal not found with ID: ${modalId}`);
        console.log('Current modals available:', document.querySelectorAll('.modal'));
        return;
      }

      console.log('Modal found:', modal);

      // Update modal message dynamically
      modal.querySelector('.delete-message').innerHTML = `Are you sure you want to delete this <strong>${entityType}</strong>: "<strong>${entityName}</strong>"?`;

      // Update hidden input fields before submitting
      const entityIdField = modal.querySelector('.delete-entity-id');
      if (entityIdField) {
        entityIdField.value = entityId;
      } else {
        console.error(`ERROR: Hidden input field for entity ID not found in modal.`);
        return;
      }

      // Update form action dynamically
      modal.querySelector('.delete-form').action = deleteUrl;

      console.log('Opening modal...');
      openModal(modal);
    });
  });
});

/**
 * Closes the modal when clicking outside of it.
 */
document.addEventListener('click', function (event) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (event.target === modal) {
      closeModal(modal);
    }
  });
});
