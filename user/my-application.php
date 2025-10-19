<?php
include '../config/config.php';
include '../includes/header.php';

// Sample data - replace with database queries
$applications = [
  [
    'property' => 'Unit 101 - Palm Residence',
    'status' => 'Under Review',
    'date_applied' => '2024-11-05'
  ],
  [
    'property' => 'Unit 204 - Maple Tower',
    'status' => 'Approved',
    'date_applied' => '2024-10-21'
  ]
];
?>

<div class="container py-5" style="min-height: 100vh;" >
  <h2 class="mb-2">My Rental Applications</h2>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead style="background-color: #7e57c2; color: white;">
        <tr>
          <th>Property</th>
          <th>Status</th>
          <th>Date Applied</th>
        </tr>
      </thead>
      <tbody style="background-color: #EBE7DB;">
        <?php foreach ($applications as $application): ?>
          <tr>
            <td><?php echo htmlspecialchars($application['property']); ?></td>
            <td>
              <span class="badge 
                <?php echo $application['status'] == 'Approved' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                <?php echo htmlspecialchars($application['status']); ?>
              </span>
            </td>
            <td><?php echo htmlspecialchars($application['date_applied']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
