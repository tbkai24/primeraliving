document.addEventListener('DOMContentLoaded', () => {
  loadLeases();

  // ========== GLOBAL ELEMENTS ==========
  const alertContainer = document.getElementById('alert-container');
  const notifyAllBtn = document.getElementById('notifyLeases');

  // ========== LOAD LEASE DATA ==========
  function loadLeases() {
    fetch('../handlers/lease_handler.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ action: 'fetch_leases' })
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') renderLeaseTable(data.data);
        else showAlert('danger', 'Failed to load leases.');
      })
      .catch(() => showAlert('danger', 'Server error while loading leases.'));
  }

  // ========== RENDER LEASE TABLE ==========
  function renderLeaseTable(leases) {
    const tbody = document.getElementById('leaseBody');
    tbody.innerHTML = '';

    if (leases.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">No active leases found.</td></tr>`;
      return;
    }

    leases.forEach(l => {
      const isDueSoon = l.days_left <= 7 && l.days_left >= 0;
      const notifyDisabled = !isDueSoon ? 'disabled' : '';
      const notifyColor = isDueSoon ? 'text-danger' : 'text-muted';

      tbody.innerHTML += `
        <tr>
          <td>${l.full_name}</td>
          <td>${l.unit}</td>
          <td>${formatDate(l.start_date)}</td>
          <td>${formatDate(l.next_due)}</td>
          <td>${l.days_left} days</td>
          <td>${Number(l.rent).toLocaleString()}</td>
          <td><span class="badge bg-${l.status === 'active' ? 'success' : 'secondary'}">${l.status}</span></td>
          <td class="text-center">
            <button class="btn btn-link p-0 notify-btn" data-id="${l.id}" ${notifyDisabled} title="Send Reminder">
              <i class="fas fa-bell ${notifyColor}" style="font-size:1rem;"></i>
            </button>
          </td>
        </tr>
      `;
    });

    attachNotifyEvents();
  }

  // ========== ATTACH NOTIFY BUTTON EVENTS ==========
  function attachNotifyEvents() {
    document.querySelectorAll('.notify-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const leaseId = btn.dataset.id;
        showConfirmAlert('Would you like to send a reminder for this lease?', () => {
          sendReminder(leaseId);
        });
      });
    });
  }

  // ========== SEND REMINDER ==========
  function sendReminder(leaseId) {
    fetch('../handlers/lease_handler.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ action: 'send_reminder', lease_id: leaseId })
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          showAlert('success', data.message || 'Reminder sent successfully!');
          loadLeases();
        } else {
          showAlert('warning', data.message || 'Reminder could not be sent.');
        }
      })
      .catch(() => showAlert('danger', 'Server error while sending reminder.'));
  }

  // ========== MANUAL BULK REMINDER BUTTON ==========
  notifyAllBtn.addEventListener('click', () => {
    showConfirmAlert('Would you like to send reminders for all leases due within 7 days?', () => {
      fetch('../handlers/lease_handler.php?cron=run')
        .then(res => res.text())
        .then(msg => {
          showAlert('info', msg);
          loadLeases();
        })
        .catch(() => showAlert('danger', 'Error sending bulk reminders.'));
    });
  });

  // ========== UTILITIES ==========
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-PH', {
      year: 'numeric', month: 'short', day: 'numeric'
    });
  }

  // ========== ALERT HELPERS ==========
  function showAlert(type, message) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alert.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.innerHTML = '';
    alertContainer.appendChild(alert);
    setTimeout(() => alert.remove(), 5000);
  }

  // ========== CONFIRMATION ALERT ==========
  function showConfirmAlert(message, onConfirm) {
    const modalHtml = `
      <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-3 shadow">
            <div class="modal-header border-0">
              <h5 class="modal-title"><i class="fas fa-exclamation-circle text-warning me-2"></i>Confirm Action</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p class="mb-0">${message}</p>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary btn-sm" id="confirmYes">Yes</button>
            </div>
          </div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modalEl = document.getElementById('confirmModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    modalEl.querySelector('#confirmYes').addEventListener('click', () => {
      modal.hide();
      modalEl.addEventListener('hidden.bs.modal', () => modalEl.remove());
      onConfirm();
    });

    modalEl.addEventListener('hidden.bs.modal', () => modalEl.remove());
  }
});
