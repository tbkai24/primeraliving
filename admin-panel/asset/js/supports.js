document.addEventListener("DOMContentLoaded", () => {

    function showAlert(message, type="success") {
        const alertBox = document.getElementById("alertBox");
        if(!alertBox) return;
        alertBox.innerHTML = `
            <div class="alert alert-${type} alert-dismissible" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // ====== Load tickets list with filters ======
    async function loadTickets(){
        const tbody = document.querySelector("#ticketsTable tbody");
        if(!tbody) return;

        const keyword = document.getElementById("filterKeyword")?.value || '';
        const status = document.getElementById("filterStatus")?.value || '';
        const category = document.getElementById("filterCategory")?.value || '';

        const params = new URLSearchParams({
            action: "list",
            keyword,
            status,
            category
        });

        try {
            const res = await fetch(`../handlers/support_handler.php?${params.toString()}`);
            const tickets = await res.json();
            tbody.innerHTML = "";

            if(tickets.length === 0){
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No tickets found.</td></tr>`;
                return;
            }

            tickets.forEach(ticket => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${ticket.ticket_id}</td>
                    <td>${ticket.first_name} ${ticket.last_name}</td>
                    <td>${ticket.subject}</td>
                    <td>
                        <span class="badge bg-${ticket.status==='Pending'?'warning text-dark':'success'}">${ticket.status}</span>
                    </td>
                    <td>${ticket.created_at}</td>
                    <td>
                        <a href="ticket_view.php?id=${ticket.ticket_id}" class="btn btn-sm btn-primary me-1">View</a>
                        <button class="btn btn-sm btn-danger delete-ticket" data-id="${ticket.ticket_id}">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Delete button events
            document.querySelectorAll(".delete-ticket").forEach(btn => {
                btn.addEventListener("click", async () => {
                    if(!confirm("Are you sure you want to delete this ticket?")) return;
                    const ticket_id = btn.dataset.id;
                    try {
                        const res = await fetch("../handlers/support_handler.php", {
                            method: "POST",
                            body: new URLSearchParams({ action: "delete", ticket_id })
                        });
                        const data = await res.json();
                        if(data.success){
                            showAlert("Ticket deleted successfully!", "success");
                            loadTickets();
                        } else {
                            showAlert(data.message || "Failed to delete ticket.", "danger");
                        }
                    } catch(e){
                        showAlert("Error deleting ticket.", "danger");
                    }
                });
            });

        } catch(e){
            showAlert("Failed to load tickets.", "danger");
        }
    }

    if(window.location.href.includes("supports.php")){
        loadTickets();

        // Filters
        document.getElementById("filterKeyword")?.addEventListener("input", loadTickets);
        document.getElementById("filterStatus")?.addEventListener("change", loadTickets);
        document.getElementById("filterCategory")?.addEventListener("change", loadTickets);

        // Reset filters
        document.getElementById("resetFilters")?.addEventListener("click", () => {
            document.getElementById("filterKeyword").value = '';
            document.getElementById("filterStatus").value = '';
            document.getElementById("filterCategory").value = '';
            loadTickets();
        });
    }

    // ====== Ticket replies and messages (ticket_view.php) ======
    const replyForm = document.getElementById("replyForm");
    if(replyForm){
        replyForm.addEventListener("submit", e => {
            e.preventDefault();
            const formData = new FormData(replyForm);
            fetch("../handlers/support_handler.php", { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    showAlert("Reply sent!", "success");
                    replyForm.reset();
                    loadMessages(formData.get("ticket_id"));
                } else showAlert(data.message || "Failed to send reply.", "danger");
            })
            .catch(err => showAlert("An error occurred.", "danger"));
        });
    }

    async function loadMessages(ticket_id){
        const messagesDiv = document.getElementById("messages");
        if(!messagesDiv) return;
        try {
            const res = await fetch(`../handlers/support_handler.php?action=messages&ticket_id=${ticket_id}`);
            const data = await res.json();
            messagesDiv.innerHTML = "";
            if(data.length === 0){
                messagesDiv.innerHTML = `<div class="text-center text-muted">No messages yet.</div>`;
                return;
            }
            data.forEach(msg => {
                const div = document.createElement("div");
                div.className = "mb-2";
                div.innerHTML = `<strong>${msg.sender_name}</strong> <small class="text-muted">${msg.created_at}</small><p>${msg.message}</p>`;
                messagesDiv.appendChild(div);
            });
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } catch(e){
            showAlert("Failed to load messages.", "danger");
        }
    }

    if(window.location.href.includes("ticket_view.php")){
        const ticket_id = document.getElementById("replyForm")?.ticket_id?.value;
        if(ticket_id) loadMessages(ticket_id);

        // Update status
        const statusForm = document.getElementById("statusForm");
        statusForm?.addEventListener("submit", e => {
            e.preventDefault();
            const ticket_id = document.querySelector('input[name="ticket_id"]').value;
            const status = document.getElementById("statusSelect").value;
            fetch("../handlers/support_handler.php", {
                method: "POST",
                body: new URLSearchParams({ action: "update_status", ticket_id, status })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) showAlert("Status updated successfully!", "success");
                else showAlert(data.message || "Failed to update status.", "danger");
            })
            .catch(err => showAlert("An error occurred.", "danger"));
        });
    }

});
