/**
 * Toggles the visibility of the price input field when a price unit checkbox is selected.
 *
 * @param {HTMLElement} checkbox - The checkbox input element for selecting a price unit.
 * @param {string} inputId - The ID of the corresponding price input field to show/hide.
 */
export function togglePriceInput(checkbox, inputId) {
  let priceInput = document.getElementById(inputId);
  if (priceInput) {
    priceInput.style.display = checkbox.checked ? 'inline-block' : 'none';
  } else {
    console.error(`togglePriceInput: Input field with ID '${inputId}' not found.`);
  }
}

// Attach to window so inline event attributes like `onchange="togglePriceInput(...)"` work
window.togglePriceInput = togglePriceInput;

document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('delete-modal');
  const deleteForm = document.getElementById('delete-form');
  const deleteEntityId = document.getElementById('delete-entity-id');
  const deleteMessage = document.getElementById('delete-message');

  // Listen for delete button clicks on products
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent default link behavior

      const entityId = this.getAttribute('data-entity-id');
      const entityType = this.getAttribute('data-entity-type');
      const deleteUrl = this.getAttribute('data-delete-url');

      // Update modal fields
      deleteEntityId.value = entityId;
      deleteForm.action = deleteUrl;
      deleteMessage.innerHTML = `Are you sure you want to delete this ${entityType}?`;
    });
  });
});
