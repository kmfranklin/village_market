document.addEventListener('DOMContentLoaded', function () {
  /**
   * Close the modal when clicking "X" or "Cancel".
   */
  const closeButtons = document.querySelectorAll('.close-modal');
  if (closeButtons.length) {
    closeButtons.forEach(button => {
      button.addEventListener('click', function () {
        const modal = this.closest('.modal');
        if (modal) {
          const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
          bsModal.hide();
        }
      });
    });
  }

  /**
   * Handles Hero Image selection from the gallery modal.
   * Updates the preview image and hidden input field.
   */
  const confirmButton = document.getElementById('confirmImageSelection');
  if (confirmButton) {
    confirmButton.addEventListener('click', function () {
      const selectedImage = document.querySelector("input[name='hero_image_select']:checked");
      if (selectedImage) {
        const imageUrl = selectedImage.getAttribute('data-url');
        const imageId = selectedImage.value;
        const imageAlt = selectedImage.getAttribute('data-alt') || 'Village Market hero image.';

        const currentHeroImage = document.getElementById('current-hero-image');
        const heroImageId = document.getElementById('hero_image_id');
        const heroAltText = document.getElementById('hero_alt_text');

        if (currentHeroImage) currentHeroImage.src = imageUrl;
        if (heroImageId) heroImageId.value = imageId;
        if (heroAltText) heroAltText.value = imageAlt;

        // Close Modal
        const modalElement = document.getElementById('imageGalleryModal');
        if (modalElement) {
          const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
          modalInstance.hide();
        }
      }
    });
  }

  /**
   * Ensure modal opens correctly.
   */
  const modalTriggers = document.querySelectorAll("[data-bs-toggle='modal']");
  if (modalTriggers.length) {
    modalTriggers.forEach(button => {
      button.addEventListener('click', function () {
        const targetModal = document.querySelector(this.getAttribute('data-bs-target'));
        if (targetModal) {
          const modalInstance = bootstrap.Modal.getOrCreateInstance(targetModal);
          modalInstance.show();
        }
      });
    });
  }

  /**
   * Handles suspend button clicks for vendors.
   */
  const suspendButtons = document.querySelectorAll('.suspend-btn');
  if (suspendButtons.length) {
    suspendButtons.forEach(button => {
      button.addEventListener('click', function () {
        const vendorId = this.getAttribute('data-vendor-id');
        const userId = this.getAttribute('data-user-id');
        const entityName = this.getAttribute('data-entity-name');
        const suspendUrl = this.getAttribute('data-suspend-url');

        if (!vendorId) return;

        const modal = document.getElementById(`suspend-modal-vendor-${vendorId}`);
        if (!modal) return;

        modal.querySelector('.suspend-message').innerHTML = `Are you sure you want to suspend "<strong>${entityName}</strong>"?`;
        modal.querySelector('.suspend-vendor-id').value = vendorId;
        modal.querySelector('.suspend-user-id').value = userId;
        modal.querySelector('.suspend-form').action = suspendUrl;

        const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
        bsModal.show();
      });
    });
  }

  /**
   * Handles multi-selection for the Price Unit modal.
   */
  const selectedUnits = new Set();
  const unitModal = document.getElementById('addUnitModal');
  if (unitModal) {
    unitModal.addEventListener('show.bs.modal', function () {
      selectedUnits.clear();

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

    document.querySelectorAll('.unit-btn').forEach(button => {
      button.addEventListener('click', function () {
        const unitId = this.getAttribute('data-unit-id');

        if (selectedUnits.has(unitId)) {
          selectedUnits.delete(unitId);
          this.classList.remove('btn-primary');
          this.classList.add('btn-outline-primary');
          document.querySelector(`#selectedUnitsContainer [data-unit-id='${unitId}']`)?.remove();
        } else {
          selectedUnits.add(unitId);
          this.classList.remove('btn-outline-primary');
          this.classList.add('btn-primary');
        }
        this.blur();
      });
    });

    const confirmUnitSelection = document.getElementById('confirmUnitSelection');
    if (confirmUnitSelection) {
      confirmUnitSelection.addEventListener('click', function () {
        const container = document.getElementById('selectedUnitsContainer');

        selectedUnits.forEach(unitId => {
          const unitBtn = document.querySelector(`.unit-btn[data-unit-id='${unitId}']`);
          const unitName = unitBtn.getAttribute('data-unit-name').toLowerCase();

          const placeholderText = unitName === 'each' ? 'Enter price per item' : `Enter price per ${unitName}`;

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

        selectedUnits.clear();

        const modalInstance = bootstrap.Modal.getOrCreateInstance(unitModal);
        modalInstance.hide();
      });
    }

    document.getElementById('selectedUnitsContainer').addEventListener('click', function (e) {
      if (e.target.classList.contains('remove-unit')) {
        const unitDiv = e.target.parentElement;
        const unitId = unitDiv.getAttribute('data-unit-id');

        document.querySelector(`.unit-btn[data-unit-id='${unitId}']`)?.classList.remove('btn-primary');
        document.querySelector(`.unit-btn[data-unit-id='${unitId}']`)?.classList.add('btn-outline-primary');

        unitDiv.remove();
      }
    });
  }

  /**
   * Closes the modal when clicking outside of it.
   */
  document.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) {
        const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
        bsModal.hide();
      }
    });
  });
});
