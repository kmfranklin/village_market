/**
 * @file dashboard.js
 *
 * Handles the vendor dashboard calendar preview.
 * Loads and displays read-only market date selections.
 */

import flatpickr from 'flatpickr';

/**
 * Initializes the vendor calendar using Flatpickr.
 * Disables date selection and highlights selected dates.
 */
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('vendor-calendar');
  const calendarDataEl = document.getElementById('vendor-calendar-dates');

  if (calendarEl && calendarDataEl) {
    let selectedDates = [];

    try {
      selectedDates = JSON.parse(calendarDataEl.textContent);
    } catch (e) {
      console.error('Invalid vendor dates:', e);
      return;
    }

    const selectedDateStrings = selectedDates.map(date => flatpickr.formatDate(new Date(date), 'Y-m-d'));

    flatpickr(calendarEl, {
      inline: true,
      clickOpens: false,
      allowInput: false,
      disableMobile: true,
      disable: [() => true],
      onChange: () => false,

      /**
       * Adds a custom class to the calendar container once Flatpickr is initialized.
       *
       * @param {Date[]} selectedDates
       * @param {string} dateStr
       * @param {object} instance - Flatpickr instance
       */
      onReady: (selectedDates, dateStr, instance) => {
        instance.calendarContainer.classList.add('readonly-calendar');
      },

      /**
       * Customizes each day element in the Flatpickr calendar.
       *
       * This hook is triggered for each day as it is created.
       * The first three parameters are ignored using placeholders
       * since only the day element is needed for styling.
       *
       * If the day matches a vendor-selected date, it is marked as selected,
       * disabled from interaction, and given appropriate ARIA attributes.
       *
       * @param {*} _ - Unused: Flatpickr date object
       * @param {*} __ - Unused: Current date string
       * @param {*} ___ - Unused: Flatpickr instance
       * @param {HTMLElement} dayElement - The day cell element to be modified
       */
      onDayCreate: function (_, __, ___, dayElement) {
        const date = dayElement.dateObj;
        const formatted = flatpickr.formatDate(date, 'Y-m-d');

        if (selectedDateStrings.includes(formatted)) {
          dayElement.classList.add('flatpickr-day', 'selected');
          dayElement.setAttribute('aria-disabled', 'true');
          dayElement.classList.add('flatpickr-disabled');
        }
      },
    });
  }

  const reminderCloseBtn = document.querySelector('[data-bs-dismiss="alert"]');

  /**
   * Store dismissal of attendance reminder in a cookie.
   */
  if (reminderCloseBtn) {
    reminderCloseBtn.addEventListener('click', function () {
      const expires = new Date();
      expires.setDate(expires.getDate() + 7);
      document.cookie = `attendance_reminder_dismissed=true; expires=${expires.toUTCString()}; path=/`;
    });
  }
});
