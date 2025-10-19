document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#ticketsTable tbody');
    const createForm = document.querySelector('#createTicketForm');
    const createModal = document.querySelector('#createTicketModal');

    // ===== Bootstrap Alert Helper =====
    function showAlert(message, type = 'success') {
        const alertBox = document.createElement('div');
        alertBox.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow`;
        alertBox.style.zIndex = '2000';
        alertBox.innerHTML = `
            <strong>${type === 'success' ? '✔' : '❌'} </strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertBox);
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertBox);
            alert.close();
        }, 3000);
    }

    // ===== Load Tickets =====
    function loadTickets() {
        fetch('../../handlers/ticketshandler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'fetch' })
        })
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    renderTickets(data);
                } catch (err) {
                    console.error("Error parsing JSON:", err, text);
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-muted text-center py-4">
                                <i class="fa fa-exclamation-circle me-2"></i>
                                Failed to load tickets. Please try again.
                            </td>
                        </tr>`;
                }
            })
            .catch(err => {
                console.error("Error loading tickets:", err);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-muted text-center py-4">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            Unable to connect to server.
                        </td>
                    </tr>`;
            });
    }

    // ===== Render Tickets =====
    function renderTickets(tickets) {
        tableBody.innerHTML = '';

        if (!Array.isArray(tickets) || tickets.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-muted text-center py-4">
                        <i class="fa fa-ticket-alt me-2"></i> No tickets found.
                    </td>
                </tr>`;
            return;
        }

        tickets.forEach(ticket => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${ticket.ticket_number || '—'}</td>
                <td>${ticket.category}</td>
                <td>${ticket.subject}</td>
                <td>
                    <span class="badge ${getStatusClass(ticket.status)}">${ticket.status}</span>
                </td>
                <td>${formatDate(ticket.created_at)}</td>
                <td>
                    <a href="view-ticket.php?ticket_id=${ticket.ticket_id}" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-eye"></i> View
                    </a>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // ===== Create Ticket =====
    createForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(createForm);
        formData.append('action', 'create');

        fetch('../../handlers/ticketshandler.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(`Ticket Created Successfully! Ticket No: ${data.ticket_number}`, 'success');
                    const modal = bootstrap.Modal.getInstance(createModal);
                    modal.hide();
                    createForm.reset();
                    loadTickets();
                } else {
                    showAlert('Failed to create ticket: ' + (data.error || 'Unknown error'), 'danger');
                }
            })
            .catch(err => {
                console.error("Error creating ticket:", err);
                showAlert('Server error. Please try again later.', 'danger');
            });
    });

    // ===== Helper: Format Date =====
    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const d = new Date(dateStr);
        return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // ===== Helper: Status Badge =====
    function getStatusClass(status) {
        status = (status || '').toLowerCase();
        if (status === 'pending') return 'bg-warning text-dark'; // yellow
        if (status === 'closed') return 'bg-success'; // green (means resolved)
        return 'bg-secondary';
    }

    // ===== Initial Load =====
    loadTickets();
});
