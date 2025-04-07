import flatpickr from 'flatpickr';

document.addEventListener('DOMContentLoaded', function () {
  // Flatpickr Attendance Calendar + Checkbox Sync
  const calendarEl = document.getElementById('market-calendar');
  const checkboxes = document.querySelectorAll('input[type="checkbox"][name="market_dates[]"]');

  /**
   * Format a JS Date object to YYYY-MM-DD (local time)
   *
   * @param {Date} date
   * @returns {string}
   */
  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  if (calendarEl && checkboxes.length > 0) {
    const selectedDates = Array.from(checkboxes)
      .filter(cb => cb.checked)
      .map(cb => cb.dataset.date);

    let suppressCheckboxSync = false;

    const fp = flatpickr(calendarEl, {
      mode: 'multiple',
      dateFormat: 'Y-m-d',
      inline: true,
      enable: [date => date.getDay() === 6], // Saturdays only
      defaultDate: selectedDates,

      /**
       * Sync calendar selection → checkboxes
       */
      onChange: function (selectedDates) {
        if (suppressCheckboxSync) return;

        const selectedStrings = selectedDates.map(formatDate);

        checkboxes.forEach(checkbox => {
          const checkboxDate = checkbox.dataset.date;
          checkbox.checked = selectedStrings.includes(checkboxDate);
        });
      },
    });

    /**
     * Sync checkbox changes → calendar selections
     */
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const checkboxDate = this.dataset.date;
        const date = new Date(checkboxDate);

        const targetMonth = date.getMonth();
        const targetYear = date.getFullYear();

        const calendarMonth = fp.currentMonth;
        const calendarYear = fp.currentYear;

        const currentDates = fp.selectedDates.map(formatDate);
        let updatedDates = [...currentDates];

        if (this.checked && !currentDates.includes(checkboxDate)) {
          updatedDates.push(checkboxDate);
        } else if (!this.checked && currentDates.includes(checkboxDate)) {
          updatedDates = updatedDates.filter(date => date !== checkboxDate);
        }

        const shouldJump = this.checked && (targetMonth !== calendarMonth || targetYear !== calendarYear);

        suppressCheckboxSync = true;

        // Save current view in case we need to restore it
        const currentViewDate = new Date(calendarYear, calendarMonth, 1);

        fp.setDate(updatedDates, false); // <- this might still jump

        if (shouldJump) {
          console.log('Jumping to:', date);
          fp.jumpToDate(date);
        } else {
          // Cancel Flatpickr's auto-jump
          console.log('Restoring view:', currentViewDate);
          fp.jumpToDate(currentViewDate);
        }

        suppressCheckboxSync = false;
      });
    });
  }

  // Password Strength Meter
  const passwordInput = document.querySelector('#password');
  const meter = document.querySelector('#password-strength-meter');
  const bar = document.querySelector('#password-strength-bar');
  const checklist = document.querySelector('#password-checklist');

  function toggleChecklist(id, passed) {
    const item = document.getElementById(id);
    if (item) {
      item.classList.toggle('text-success', passed);
      item.classList.toggle('text-danger', !passed);
    }
  }

  if (passwordInput && meter && bar && checklist) {
    passwordInput.addEventListener('input', function () {
      const password = passwordInput.value;
      const lengthCheck = password.length >= 12;
      const uppercaseCheck = /[A-Z]/.test(password);
      const lowercaseCheck = /[a-z]/.test(password);
      const numberCheck = /[0-9]/.test(password);
      const specialCheck = /[^A-Za-z0-9\s]/.test(password);

      const score = [lengthCheck, uppercaseCheck, lowercaseCheck, numberCheck, specialCheck].filter(Boolean).length;
      bar.style.width = `${(score / 5) * 100}%`;

      if (score <= 2) {
        bar.className = 'progress-bar bg-danger';
        bar.setAttribute('aria-valuenow', '33');
      } else if (score <= 4) {
        bar.className = 'progress-bar bg-warning';
        bar.setAttribute('aria-valuenow', '66');
      } else {
        bar.className = 'progress-bar bg-success';
        bar.setAttribute('aria-valuenow', '100');
      }

      meter.classList.toggle('d-none', password.length === 0);
      checklist.classList.toggle('d-none', password.length === 0);

      toggleChecklist('check-length', lengthCheck);
      toggleChecklist('check-uppercase', uppercaseCheck);
      toggleChecklist('check-lowercase', lowercaseCheck);
      toggleChecklist('check-number', numberCheck);
      toggleChecklist('check-special', specialCheck);
    });
  }

  // Confirm Password Checker
  const confirmInput = document.querySelector('#confirm_password');
  const confirmFeedback = document.querySelector('#confirm-password-feedback');

  function validateMatch() {
    if (!passwordInput || !confirmInput || !confirmFeedback) return;

    const password = passwordInput.value;
    const confirm = confirmInput.value;

    confirmFeedback.classList.toggle('d-none', confirm.length === 0);

    if (password === confirm) {
      confirmFeedback.textContent = '✅ Passwords match!';
      confirmFeedback.classList.remove('text-danger');
      confirmFeedback.classList.add('text-success');
      confirmInput.classList.remove('is-invalid');
      confirmInput.classList.add('is-valid');
    } else {
      confirmFeedback.textContent = '❌ Passwords do not match.';
      confirmFeedback.classList.add('text-danger');
      confirmFeedback.classList.remove('text-success');
      confirmInput.classList.add('is-invalid');
      confirmInput.classList.remove('is-valid');
    }
  }

  if (confirmInput && confirmFeedback && passwordInput) {
    confirmInput.addEventListener('input', validateMatch);
    passwordInput.addEventListener('input', validateMatch);
  }
});
