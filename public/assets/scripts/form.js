/**
 * Live password strength meter and requirement checklist.
 */

document.addEventListener('DOMContentLoaded', function () {
  const passwordInput = document.querySelector('#password');
  const meter = document.querySelector('#password-strength-meter');
  const bar = document.querySelector('#password-strength-bar');
  const checklist = document.querySelector('#password-checklist');

  if (!passwordInput || !meter || !bar || !checklist) return;

  passwordInput.addEventListener('input', function () {
    const password = passwordInput.value;
    const lengthCheck = password.length >= 12;
    const uppercaseCheck = /[A-Z]/.test(password);
    const lowercaseCheck = /[a-z]/.test(password);
    const numberCheck = /[0-9]/.test(password);
    const specialCheck = /[^A-Za-z0-9\s]/.test(password);

    // Show meter and checklist after user starts typing
    if (password.length > 0) {
      meter.classList.remove('d-none');
      checklist.classList.remove('d-none');
    } else {
      meter.classList.add('d-none');
      checklist.classList.add('d-none');
    }

    // Update checklist visually
    toggleChecklist('check-length', lengthCheck);
    toggleChecklist('check-uppercase', uppercaseCheck);
    toggleChecklist('check-lowercase', lowercaseCheck);
    toggleChecklist('check-number', numberCheck);
    toggleChecklist('check-special', specialCheck);

    // Determine strength level
    const score = [lengthCheck, uppercaseCheck, lowercaseCheck, numberCheck, specialCheck].filter(Boolean).length;

    bar.style.width = `${(score / 5) * 100}%`;

    if (score <= 2) {
      bar.className = 'progress-bar bg-danger';
      bar.setAttribute('aria-valuenow', '33');
    } else if (score === 3 || score === 4) {
      bar.className = 'progress-bar bg-warning';
      bar.setAttribute('aria-valuenow', '66');
    } else if (score === 5) {
      bar.className = 'progress-bar bg-success';
      bar.setAttribute('aria-valuenow', '100');
    }
  });

  /**
   * Toggles checklist item class based on pass/fail
   *
   * @param {string} id - The checklist item's ID.
   * @param {boolean} passed - Whether the rule passed.
   */
  function toggleChecklist(id, passed) {
    const item = document.getElementById(id);
    if (!item) return;
    item.classList.toggle('text-success', passed);
    item.classList.toggle('text-danger', !passed);
  }

  const confirmInput = document.querySelector('#confirm_password');
  const confirmFeedback = document.querySelector('#confirm-password-feedback');

  if (confirmInput && confirmFeedback) {
    confirmInput.addEventListener('input', validateMatch);
    passwordInput.addEventListener('input', validateMatch);
  }

  /**
   * Checks if password and confirm password match and updates UI.
   */
  function validateMatch() {
    const password = passwordInput.value;
    const confirm = confirmInput.value;

    if (confirm.length === 0) {
      confirmFeedback.classList.add('d-none');
      confirmInput.classList.remove('is-invalid', 'is-valid');
      return;
    }

    confirmFeedback.classList.remove('d-none');

    if (password === confirm) {
      confirmFeedback.textContent = '✅ Passwords match!';
      confirmFeedback.classList.remove('text-danger');
      confirmFeedback.classList.add('text-success');
      confirmInput.classList.remove('is-invalid');
      confirmInput.classList.add('is-valid');
    } else {
      confirmFeedback.textContent = '❌ Passwords do not match.';
      confirmFeedback.classList.remove('text-success');
      confirmFeedback.classList.add('text-danger');
      confirmInput.classList.remove('is-valid');
      confirmInput.classList.add('is-invalid');
    }
  }
});
