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
