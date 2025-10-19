document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("table tbody");
  const alertContainer = document.getElementById("alert-container");
  const viewModal = new bootstrap.Modal(document.getElementById("viewTransactionModal"));
  const toggleArchivedBtn = document.getElementById("toggleArchived");
  const fromDateInput = document.getElementById("fromDate");
  const toDateInput = document.getElementById("toDate");
  const resetFiltersBtn = document.getElementById("resetFilters");

  let showArchived = false;
  let transactions = [];

  // ------------------ ALERT ------------------
  function showAlert(message, type = "success") {
    alertContainer.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `;
  }

  // ------------------ FETCH TRANSACTIONS ------------------
  async function fetchTransactions() {
    try {
      const fd = new FormData();
      fd.append("action", "read");
      const res = await fetch("../handlers/transaction_handler.php", { method: "POST", body: fd });
      const data = await res.json();
      if (!data.success) return showAlert(data.message, "danger");

      // Only completed and archived transactions
      transactions = data.data.filter(t => t.status === "Completed" || t.status === "Archived");
      renderTable();
      updateArchivedCount();
    } catch (err) {
      console.error(err);
      showAlert("Failed to fetch transactions.", "danger");
    }
  }

  // ------------------ RENDER TABLE ------------------
  function renderTable() {
  tableBody.innerHTML = "";

  // Filter by archived
  let filtered = showArchived
    ? transactions.filter(t => t.status === "Archived")
    : transactions.filter(t => t.status === "Completed");

  // Filter by date range
  const from = fromDateInput.value ? new Date(fromDateInput.value) : null;
  const to = toDateInput.value ? new Date(toDateInput.value) : null;

  if (from || to) {
    filtered = filtered.filter(t => {
      const created = new Date(t.created_at);
      if (from && created < from) return false;
      if (to && created > to) return false;
      return true;
    });
  }

  // --------- Show message if no data ---------
  if (filtered.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="8" class="text-center text-muted">No transactions found.</td>
      </tr>
    `;
    return;
  }

  // Render rows normally
  filtered.forEach(t => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${t.id}</td>
      <td>${t.description || ""}</td>
      <td>${t.user_id}</td>
      <td>₱${parseFloat(t.amount).toLocaleString("en-PH", {minimumFractionDigits:2})}</td>
      <td><span class="badge bg-${t.status === "Completed" ? "success" : "secondary"}">${t.status}</span></td>
      <td>${t.method || ""}</td>
      <td>${t.created_at}</td>
      <td class="d-flex gap-1">
        <button class="btn btn-sm btn-primary btn-view" data-id="${t.id}" title="View">
          <i class="fas fa-eye"></i>
        </button>
        ${t.status !== "Archived" ? `
        <button class="btn btn-sm btn-secondary btn-archive" data-id="${t.id}" title="Archive">
          <i class="fas fa-archive"></i>
        </button>` : ""}
      </td>
    `;
    tableBody.appendChild(tr);
  });

  bindButtons();
}
  // ------------------ BIND BUTTONS ------------------
  function bindButtons() {
    document.querySelectorAll(".btn-view").forEach(btn => {
      btn.addEventListener("click", async () => {
        const id = btn.dataset.id;
        const fd = new FormData();
        fd.append("action", "get");
        fd.append("id", id);
        const res = await fetch("../handlers/transaction_handler.php", { method: "POST", body: fd });
        const data = await res.json();
        if (!data.success) return showAlert(data.message, "danger");

        const t = data.data;
        document.getElementById("t_id").textContent = t.id;
        document.getElementById("t_user").textContent = t.user_id;
        document.getElementById("t_email").textContent = t.tenant_email || "";
        document.getElementById("t_desc").textContent = t.description || "";
        document.getElementById("t_amount").textContent = parseFloat(t.amount).toLocaleString("en-PH",{minimumFractionDigits:2});
        document.getElementById("t_status").textContent = t.status;
        document.getElementById("t_method").textContent = t.method || "";
        document.getElementById("t_property").textContent = t.property || "";
        document.getElementById("t_created").textContent = t.created_at;
        viewModal.show();
      });
    });

    document.querySelectorAll(".btn-archive").forEach(btn => {
      btn.addEventListener("click", async () => {
        const id = btn.dataset.id;
        if (!confirm("Archive this transaction?")) return;

        const fd = new FormData();
        fd.append("action", "archive");
        fd.append("id", id);
        const res = await fetch("../handlers/transaction_handler.php", { method: "POST", body: fd });
        const data = await res.json();
        showAlert(data.message, data.success ? "success" : "danger");
        if (data.success) fetchTransactions();
      });
    });
  }

  // ------------------ TOGGLE ARCHIVED ------------------
  toggleArchivedBtn.addEventListener("click", () => {
    showArchived = !showArchived;
    const count = transactions.filter(t => t.status === "Archived").length;
    toggleArchivedBtn.innerHTML = showArchived
      ? `<i class="fas fa-archive me-1"></i> Hide Archived <span id="archivedCount" class="badge bg-light text-dark ms-1">${count}</span>`
      : `<i class="fas fa-archive me-1"></i> Show Archived <span id="archivedCount" class="badge bg-light text-dark ms-1">${count}</span>`;
    renderTable();
  });

  // ------------------ RESET FILTERS ------------------
  resetFiltersBtn.addEventListener("click", () => {
    fromDateInput.value = "";
    toDateInput.value = "";
    renderTable();
  });

  // ------------------ UPDATE ARCHIVED COUNT ------------------
  function updateArchivedCount() {
    const count = transactions.filter(t => t.status === "Archived").length;
    const badge = document.getElementById("archivedCount");
    if (badge) badge.textContent = count;
  }

  // INITIAL FETCH
  fetchTransactions();
});
