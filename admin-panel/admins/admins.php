<?php
include '../layouts/header.php';

// Dummy admin data
$admins = [
  (object)[
    'admin_id' => 1,
    'fullname' => 'Anna Reyes',
    'email' => 'anna.reyes@primeraliving.com',
    'role' => 'superadmin',
    'created' => '2025-04-15 08:30:00'
  ],
  (object)[
    'admin_id' => 2,
    'fullname' => 'Carlos Mendoza',
    'email' => 'carlos.mendoza@primeraliving.com',
    'role' => 'admin',
    'created' => '2025-04-20 11:45:00'
  ]
];
?>

<div class="container-fluid">
  <div class="mb-4 d-flex justify-content-between align-items-center">
    <h2>Admin Management</h2>
    <a href="#" class="btn btn-sm" style="background-color: #6f42c1; color: white;">
      <i class="fas fa-plus me-1"></i> Add Admin
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($admins as $admin): ?>
          <tr>
            <td><?= $admin->admin_id ?></td>
            <td><?= $admin->fullname ?></td>
            <td><?= $admin->email ?></td>
            <td>
              <span class="badge bg-<?= $admin->role === 'superadmin' ? 'primary' : 'secondary' ?>">
                <?= ucfirst($admin->role) ?>
              </span>
            </td>
            <td><?= $admin->created ?></td>
            <td>
              <a href="#" class="btn btn-sm" style="background-color: #7e57c2; color: white;">Edit</a>
              <a href="#" class="btn btn-sm btn-danger disabled" onclick="return confirm('Delete this admin?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>
