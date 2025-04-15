import flatpickr from 'flatpickr';

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('vendor-calendar');
  const calendarDataEl = document.getElementById('vendor-calendar-dates');

  if (!calendarEl || !calendarDataEl) return;

  let selectedDates = [];

  try {
    selectedDates = JSON.parse(calendarDataEl.textContent);
  } catch (e) {
    console.error('Invalid vendor dates:', e);
    return;
  }

  // Ensure proper formatting for match
  const selectedDateStrings = selectedDates.map(date => flatpickr.formatDate(new Date(date), 'Y-m-d'));

  flatpickr(calendarEl, {
    inline: true,
    clickOpens: false,
    allowInput: false,
    disableMobile: true,
    disable: [() => true], // disable all clicks

    onChange: () => false, // prevent toggling

    onReady: (selectedDates, dateStr, instance) => {
      instance.calendarContainer.classList.add('readonly-calendar');
    },

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
});
