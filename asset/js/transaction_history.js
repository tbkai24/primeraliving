// transaction_history.js

document.addEventListener('DOMContentLoaded', () => {
    const transactionTableBody = document.querySelector('#transactionTable tbody');
    const refreshBtn = document.getElementById('refreshBtn');
    const requestPdfForm = document.getElementById('requestPdfForm');

    // Create alert container dynamically
    const alertContainer = document.createElement('div');
    alertContainer.id = 'alertContainer';
    alertContainer.style.transition = 'min-height 0.3s ease';
    alertContainer.style.minHeight = '0';
    alertContainer.style.overflow = 'hidden';

    // Insert alert container above table
    const tableCard = document.querySelector('.card-body');
    tableCard.prepend(alertContainer);

    // --------------------------
    // Show Bootstrap alert (no layout jump)
    // --------------------------
    function showAlert(message, type = 'success') {
        const color = type === 'success' ? 'success' : 'danger';
        const alert = document.createElement('div');
        alert.className = `alert alert-${color} alert-dismissible fade show text-center fw-semibold`;
        alert.role = 'alert';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Clear any previous alert
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alert);

        // Lock container height to prevent jump
        alertContainer.style.minHeight = alert.offsetHeight + 'px';

        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => {
                alert.remove();
                alertContainer.style.minHeight = '0';
            }, 300);
        }, 3000);
    }

    // --------------------------
    // Fetch and render transactions
    // --------------------------
    async function fetchTransactions() {
        try {
            const res = await fetch('../handlers/TransactionHandler.php?action=get_transactions');
            const data = await res.json();

            if (!data.status || !Array.isArray(data.data)) {
                transactionTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Failed to load transactions.</td></tr>`;
                return;
            }

            const transactions = data.data;

            if (transactions.length === 0) {
                transactionTableBody.innerHTML = `<tr><td colspan="8" class="text-center">No transactions found.</td></tr>`;
                return;
            }

            transactionTableBody.innerHTML = '';
            transactions.forEach((trx, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${trx.invoice_id ?? '-'}</td>
                    <td>${trx.property ?? '-'}</td>
                    <td>${trx.description ?? '-'}</td>
                    <td>₱${Number(trx.amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>
                        <span class="badge ${trx.status === 'Paid' ? 'bg-success' : trx.status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger'}">
                            ${trx.status}
                        </span>
                    </td>
                    <td>${trx.method ?? '-'}</td>
                    <td>${new Date(trx.created_at).toISOString().split('T')[0]}</td>
                `;
                transactionTableBody.appendChild(row);
            });

        } catch (err) {
            console.error("Error fetching transactions:", err);
            transactionTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Failed to load transactions.</td></tr>`;
        }
    }

    // --------------------------
    // Refresh button
    // --------------------------
    if (refreshBtn) {
        refreshBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fetchTransactions();
        });
    }

    // --------------------------
    // Request PDF form submission
    // --------------------------
    if (requestPdfForm) {
        requestPdfForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(requestPdfForm);

            try {
                const res = await fetch('../handlers/TransactionHandler.php?action=request_pdf', {
                    method: 'POST',
                    body: formData
                });

                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch {
                    console.error("Invalid JSON response:", text);
                    showAlert("Server error occurred while requesting PDF.", "danger");
                    return;
                }

                if (data.success) {
                    showAlert(" PDF successfully sent to your email!", "success");
                    requestPdfForm.reset();
                } else {
                    showAlert(`⚠️ ${data.message}`, "danger");
                }

            } catch (err) {
                console.error("Error requesting PDF:", err);
                showAlert('Failed to request PDF. Please try again.', 'danger');
            }
        });
    }

    // Initial fetch
    fetchTransactions();
});
