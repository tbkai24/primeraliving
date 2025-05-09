<?php
include '../layouts/header.php';
// Dummy data for prototype

$properties = [
  (object)[
    'property_id' => 1,
    'unit_name' => 'Unit 101 - Lavender',
    'address' => 'Blk 3 Lot 5, Orchid Street, Nueva Subdivision, QC',
    'rent_amount' => 8500,
    'availability_status' => 'available',
    'description' => 'Cozy 2-bedroom unit with balcony and parking.',
    'image' => '../asset/images/bg.jpg',
    'created_at' => '2025-05-01 10:15:00'
  ],
  (object)[
    'property_id' => 2,
    'unit_name' => 'Unit 302 - Rosewood',
    'address' => 'Tower B, Rose St., Taguig City',
    'rent_amount' => 12000,
    'availability_status' => 'occupied',
    'description' => 'Fully furnished 1-bedroom condo with amenities.',
    'image' => '../asset/images/bg.jpg',
    'created_at' => '2025-04-27 09:30:00'
  ],
  (object)[
    'property_id' => 3,
    'unit_name' => 'Unit A2 - Garden View',
    'address' => 'Garden Heights, Mandaluyong',
    'rent_amount' => 10000,
    'availability_status' => 'available',
    'description' => 'Spacious studio with garden access.',
    'image' => '../asset/images/bg.jpg',
    'created_at' => '2025-03-20 11:45:00'
  ]
];
?>

<div class="container-fluid">
  <div class="mb-4">
    <h2>Property Management</h2>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Unit Name</th>
          <th>Address</th>
          <th>Rent (₱)</th>
          <th>Status</th>
          <th>Description</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($properties as $p): ?>
          <tr>
            <td><?= $p->property_id ?></td>
            <td>
              <img src="../uploads/<?= $p->image ?>" alt="Image" style="width: 80px; height: auto; border-radius: 4px;">
            </td>
            <td><?= $p->unit_name ?></td>
            <td><?= $p->address ?></td>
            <td><?= number_format($p->rent_amount, 2) ?></td>
            <td>
              <span class="badge bg-<?= $p->availability_status === 'available' ? 'success' : 'danger' ?>">
                <?= ucfirst($p->availability_status) ?>
              </span>
            </td>
            <td><?= $p->description ?></td>
            <td><?= $p->created_at ?></td>
            <td>
              <a href="#" class="btn btn-sm"style="background-color: #7e57c2; color: white;">Edit</a>
              <a href="#" class="btn btn-sm btn-danger disabled" onclick="return confirm('Delete this property?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>
