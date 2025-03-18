document.addEventListener('DOMContentLoaded', function () {
  // Close the modal when clicking "X" or "Cancel"
  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      if (modal) {
        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
        bsModal.hide();
      }
    });
  });

  /**
   * Handles Hero Image selection from the gallery modal.
   * Updates the preview image and hidden input field.
   */
  let confirmButton = document.getElementById('confirmImageSelection');

  if (confirmButton) {
    confirmButton.addEventListener('click', function () {
      let selectedImage = document.querySelector("input[name='hero_image_select']:checked");
      if (selectedImage) {
        let imageUrl = selectedImage.getAttribute('data-url');
        let imageId = selectedImage.value;
        let imageAlt = selectedImage.getAttribute('data-alt') || 'Village Market hero image.';

        let currentHeroImage = document.getElementById('current-hero-image');
        let heroImageId = document.getElementById('hero_image_id');
        let heroAltText = document.getElementById('hero_alt_text');

        // Ensure all elements exist before modifying them
        if (currentHeroImage) {
          currentHeroImage.src = imageUrl;
        }

        if (heroImageId) {
          heroImageId.value = imageId;
        }

        if (heroAltText) {
          heroAltText.value = imageAlt;
        }

        // Close Modal
        let modalElement = document.getElementById('imageGalleryModal');

        if (modalElement) {
          let modalInstance = bootstrap.Modal.getInstance(modalElement);
          if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
          }
          modalInstance.hide();

          setTimeout(() => {
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');

            let backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
              backdrop.remove();
            }
          }, 300);
        }
      }
    });
  }

  // Ensure modal opens correctly
  document.querySelectorAll("[data-bs-toggle='modal']").forEach(button => {
    button.addEventListener('click', function () {
      let targetModal = document.querySelector(this.getAttribute('data-bs-target'));
      if (targetModal) {
        let modalInstance = bootstrap.Modal.getOrCreateInstance(targetModal);
        modalInstance.show();
      }
    });
  });

  /**
   * Handles suspend button clicks for vendors.
   * Dynamically updates the suspend modal content and form action.
   */
  document.querySelectorAll('.suspend-btn').forEach(button => {
    button.addEventListener('click', function () {
      const vendorId = this.getAttribute('data-vendor-id');
      const userId = this.getAttribute('data-user-id');
      const entityName = this.getAttribute('data-entity-name');
      const suspendUrl = this.getAttribute('data-suspend-url');

      if (!vendorId) return;

      // Find the correct modal
      const modal = document.getElementById(`suspend-modal-vendor-${vendorId}`);
      if (!modal) return;

      // Update modal content dynamically
      modal.querySelector('.suspend-message').innerHTML = `Are you sure you want to suspend "<strong>${entityName}</strong>"?`;

      // Update hidden input fields
      modal.querySelector('.suspend-vendor-id').value = vendorId;
      modal.querySelector('.suspend-user-id').value = userId;
      modal.querySelector('.suspend-form').action = suspendUrl;

      // Use Bootstrap's modal show function
      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    });
  });

  /**
   * Handles multi-selection for the Price Unit modal.
   * Allows users to select multiple price units before confirming.
   */
  let selectedUnits = new Set();

  // Sync modal with already selected units when opened
  document.getElementById('addUnitModal').addEventListener('show.bs.modal', function () {
    selectedUnits.clear(); // Reset selection state

    document.querySelectorAll('.unit-btn').forEach(button => {
      const unitId = button.getAttribute('data-unit-id');
      const isAlreadySelected = document.querySelector(`#selectedUnitsContainer [data-unit-id='${unitId}']`);

      if (isAlreadySelected) {
        selectedUnits.add(unitId);
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-primary');
      } else {
        button.classList.remove('btn-primary');
        button.classList.add('btn-outline-primary');
      }
    });
  });

  // Toggle selection on unit buttons (Ensure only one event listener is attached)
  document.querySelectorAll('.unit-btn').forEach(button => {
    button.addEventListener('click', function () {
      const unitId = this.getAttribute('data-unit-id');

      if (selectedUnits.has(unitId)) {
        selectedUnits.delete(unitId);
        this.classList.remove('btn-primary');
        this.classList.add('btn-outline-primary');

        // Remove unit from form when deselected
        document.querySelector(`#selectedUnitsContainer [data-unit-id='${unitId}']`)?.remove();
      } else {
        selectedUnits.add(unitId);
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-primary');
      }

      this.blur(); // Prevents Bootstrap's active state issue
    });
  });

  // Confirm selection and add selected units to the form
  document.getElementById('confirmUnitSelection').addEventListener('click', function () {
    const container = document.getElementById('selectedUnitsContainer');

    selectedUnits.forEach(unitId => {
      const unitBtn = document.querySelector(`.unit-btn[data-unit-id='${unitId}']`);
      const unitName = unitBtn.getAttribute('data-unit-name').toLowerCase();

      // Determine placeholder text
      const placeholderText = unitName === 'each' ? `Enter price per item` : `Enter price per ${unitName}`;

      if (!container.querySelector(`[data-unit-id='${unitId}']`)) {
        const unitEntry = document.createElement('div');
        unitEntry.classList.add('selected-unit', 'd-flex', 'align-items-center', 'mb-2');
        unitEntry.setAttribute('data-unit-id', unitId);
        unitEntry.innerHTML = `
          <span class="me-2">${unitName}</span>
          <input type="number" step="0.01" name="product_price_unit[${unitId}][price]" 
                 class="form-control form-control-sm" placeholder="${placeholderText}" required>
          <button type="button" class="btn btn-danger btn-sm ms-2 remove-unit">&times;</button>
        `;
        container.appendChild(unitEntry);
      }
    });

    // Reset selectedUnits set after confirming
    selectedUnits.clear();

    // Close modal
    let modalElement = document.getElementById('addUnitModal');
    let modalInstance = bootstrap.Modal.getInstance(modalElement);
    if (!modalInstance) {
      modalInstance = new bootstrap.Modal(modalElement);
    }
    modalInstance.hide();
  });

  // Remove selected unit from form when clicking the remove button (Ensuring only one event listener)
  document.getElementById('selectedUnitsContainer').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-unit')) {
      const unitDiv = e.target.parentElement;
      const unitId = unitDiv.getAttribute('data-unit-id');

      // Also remove highlight from the modal when reopened
      document.querySelector(`.unit-btn[data-unit-id='${unitId}']`)?.classList.remove('btn-primary');
      document.querySelector(`.unit-btn[data-unit-id='${unitId}']`)?.classList.add('btn-outline-primary');

      unitDiv.remove();
    }
  });
  /**
   * Closes the modal when clicking outside of it.
   */
  document.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) {
        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
        bsModal.hide();
      }
    });
  });
});
