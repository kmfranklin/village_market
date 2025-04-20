/**
 * @file product_card_filter.js
 *
 * Filters product cards based on search input and dropdown selections.
 * Includes pagination logic and handles live updates on input change.
 *
 * Used on: products/index.php
 */

document.addEventListener('DOMContentLoaded', function () {
  const productContainer = document.querySelector('#products .row');
  const productCards = Array.from(document.querySelectorAll('#products .col'));
  const searchInput = document.getElementById('search');
  const vendorSelect = document.getElementById('vendor_id');
  const categorySelect = document.getElementById('category_id');
  const emptyMessage = document.getElementById('no-results');
  const paginationContainer = document.getElementById('product-pagination');
  const PRODUCTS_PER_PAGE = 9;

  if (!productContainer || productCards.length === 0) return;

  let currentPage = 1;
  let filteredProducts = [];

  /**
   * Filters product cards based on search term, vendor, and category dropdowns.
   *
   * Applies all active filters and updates the `filteredProducts` array, then triggers rendering.
   */
  function filterProducts() {
    const search = searchInput?.value.trim().toLowerCase() || '';
    const vendor = vendorSelect?.value || '';
    const category = categorySelect?.value || '';

    filteredProducts = productCards.filter(card => {
      const name = card.dataset.name || '';
      const vendorId = card.dataset.vendor || '';
      const categoryId = card.dataset.category || '';

      const matchesSearch = name.includes(search);
      const matchesVendor = !vendor || vendor === vendorId;
      const matchesCategory = !category || category === categoryId;

      return matchesSearch && matchesVendor && matchesCategory;
    });

    currentPage = 1;
    renderProducts();
  }

  /**
   * Renders the filtered product cards for the current page.
   *
   * Replaces the product grid content and triggers pagination rendering.
   */
  function renderProducts() {
    productContainer.innerHTML = '';

    const totalPages = Math.ceil(filteredProducts.length / PRODUCTS_PER_PAGE);
    const start = (currentPage - 1) * PRODUCTS_PER_PAGE;
    const end = start + PRODUCTS_PER_PAGE;
    const toShow = filteredProducts.slice(start, end);

    toShow.forEach(card => productContainer.appendChild(card));

    emptyMessage.style.display = toShow.length ? 'none' : 'block';

    renderPagination(totalPages);
  }

  /**
   * Renders pagination buttons.
   *
   * @param {number} totalPages
   */
  function renderPagination(totalPages) {
    paginationContainer.innerHTML = '';
    if (totalPages <= 1) return;

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm btn-outline-primary mx-1';
      btn.textContent = i;

      if (i === currentPage) {
        btn.classList.add('active');
      }

      btn.addEventListener('click', () => {
        currentPage = i;
        renderProducts();
      });

      paginationContainer.appendChild(btn);
    }
  }

  // Attach event listeners
  searchInput?.addEventListener('input', filterProducts);
  vendorSelect?.addEventListener('change', filterProducts);
  categorySelect?.addEventListener('change', filterProducts);

  // Initial run
  filterProducts();
});
