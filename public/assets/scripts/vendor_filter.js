/**
 * @file vendor_filter.js
 *
 * Filters vendor cards based on search input, date selection, and sort order.
 * Dynamically paginates and sorts matching vendors, toggles empty state and pagination display.
 * JavaScript must be enabled for dynamic filtering; otherwise, PHP fallback applies.
 *
 * Used on: vendors/index.php
 */

document.addEventListener('DOMContentLoaded', function () {
  const vendorContainer = document.getElementById('vendor-card-container');
  const vendorCardsExist = document.querySelectorAll('#vendors .vendor-card').length > 0;

  if (!vendorContainer || !vendorCardsExist) return;

  const vendorsPerPage = 9;
  let currentPage = 1;
  let filteredVendors = [];

  const searchInput = document.getElementById('search');
  const dateSelect = document.getElementById('market_date_id');
  const sortSelect = document.getElementById('sort');
  const vendorCards = Array.from(document.querySelectorAll('#vendors .vendor-card'));
  const emptyMessage = document.getElementById('no-results');
  const paginationContainer = document.getElementById('vendor-pagination');

  const applyButtonWrapper = document.getElementById('apply-button-wrapper');
  if (applyButtonWrapper) {
    applyButtonWrapper.style.display = 'none';
  }

  /**
   * Paginates the vendor list.
   *
   * @param {HTMLElement[]} vendors
   * @param {number} page
   * @returns {HTMLElement[]}
   */
  function paginateVendors(vendors, page) {
    const start = (page - 1) * vendorsPerPage;
    const end = start + vendorsPerPage;
    return vendors.slice(start, end);
  }

  /**
   * Sorts vendors alphabetically.
   *
   * @param {HTMLElement[]} vendors
   * @param {string} sortType
   * @returns {HTMLElement[]}
   */
  function sortVendors(vendors, sortType) {
    return vendors.slice().sort((a, b) => {
      const nameA = a.dataset.name?.toLowerCase() || '';
      const nameB = b.dataset.name?.toLowerCase() || '';
      return sortType === 'name_desc' ? nameB.localeCompare(nameA) : nameA.localeCompare(nameB);
    });
  }

  /**
   * Renders pagination controls.
   *
   * @param {HTMLElement[]} vendorList
   */
  function renderPagination(vendorList) {
    paginationContainer.innerHTML = '';
    const pageCount = Math.ceil(vendorList.length / vendorsPerPage);
    if (pageCount <= 1) return;

    for (let i = 1; i <= pageCount; i++) {
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm btn-outline-primary mx-1';
      btn.textContent = i;
      if (i === currentPage) btn.classList.add('active');
      btn.addEventListener('click', () => {
        currentPage = i;
        renderFilteredVendors();
      });
      paginationContainer.appendChild(btn);
    }
  }

  /**
   * Renders the current page of filtered and sorted vendor cards.
   *
   * Clears existing content, displays matching cards, and updates pagination and empty state.
   */
  function renderFilteredVendors() {
    const sortType = sortSelect?.value || '';
    filteredVendors = sortVendors(filteredVendors, sortType); // ← THIS LINE FIXES IT

    const container = document.getElementById('vendor-card-container');
    container.innerHTML = ''; // Clear existing cards

    const toShow = paginateVendors(filteredVendors, currentPage);
    toShow.forEach(card => container.appendChild(card));

    emptyMessage.style.display = toShow.length ? 'none' : 'block';
    renderPagination(filteredVendors);
  }

  /**
   * Filters vendor cards by search term and selected market date.
   *
   * Updates the `filteredVendors` array and resets pagination to page 1.
   */
  function filterVendors() {
    const search = searchInput?.value.trim().toLowerCase() || '';
    const selectedDate = dateSelect?.value.trim() || '';

    filteredVendors = vendorCards.filter(card => {
      const name = card.dataset.name?.toLowerCase() || '';
      const dates = (card.dataset.dates || '').split(',').map(s => s.trim());
      const matchesName = name.includes(search);
      const matchesDate = !selectedDate || dates.includes(selectedDate);
      return matchesName && matchesDate;
    });

    currentPage = 1;
    renderFilteredVendors();
  }

  // Event listeners
  searchInput?.addEventListener('input', filterVendors);
  dateSelect?.addEventListener('change', filterVendors);
  sortSelect?.addEventListener('change', () => {
    currentPage = 1;
    renderFilteredVendors();
  });

  // Initial run
  filterVendors();
});
