/**
 * Toggles the visibility of the price input field when a price unit checkbox is selected.
 *
 * @param {HTMLInputElement} checkbox - The checkbox input element for selecting a price unit.
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

// Attach to window so inline event attributes like `onchange="togglePriceInput(...)` work
window.togglePriceInput = togglePriceInput;
