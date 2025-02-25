/**
 * Opens a modal.
 *
 * @param {HTMLElement} modal - The modal element to display.
 */

export function openModal(modal) {
  if (modal) {
    modal.style.display = 'block';
  } else {
    console.error('ERROR: Modal element is undefined.');
  }
}

/**
 * Closes a modal.
 *
 * @param {HTMLElement} modal - The modal element to hide.
 */

export function closeModal(modal) {
  if (modal) {
    modal.style.display = 'none';
  } else {
    console.error('ERROR: Modal element is undefined.');
  }
}

/**
 * Closes the modal when clicking "X" or "Cancel".
 *
 */

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      closeModal(modal);
    });
  });
});

/**
 * Closes the modal when clicking outside of it.
 *
 */

document.addEventListener('click', function (event) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (event.target === modal) {
      closeModal(modal);
    }
  });
});
