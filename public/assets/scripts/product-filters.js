/**
 * Product Filter Handler
 * Handles both card and table layouts
 */
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('product-filter-form');
  if (!form) return;

  const submitBtn = form.querySelector('[data-filter-submit]');
  const searchInput = form.querySelector('input[name="search"]');
  const categorySelect = form.querySelector('select[name="category_id"]');
  const vendorSelect = form.querySelector('select[name="vendor_id"]');

  // Get all filterable elements (cards or table rows)
  const filterableItems = document.querySelectorAll('.product-table tbody tr, #products .col');

  if (filterableItems.length > 0) {
    submitBtn.style.display = 'none';
  }

  function filterItems() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedCategory = categorySelect ? categorySelect.value : '';
    const selectedVendor = vendorSelect ? vendorSelect.value : '';

    let hasVisibleItems = false;

    filterableItems.forEach(item => {
      const name = item.dataset.name;
      const category = item.dataset.category;
      const vendor = item.dataset.vendor;

      const matchesSearch = name.includes(searchTerm);
      const matchesCategory = !selectedCategory || category === selectedCategory;
      const matchesVendor = !selectedVendor || vendor === selectedVendor;

      const isVisible = matchesSearch && matchesCategory && matchesVendor;
      item.style.display = isVisible ? '' : 'none';
      if (isVisible) hasVisibleItems = true;
    });

    // Update table visibility
    document.querySelectorAll('.table-responsive').forEach(wrapper => {
      const table = wrapper.querySelector('table');
      const alert = wrapper.querySelector('.alert');
      const visibleRows = wrapper.querySelectorAll('tbody tr:not([style*="display: none"])');

      if (visibleRows.length === 0) {
        if (table) table.style.display = 'none';
        if (!alert) {
          const message = document.createElement('div');
          message.className = 'alert alert-success';
          message.innerHTML = '<i class="bi bi-info-circle"></i> No products match your search criteria.';
          wrapper.appendChild(message);
        }
      } else {
        if (table) table.style.display = '';
        if (alert) alert.remove();
      }
    });
  }

  // Add event listeners
  searchInput.addEventListener('input', debounce(filterItems, 300));
  if (categorySelect) categorySelect.addEventListener('change', filterItems);
  if (vendorSelect) vendorSelect.addEventListener('change', filterItems);

  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }
});
