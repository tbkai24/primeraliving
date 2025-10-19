document.addEventListener("DOMContentLoaded", function() {
  const addModal = new bootstrap.Modal(document.getElementById('addPropertyModal'));
  const editModal = new bootstrap.Modal(document.getElementById('editPropertyModal'));
  const addForm = document.getElementById('addPropertyForm');
  const editForm = document.getElementById('editPropertyForm');
  const tableBody = document.querySelector('table tbody');
  const alertContainer = document.getElementById('alert-container');

  // ------------------ ALERT BOX ------------------
  function showAlert(message, type = 'success') {
    alertContainer.innerHTML = `
      <div id="autoAlert" class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `;

    // Auto-hide after 4 seconds
    setTimeout(() => {
      const alert = document.getElementById('autoAlert');
      if (alert) {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
      }
    }, 4000);
  }

  // ------------------ POST DATA ------------------
  async function postData(formData) {
    try {
      const res = await fetch('../handlers/property_handler.php', { method: 'POST', body: formData });
      const text = await res.text();
      try {
        return JSON.parse(text);
      } catch {
        console.error('Invalid JSON:', text);
        return { success: false, message: 'Invalid server response' };
      }
    } catch (err) {
      console.error(err);
      return { success: false, message: 'Server error' };
    }
  }

  // ------------------ LOAD PROPERTIES ------------------
  async function loadProperties() {
    const fd = new FormData();
    fd.append('action', 'read');
    const res = await postData(fd);
    if (!res.success) return showAlert(res.message, 'danger');

    tableBody.innerHTML = '';
    res.data.forEach(p => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${p.property_id}</td>
        <td>${p.unit_name}</td>
        <td>${p.description || ''}</td>
        <td><img src="../uploads/${p.image}" style="width:90px;height:65px;object-fit:cover;border-radius:5px;"></td>
        <td>${p.address}</td>
        <td>${parseFloat(p.rent_amount).toLocaleString('en-PH',{style:'currency',currency:'PHP'})}</td>
        <td><span class="badge bg-${p.availability_status==='available'?'success':'danger'}">${p.availability_status}</span></td>
        <td>${p.created_at}</td>
        <td>
          <button class="btn btn-warning btn-sm btn-edit" data-id="${p.property_id}"><i class="fas fa-pen"></i></button>
          <button class="btn btn-danger btn-sm btn-delete" data-id="${p.property_id}"><i class="fas fa-trash"></i></button>
        </td>
      `;
      tableBody.appendChild(tr);
    });

    bindEditButtons();
    bindDeleteButtons();
  }

  // ------------------ ADD PROPERTY ------------------
  addForm.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(addForm);
    fd.append('action', 'add');
    const res = await postData(fd);
    showAlert(res.message, res.success ? 'success' : 'danger');
    if (res.success) {
      addForm.reset();
      addModal.hide();
      loadProperties();
    }
  });

  // ------------------ EDIT PROPERTY ------------------
  function bindEditButtons() {
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', async function() {
        const property_id = this.dataset.id;
        const fd = new FormData();
        fd.append('action', 'get');
        fd.append('property_id', property_id);
        const res = await postData(fd);
        if (!res.success) return showAlert(res.message, 'danger');
        const p = res.data;
        editForm.property_id.value = p.property_id;
        editForm.unit_name.value = p.unit_name;
        editForm.description.value = p.description;
        editForm.address.value = p.address;
        editForm.rent_amount.value = p.rent_amount;
        editForm.availability_status.value = p.availability_status;
        document.getElementById('currentImage').src = p.image ? `../uploads/${p.image}` : '';
        editModal.show();
      });
    });
  }

  editForm.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(editForm);
    fd.append('action', 'update');
    const res = await postData(fd);
    showAlert(res.message, res.success ? 'success' : 'danger');
    if (res.success) {
      editForm.reset();
      editModal.hide();
      loadProperties();
    }
  });

  // ------------------ DELETE PROPERTY ------------------
  function bindDeleteButtons() {
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', async function() {
        if (!confirm('Delete this property?')) return;
        const fd = new FormData();
        fd.append('action', 'delete');
        fd.append('property_id', this.dataset.id);
        const res = await postData(fd);
        showAlert(res.message, res.success ? 'success' : 'danger');
        if (res.success) loadProperties();
      });
    });
  }

  // ------------------ INITIAL LOAD ------------------
  loadProperties();
});
