/**
 * @file product_table_filters.js
 *
 * Filters product table rows based on search input and dropdown selections.
 * Also toggles the "No results" alert when no matching rows are found.
 *
 * Used on: products/manage.php
 */

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('product-filter-form');
  if (!form) return;

  const searchInput = form.querySelector('input[name="search"]');
  const categorySelect = form.querySelector('select[name="category_id"]');
  const vendorSelect = form.querySelector('select[name="vendor_id"]');
  const submitButton = form.querySelector('#apply-button');
  const tableRows = document.querySelectorAll('.product-table tbody tr');

  // Hide the apply button if JavaScript is active
  if (submitButton) {
    submitButton.style.display = 'none';
  }

  /**
   * Filters visible product table rows based on input values.
   *
   * Matches against product name, vendor, and category.
   * Also handles toggling of the table and alert if no results match.
   */
  function filterTable() {
    const searchTerm = searchInput?.value.trim().toLowerCase() || '';
    const selectedCategory = categorySelect?.value || '';
    const selectedVendor = vendorSelect?.value || '';

    tableRows.forEach(row => {
      const name = row.dataset.name?.toLowerCase() || '';
      const category = row.dataset.category || '';
      const vendor = row.dataset.vendor || '';

      const matchesSearch = name.includes(searchTerm);
      const matchesCategory = !selectedCategory || String(category) === String(selectedCategory);
      const matchesVendor = !selectedVendor || String(vendor) === String(selectedVendor);

      row.style.display = matchesSearch && matchesCategory && matchesVendor ? '' : 'none';
    });

    // Show/hide "No results" alert
    document.querySelectorAll('.table-responsive').forEach(wrapper => {
      const table = wrapper.querySelector('table');
      let alert = wrapper.querySelector('.alert');
      const visibleRows = wrapper.querySelectorAll('tbody tr:not([style*="display: none"])');

      if (visibleRows.length === 0) {
        if (table) table.style.display = 'none';
        if (!alert) {
          alert = document.createElement('div');
          alert.className = 'alert alert-success';
          alert.innerHTML = '<i class="bi bi-info-circle"></i> No products match your search criteria.';
          wrapper.appendChild(alert);
        }
      } else {
        if (table) table.style.display = '';
        if (alert) alert.remove();
      }
    });
  }

  /**
   * Returns a debounced version of a function.
   *
   * Ensures the function is only called after a delay with no new calls.
   *
   * @param {Function} func - Function to debounce
   * @param {number} wait - Delay time in milliseconds
   * @returns {Function}
   */
  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  searchInput?.addEventListener('input', debounce(filterTable, 300));
  categorySelect?.addEventListener('change', filterTable);
  vendorSelect?.addEventListener('change', filterTable);

  // Initial filter pass
  filterTable();
});
