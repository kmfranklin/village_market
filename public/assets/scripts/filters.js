document.addEventListener('DOMContentLoaded', function () {
  const applyButton = document.getElementById('apply-button');
  const sortDropdown = document.getElementById('sort');

  // Hide the "Apply" button if JavaScript is enabled
  if (applyButton) {
    applyButton.style.display = 'none';
  }

  // Automatically submit the form when the sort dropdown changes
  if (sortDropdown) {
    sortDropdown.addEventListener('change', function () {
      const form = document.getElementById('product-filter-form');
      form.submit();
    });
  }
});
