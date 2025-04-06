/**
 * Filters product cards based on search input and dropdown selections.
 *
 * Used on: products/index.php
 */

document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('search');
  const vendorSelect = document.getElementById('vendor_id');
  const categorySelect = document.getElementById('category_id');
  const productCards = document.querySelectorAll('#products .col');
  const emptyMessage = document.getElementById('no-results');

  if (!searchInput || productCards.length === 0) return;

  function filterProducts() {
    const search = searchInput.value.trim().toLowerCase();
    const vendor = vendorSelect?.value || '';
    const category = categorySelect?.value || '';

    let anyVisible = false;

    productCards.forEach(card => {
      const name = card.dataset.name?.toLowerCase() || '';
      const vendorId = card.dataset.vendor || '';
      const categoryId = card.dataset.category || '';

      const matchesSearch = name.includes(search);
      const matchesVendor = !vendor || vendor === vendorId;
      const matchesCategory = !category || category === categoryId;

      const isVisible = matchesSearch && matchesVendor && matchesCategory;
      card.style.display = isVisible ? 'block' : 'none';

      if (isVisible) anyVisible = true;
    });

    if (emptyMessage) {
      emptyMessage.style.display = anyVisible ? 'none' : 'block';
    }
  }

  searchInput.addEventListener('input', filterProducts);
  vendorSelect?.addEventListener('change', filterProducts);
  categorySelect?.addEventListener('change', filterProducts);

  // Run on page load
  filterProducts();
});
