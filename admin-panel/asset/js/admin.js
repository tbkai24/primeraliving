document.addEventListener("DOMContentLoaded", function() {
  const addModal = new bootstrap.Modal(document.getElementById('addAdminModal'));
  const editModal = new bootstrap.Modal(document.getElementById('editAdminModal'));

  const addForm = document.getElementById('addAdminForm');
  const editForm = document.getElementById('editAdminForm');
  const tableBody = document.querySelector('table tbody');

  async function postData(formData){
    try{
      const res = await fetch('../handlers/admin_handler.php', {method:'POST', body:formData});
      return await res.json();
    } catch(err){
      console.error(err);
      return {success:false, message:'Server error'};
    }
  }

  async function loadAdmins(){
    const fd = new FormData();
    fd.append('action','read');
    const res = await postData(fd);

    if(res.success){
      tableBody.innerHTML = '';
      res.data.forEach(admin=>{
        const role = admin.role ?? '';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${admin.admin_id ?? ''}</td>
          <td>${admin.fullname ?? ''}</td>
          <td>${admin.email ?? ''}</td>
          <td>${role.charAt(0).toUpperCase() + role.slice(1)}</td>
          <td>${admin.created_at ?? ''}</td>
          <td>
            <button class="btn btn-warning btn-sm btn-edit me-1"
              data-id="${admin.admin_id ?? ''}"
              data-fullname="${admin.fullname ?? ''}"
              data-email="${admin.email ?? ''}"
              data-role="${role}" title="Edit Admin">
              <i class="fas fa-pen"></i>
            </button>
            <button class="btn btn-danger btn-sm btn-delete"
              data-id="${admin.admin_id ?? ''}" title="Delete Admin">
              <i class="fas fa-trash"></i>
            </button>
          </td>`;
        tableBody.appendChild(tr);
      });
      bindEditButtons();
      bindDeleteButtons();
    } else {
      alert(res.message);
    }
  }

  addForm.addEventListener('submit', async e=>{
    e.preventDefault();
    const fd = new FormData(addForm);
    fd.append('action','create');
    const res = await postData(fd);
    alert(res.message);
    if(res.success){ addModal.hide(); addForm.reset(); loadAdmins(); }
  });

  function bindEditButtons(){
    document.querySelectorAll('.btn-edit').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        editForm.admin_id.value = btn.dataset.id;
        editForm.fullname.value = btn.dataset.fullname;
        editForm.email.value = btn.dataset.email;
        editForm.role.value = btn.dataset.role;
        editForm.password.value = '';
        editModal.show();
      });
    });
  }

  editForm.addEventListener('submit', async e=>{
    e.preventDefault();
    const fd = new FormData(editForm);
    fd.append('action','update');
    const res = await postData(fd);
    alert(res.message);
    if(res.success){ editModal.hide(); loadAdmins(); }
  });

  function bindDeleteButtons(){
    document.querySelectorAll('.btn-delete').forEach(btn=>{
      btn.addEventListener('click', async ()=>{
        if(!confirm('Delete this admin?')) return;
        const fd = new FormData();
        fd.append('action','delete');
        fd.append('admin_id', btn.dataset.id);
        const res = await postData(fd);
        alert(res.message);
        if(res.success) loadAdmins();
      });
    });
  }

  loadAdmins();
});
