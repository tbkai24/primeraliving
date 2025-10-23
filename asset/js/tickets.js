document.addEventListener('DOMContentLoaded', () => {

  // ================= LOAD USER TICKETS (tickets.php) =================
  const ticketsTable = document.getElementById('ticketsTable');
  if(ticketsTable){
    loadTickets();
  }

  // ================= LOAD SINGLE TICKET + MESSAGES (ticket-view.php) =================
  if(typeof ticketId !== 'undefined' && ticketId > 0){
    loadTicket(ticketId);
    const replyForm = document.getElementById('replyForm');
    if(replyForm){
      replyForm.addEventListener('submit', function(e){
        e.preventDefault();
        sendReply(ticketId);
      });
    }
  }

  // ================= CREATE TICKET SUBMISSION (create-ticket.php) =================
  const createTicketForm = document.getElementById('createTicketForm');
  if(createTicketForm){
    createTicketForm.addEventListener('submit', function(e){
      e.preventDefault();
      
      const formData = new FormData(createTicketForm);

      fetch('../../handlers/tickets_handler.php?action=createTicket', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        const alertBox = document.getElementById('alertBox');
        if(alertBox){
          alertBox.style.display = 'block';
          const alertDiv = alertBox.querySelector('.alert');
          if(alertDiv){
            alertDiv.className = 'alert mb-0 ' + (data.status === 'success' ? 'alert-success' : 'alert-danger');
            alertDiv.innerText = data.status === 'success' 
              ? 'Ticket created successfully!' 
              : (data.message || 'Failed to create ticket');

            // Automatically hide success alert after 3 seconds
            if(data.status === 'success'){
              setTimeout(() => {
                alertBox.style.display = 'none';
              }, 3000);
            }
          }
        }

        if(data.status === 'success') createTicketForm.reset();
      })
      .catch(err => console.error('Error creating ticket:', err));
    });
  }

});

// ================= LOAD USER TICKETS =================
function loadTickets(){
  fetch('../../handlers/tickets_handler.php?action=getUserTickets')
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#ticketsTable tbody');
      if(!tbody) return;

      tbody.innerHTML = '';
      if(data.length === 0){
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No tickets found</td></tr>`;
        return;
      }

      data.forEach((ticket, i) => {
        tbody.innerHTML += `
          <tr>
            <td>${i+1}</td>
            <td>${ticket.category}</td>
            <td>${ticket.subject}</td>
            <td>${ticket.status}</td>
            <td>${ticket.created_at}</td>
            <td>
              <a href="ticket_view.php?ticket_id=${ticket.ticket_id}" class="btn btn-sm btn-outline-primary me-1">
                <i class="fas fa-eye"></i>
              </a>
              ${ticket.status === 'Pending' ? `<button class="btn btn-sm btn-outline-danger" onclick="deleteTicket(${ticket.ticket_id})">
                <i class="fas fa-trash"></i>
              </button>` : ''}
            </td>
          </tr>
        `;
      });
    })
    .catch(err => console.error('Error loading tickets:', err));
}

// ================= DELETE TICKET =================
function deleteTicket(ticketId){
  if(!confirm('Are you sure you want to delete this ticket?')) return;

  const formData = new FormData();
  formData.append('action', 'deleteTicket');
  formData.append('ticket_id', ticketId);

  fetch('../../handlers/tickets_handler.php', { method:'POST', body:formData })
    .then(res => res.json())
    .then(data => {
      if(data.status === 'success'){
        loadTickets();
        alert('Ticket deleted successfully');
      } else alert('Failed to delete ticket');
    })
    .catch(err => console.error('Error deleting ticket:', err));
}

// ================= LOAD SINGLE TICKET =================
function loadTicket(ticketId){
  fetch(`../../handlers/tickets_handler.php?action=getTicket&ticket_id=${ticketId}`)
    .then(res => res.json())
    .then(data => {
      const ticketInfoDiv = document.getElementById('ticketInfo');
      const messagesDiv = document.getElementById('ticketMessages');

      if(data.status !== 'success'){
        if(ticketInfoDiv) ticketInfoDiv.innerHTML = `<p class="text-center text-danger">${data.message}</p>`;
        return;
      }

      const ticket = data.ticket;
      const messages = data.messages;

      // Ticket Info
      if(ticketInfoDiv){
        ticketInfoDiv.innerHTML = `
          <p><strong>Subject:</strong> ${ticket.subject}</p>
          <p><strong>Category:</strong> ${ticket.category}</p>
          <p><strong>Status:</strong> ${ticket.status}</p>
          <p><strong>Created At:</strong> ${ticket.created_at}</p>
        `;
      }

      // Messages
      if(messagesDiv){
        messagesDiv.innerHTML = '';
        if(messages.length === 0){
          messagesDiv.innerHTML = `<p class="text-center text-muted">No messages yet.</p>`;
        } else {
          messages.forEach(msg => {
            const msgClass = msg.sender_type === 'user' ? 'alert-primary' : 'alert-secondary';
            messagesDiv.innerHTML += `
              <div class="alert ${msgClass} py-2 mb-2">
                <strong>${msg.sender_name}:</strong> ${msg.message} <br>
                <small class="text-muted">${msg.created_at}</small>
              </div>
            `;
          });
        }
      }

      // Show reply form only if ticket is pending
      const replyForm = document.getElementById('replyForm');
      if(replyForm) replyForm.style.display = ticket.status === 'Pending' ? 'block' : 'none';

    })
    .catch(err => console.error('Error loading ticket:', err));
}

// ================= SEND REPLY =================
function sendReply(ticketId){
  const replyMessage = document.getElementById('replyMessage');
  if(!replyMessage) return;

  const message = replyMessage.value.trim();
  if(!message) return alert('Message cannot be empty');

  const formData = new FormData();
  formData.append('action','addReply');
  formData.append('ticket_id',ticketId);
  formData.append('message',message);

  fetch('../../handlers/tickets_handler.php',{method:'POST',body:formData})
    .then(res => res.json())
    .then(data => {
      if(data.status === 'success'){
        replyMessage.value = '';
        loadTicket(ticketId);
      } else alert('Failed to send reply');
    })
    .catch(err => console.error('Error sending reply:', err));
}
