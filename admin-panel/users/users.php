<?php
include '../layouts/header.php';
// require "../../config/config.php"; // Skipped for now

// Sample users array (dummy data)
$users = [
    (object)[
      'user_id' => 1,
      'fullname' => 'Angela Reyes',
      'email' => 'angela.reyes@primeraliving.com',
      'mobile_number' => '09171112222',
      'status' => 'active',
      'created_at' => '2025-05-01 10:00:00'
    ],
    (object)[
      'user_id' => 2,
      'fullname' => 'Mark Castillo',
      'email' => 'mark.castillo@primeraliving.com',
      'mobile_number' => '09172223333',
      'status' => 'banned',
      'created_at' => '2025-04-28 14:22:00'
    ],
    (object)[
      'user_id' => 3,
      'fullname' => 'Isabelle Cruz',
      'email' => 'isabelle.cruz@primeraliving.com',
      'mobile_number' => '09173334444',
      'status' => 'active',
      'created_at' => '2025-03-15 09:30:00'
    ],
  ];
?>

<!-- Main content wrapper -->
<div class="col-md-9 col-lg-10 p-4" style="background-color:#EBE7DB;">
  <div class="mb-4">
    <h2>User Management</h2>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>User ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Mobile Number</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= $user->user_id ?></td>
            <td><?= $user->fullname ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->mobile_number ?></td>
            <td>
              <span class="badge bg-<?= $user->status === 'banned' ? 'danger' : 'success' ?>">
                <?= ucfirst($user->status) ?>
              </span>
            </td>
            <td><?= $user->created_at ?></td>
            <td>
              <a href="#" class="btn btn-sm btn-warning disabled"><?= $user->status === 'banned' ? 'Unban' : 'Ban' ?></a>
              <a href="#" class="btn btn-sm btn-danger disabled">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>
