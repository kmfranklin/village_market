document.addEventListener('DOMContentLoaded', function () {
  // Close modals when clicking "X" or "Cancel"
  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      if (modal) {
        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
        bsModal.hide();
      }
    });
  });

  // Hero Image Modal (Gallery)
  const confirmButton = document.getElementById('confirmImageSelection');
  if (confirmButton) {
    confirmButton.addEventListener('click', function () {
      const selectedImage = document.querySelector("input[name='hero_image_select']:checked");
      if (!selectedImage) return;

      const imageUrl = selectedImage.getAttribute('data-url');
      const imageId = selectedImage.value;
      const imageAlt = selectedImage.getAttribute('data-alt') || 'Village Market hero image.';

      const currentHeroImage = document.getElementById('current-hero-image');
      const heroImageId = document.getElementById('hero_image_id');
      const heroAltText = document.getElementById('hero_alt_text');

      if (currentHeroImage) currentHeroImage.src = imageUrl;
      if (heroImageId) heroImageId.value = imageId;
      if (heroAltText) heroAltText.value = imageAlt;

      const modalElement = document.getElementById('imageGalleryModal');
      if (modalElement) {
        const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modalInstance.hide();

        setTimeout(() => {
          modalElement.classList.remove('show');
          document.body.classList.remove('modal-open');
          document.querySelector('.modal-backdrop')?.remove();
        }, 300);
      }
    });
  }

  // Bootstrap modal triggers via data attributes
  document.querySelectorAll("[data-bs-toggle='modal']").forEach(button => {
    button.addEventListener('click', function () {
      const targetModal = document.querySelector(this.getAttribute('data-bs-target'));
      if (targetModal) {
        bootstrap.Modal.getOrCreateInstance(targetModal).show();
      }
    });
  });

  // Suspend Vendor Modal
  document.querySelectorAll('.suspend-btn').forEach(button => {
    button.addEventListener('click', function () {
      const vendorId = this.getAttribute('data-vendor-id');
      const userId = this.getAttribute('data-user-id');
      const entityName = this.getAttribute('data-entity-name');
      const suspendUrl = this.getAttribute('data-suspend-url');

      const modal = document.getElementById(`suspend-modal-vendor-${vendorId}`);
      if (!modal) return;

      modal.querySelector('.suspend-message').innerHTML = `Are you sure you want to suspend "<strong>${entityName}</strong>"?`;
      modal.querySelector('.suspend-vendor-id').value = vendorId;
      modal.querySelector('.suspend-user-id').value = userId;
      modal.querySelector('.suspend-form').action = suspendUrl;

      new bootstrap.Modal(modal).show();
    });
  });

  // Price Unit Modal: Multi-Selection & Input Fields
  const selectedUnits = new Set();
  const addUnitModal = document.getElementById('addUnitModal');

  if (addUnitModal) {
    addUnitModal.addEventListener('show.bs.modal', function () {
      selectedUnits.clear();

      document.querySelectorAll('.unit-btn').forEach(button => {
        const unitId = button.getAttribute('data-unit-id');
        const isSelected = document.querySelector(`#selectedUnitsContainer [data-unit-id='${unitId}']`);

        if (isSelected) {
          selectedUnits.add(unitId);
          button.classList.replace('btn-outline-primary', 'btn-primary');
        } else {
          button.classList.replace('btn-primary', 'btn-outline-primary');
        }
      });
    });
  }

  document.querySelectorAll('.unit-btn').forEach(button => {
    button.addEventListener('click', function () {
      const unitId = this.getAttribute('data-unit-id');

      if (selectedUnits.has(unitId)) {
        selectedUnits.delete(unitId);
        this.classList.replace('btn-primary', 'btn-outline-primary');
        document.querySelector(`#selectedUnitsContainer [data-unit-id='${unitId}']`)?.remove();
      } else {
        selectedUnits.add(unitId);
        this.classList.replace('btn-outline-primary', 'btn-primary');
      }

      this.blur();
    });
  });

  const confirmUnits = document.getElementById('confirmUnitSelection');
  if (confirmUnits) {
    confirmUnits.addEventListener('click', function () {
      const container = document.getElementById('selectedUnitsContainer');

      selectedUnits.forEach(unitId => {
        const unitBtn = document.querySelector(`.unit-btn[data-unit-id='${unitId}']`);
        const unitName = unitBtn.getAttribute('data-unit-name').toLowerCase();
        const placeholder = unitName === 'each' ? 'Enter price per item' : `Enter price per ${unitName}`;

        if (!container.querySelector(`[data-unit-id='${unitId}']`)) {
          const entry = document.createElement('div');
          entry.classList.add('selected-unit', 'd-flex', 'align-items-center', 'mb-2');
          entry.setAttribute('data-unit-id', unitId);
          entry.innerHTML = `
            <span class="me-2">${unitName}</span>
            <input type="number" step="0.01" name="product_price_unit[${unitId}][price]"
                   class="form-control form-control-sm" placeholder="${placeholder}" required>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-unit">&times;</button>
          `;
          container.appendChild(entry);
        }
      });

      selectedUnits.clear();
      const modalInstance = bootstrap.Modal.getInstance(addUnitModal);
      if (modalInstance) modalInstance.hide();
    });
  }

  document.getElementById('selectedUnitsContainer')?.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-unit')) {
      const unitDiv = e.target.closest('[data-unit-id]');
      const unitId = unitDiv.getAttribute('data-unit-id');

      document.querySelector(`.unit-btn[data-unit-id='${unitId}']`)?.classList.replace('btn-primary', 'btn-outline-primary');
      unitDiv.remove();
    }
  });

  // Shared Delete Modal Handler
  function initializeDeleteModal() {
    const deleteModal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const entitySpan = document.getElementById('delete-entity');
    const entityNameEl = document.getElementById('delete-entity-name');
    const entityIdInput = document.getElementById('delete-entity-id');
    const userIdInput = document.getElementById('delete-user-id');

    if (!deleteModal || !deleteForm || !entitySpan || !entityNameEl || !entityIdInput) {
      return;
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function () {
        const entityId = this.getAttribute('data-entity-id');
        const entityType = this.getAttribute('data-entity');
        const entityName = this.getAttribute('data-entity-name');
        const deleteUrl = this.getAttribute('data-delete-url');
        const userId = this.getAttribute('data-user-id') || '';

        if (!entityId || !entityType || !deleteUrl) return;

        entitySpan.textContent = entityType;
        entityNameEl.textContent = entityName;
        entityIdInput.value = entityId;
        if (userIdInput) userIdInput.value = userId;
        deleteForm.action = deleteUrl;
      });
    });
  }

  // Suspend Modal Handler
  if (document.querySelector('.suspend-btn')) {
    document.querySelectorAll('.suspend-btn').forEach(button => {
      button.addEventListener('click', function () {
        const modal = document.getElementById('suspend-modal');
        if (!modal) return;

        modal.querySelector('#suspend-vendor-id').value = this.getAttribute('data-vendor-id');
        modal.querySelector('#suspend-user-id').value = this.getAttribute('data-user-id');
        modal.querySelector('#suspend-entity-name').textContent = this.getAttribute('data-entity-name');
        modal.querySelector('#suspend-form').action = this.getAttribute('data-suspend-url');
      });
    });
  }

  // Restore Modal Handler
  if (document.querySelector('.restore-btn')) {
    document.querySelectorAll('.restore-btn').forEach(button => {
      button.addEventListener('click', function () {
        const modal = document.getElementById('restore-modal');
        if (!modal) return;

        modal.querySelector('#restore-user-id').value = this.getAttribute('data-user-id');
        modal.querySelector('#restore-entity-name').textContent = this.getAttribute('data-entity-name');
        modal.querySelector('#restore-form').action = this.getAttribute('data-restore-url');
      });
    });
  }

  if (document.querySelector('.delete-btn')) {
    initializeDeleteModal();
  }

  // Close modal on outside click
  document.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) {
        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
        bsModal.hide();
      }
    });
  });
});
