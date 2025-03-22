/**
 * Attaches event listeners to buttons that trigger modals.
 *
 * @param {string} buttonClass - The class of the buttons that trigger the modal.
 * @param {string} modalId - The ID of the modal to open.
 * @param {object} fieldMapping - An object mapping modal input fields to data attributes.
 */
function setupModal(buttonClass, modalId, fieldMapping) {
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    document.querySelectorAll(buttonClass).forEach(button => {
      button.addEventListener('click', function (event) {
        event.preventDefault();

        for (const fieldId in fieldMapping) {
          const attr = fieldMapping[fieldId];
          const element = document.getElementById(fieldId);

          if (element) {
            element.value = this.getAttribute(attr) || '';
          }
        }

        openModal(modal);
      });
    });
  });
}

// Setup Suspend Vendor Modal
setupModal('.suspend-btn', 'suspend-modal', {
  'suspend-vendor-id': 'data-vendor-id',
  'suspend-user-id': 'data-user-id',
  'suspend-message': 'data-entity-name',
});

// Setup Restore Vendor Modal
setupModal('.restore-btn', 'restore-modal', {
  'restore-user-id': 'data-user-id',
  'restore-message': 'data-entity-name',
});
