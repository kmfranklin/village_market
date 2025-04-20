/**
 * @file sort_handler.js
 *
 * Hides the "Apply" button and auto-submits the form when the sort dropdown changes.
 * Enhances filter UX by removing the need to manually apply sort changes.
 *
 * Used on: any page with a filter form and sort dropdown (cards or tables).
 */

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('product-filter-form');
  if (!form) return;

  const applyButton = form.querySelector('#apply-button');
  const sortDropdown = form.querySelector('#sort');

  if (applyButton) {
    applyButton.style.display = 'none';
  }

  // Submit the form on sort change (let PHP handle it)
  if (sortDropdown) {
    sortDropdown.addEventListener('change', () => form.submit());
  }
});
