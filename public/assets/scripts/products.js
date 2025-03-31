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

/**
 * Filters product cards based on search input and dropdown selections.
 */
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('search');
  const vendorSelect = document.getElementById('vendor_id');
  const categorySelect = document.getElementById('category_id');
  const productCards = document.querySelectorAll('#products .col');

  function filterProducts() {
    const search = searchInput.value.trim().toLowerCase();
    const vendor = vendorSelect.value;
    const category = categorySelect.value;

    let anyVisible = false;

    productCards.forEach(card => {
      const name = card.dataset.name;
      const vendorId = card.dataset.vendor;
      const categoryId = card.dataset.category;

      const matchesSearch = name.includes(search);
      const matchesVendor = vendor === '' || vendor === vendorId;
      const matchesCategory = category === '' || category === categoryId;

      const isVisible = matchesSearch && matchesVendor && matchesCategory;
      card.style.display = isVisible ? 'block' : 'none';

      if (isVisible) anyVisible = true;
    });

    // Optional: Handle empty state
    const emptyMessage = document.getElementById('no-results');
    if (emptyMessage) {
      emptyMessage.style.display = anyVisible ? 'none' : 'block';
    }
  }

  searchInput.addEventListener('input', filterProducts);
  vendorSelect.addEventListener('change', filterProducts);
  categorySelect.addEventListener('change', filterProducts);

  // Initial filter on page load
  filterProducts();
});
