document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.delete-btn');
  const modal = document.getElementById('delete-modal');
  const deleteVendorId = document.getElementById('delete-vendor-id');
  const deleteUserId = document.getElementById('delete-user-id');
  const deleteMessage = document.getElementById('delete-message');
  const deleteForm = document.getElementById('delete-form');

  if (!modal || !deleteVendorId || !deleteUserId || !deleteMessage || !deleteForm) {
    console.error('ERROR: One or more modal elements are missing.');
    return;
  }

  deleteButtons.forEach(button => {
    button.addEventListener('click', function (event) {
      event.preventDefault();

      const vendorId = this.getAttribute('data-vendor-id');
      const userId = this.getAttribute('data-user-id');
      const entityName = this.getAttribute('data-entity-name');

      if (!vendorId || !userId) {
        console.error('ERROR: Vendor ID or User ID is missing.');
        return;
      }

      deleteVendorId.value = vendorId;
      deleteUserId.value = userId;
      deleteMessage.innerHTML = `Are you sure you want to delete <strong>${entityName}</strong>?`;
      modal.style.display = 'block';
    });
  });

  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function () {
      modal.style.display = 'none';
    });
  });

  window.addEventListener('click', function (event) {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });
});
