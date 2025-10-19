document.addEventListener("DOMContentLoaded", function() {
  const tableBody = document.querySelector('table tbody');
  const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
  const editForm = document.getElementById('editUserForm');
  const alertContainer = document.getElementById('alert-container');

  // Filter inputs
  const searchInput = document.getElementById('userSearch');
  const statusFilter = document.getElementById('statusFilter');
  const fromDate = document.getElementById('fromDate');
  const toDate = document.getElementById('toDate');
  const resetFilters = document.getElementById('resetFilters');

  // ------------------ Helper: show alert ------------------
  function showAlert(message, success = true) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${success ? 'success' : 'danger'} alert-dismissible fade show`;
    alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    alertContainer.appendChild(alert);
    setTimeout(() => alert.remove(), 4000);
  }

  // ------------------ Helper: fetch JSON ------------------
  async function postData(formData) {
    try {
      const res = await fetch('../handlers/user_handler.php', {
        method: 'POST',
        body: formData
      });
      return await res.json();
    } catch (err) {
      console.error(err);
      return { success: false, message: 'Server error.' };
    }
  }

  // ------------------ Load Users ------------------
  async function loadUsers(filters = {}) {
    const fd = new FormData();
    fd.append('action', 'read');

    // Add filters
    for (let key in filters) {
      fd.append(key, filters[key]);
    }

    const res = await postData(fd);
    if(res.success){
      tableBody.innerHTML = '';
      res.data.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${user.user_id}</td>
          <td>${user.first_name} ${user.last_name}</td>
          <td>${user.email}</td>
          <td>${user.mobile_number || ''}</td>
          <td><span class="badge bg-${user.status==='banned'?'danger': user.status==='deleted'?'secondary':'success'}">${user.status.charAt(0).toUpperCase()+user.status.slice(1)}</span></td>
          <td>${user.created_at}</td>
          <td class="text-nowrap">
            <button class="btn btn-sm btn-info btn-view" data-id="${user.user_id}"><i class="fas fa-eye"></i></button>
            <button class="btn btn-sm btn-primary btn-edit" data-id="${user.user_id}"><i class="fas fa-pen"></i></button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${user.user_id}"><i class="fas fa-trash"></i></button>
          </td>
        `;
        tableBody.appendChild(tr);
      });
      bindButtons();
    } else {
      showAlert(res.message, false);
    }
  }

  loadUsers(); // Initial load

  // ------------------ Bind Action Buttons ------------------
  function bindButtons(){
    // View / Edit
    document.querySelectorAll('.btn-edit, .btn-view').forEach(btn => {
      btn.addEventListener('click', async function() {
        const fd = new FormData();
        fd.append('action','get_user');
        fd.append('user_id', this.dataset.id);
        const res = await postData(fd);
        if(res.success){
          const u = res.data;
          editForm.user_id.value = u.user_id;
          editForm.fullname.value = u.first_name + ' ' + u.last_name;
          editForm.email.value = u.email;
          editForm.mobile_number.value = u.mobile_number || '';
          editForm.status.value = u.status;
          editForm.created_at.value = u.created_at;
          editUserModal.show();
        } else {
          showAlert(res.message,false);
        }
      });
    });

    // Delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', async function() {
        if(!confirm('Delete this user? This will remove all related data!')) return;
        const fd = new FormData();
        fd.append('action','delete');
        fd.append('user_id', this.dataset.id);
        const res = await postData(fd);
        showAlert(res.message, res.success);
        if(res.success) loadUsers(getFilters());
      });
    });
  }

  // ------------------ Edit User Form ------------------
  editForm.addEventListener('submit', async function(e){
    e.preventDefault();
    const fd = new FormData(editForm);
    fd.append('action','update_status');
    const res = await postData(fd);
    showAlert(res.message, res.success);
    if(res.success){
      editUserModal.hide();
      loadUsers(getFilters());
    }
  });

  // ------------------ Filters ------------------
  function getFilters(){
    return {
      search: searchInput.value.trim(),
      status: statusFilter.value,
      from_date: fromDate.value,
      to_date: toDate.value
    };
  }

  searchInput.addEventListener('input', () => loadUsers(getFilters()));
  statusFilter.addEventListener('change', () => loadUsers(getFilters()));
  fromDate.addEventListener('change', () => loadUsers(getFilters()));
  toDate.addEventListener('change', () => loadUsers(getFilters()));
  resetFilters.addEventListener('click', () => {
    searchInput.value = '';
    statusFilter.value = 'all';
    fromDate.value = '';
    toDate.value = '';
    loadUsers();
  });
});
