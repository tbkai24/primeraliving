// ------------------ SETTINGS NAVIGATION ------------------
document.querySelectorAll('.list-group-item-action').forEach(item => {
  item.addEventListener('click', e => {
    e.preventDefault();

    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(sec => sec.style.display = 'none');

    // Remove active class from all links
    document.querySelectorAll('.list-group-item').forEach(link => link.classList.remove('active'));

    // Activate clicked item
    item.classList.add('active');

    // Show corresponding section
    const sectionId = item.getAttribute('data-section') + '-section';
    const section = document.getElementById(sectionId);
    if(section) section.style.display = 'block';
  });
});

// ------------------ GENERIC AJAX FOR SETTINGS FORMS ------------------
const forms = document.querySelectorAll('form');
forms.forEach(form => {
  form.addEventListener('submit', async e => {
    e.preventDefault();

    const formData = new FormData(form);

    // Handle unchecked checkboxes
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
      if (!cb.checked) formData.set(cb.name, 0);
    });

    formData.append('action', form.id);

    try {
      const res = await fetch('../handlers/settings_handler.php', { method: 'POST', body: formData });
      const data = await res.json();
      showAlert(data.status, data.message);
    } catch (err) {
      showAlert('error', 'Something went wrong. Please try again.');
      console.error(err);
    }
  });
});

// ------------------ ALERT FUNCTION ------------------
function showAlert(type, message) {
  const alertBox = document.getElementById('settingsAlert');
  if (!alertBox) return;

  const color = type === 'success' ? 'success' : 'danger';

  alertBox.innerHTML = `
    <div class="alert alert-${color} alert-dismissible fade show text-center fw-semibold" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `;

  const alertElement = alertBox.querySelector('.alert');
  if (alertElement) {
    setTimeout(() => {
      alertElement.classList.remove('show');
      alertElement.classList.add('hide');
      setTimeout(() => alertElement.remove(), 500);
    }, 3000);
  }
}
