<?php
include '../layouts/header.php';
// Dummy rental application data
$applications = [
  (object)[
    'application_id' => 1,
    'user_id' => 101,
    'full_name' => 'Carlos Reyes',
    'email' => 'carlos.reyes@primeraliving.com',
    'mobile_number' => '09171234589',
    'date_of_birth' => '1990-03-22',
    'current_address' => '123 Mabuhay St, Sampaloc, Manila',
    'valid_id_file' => 'id_file_carlos.jpg',
    'employment_status' => 'Employed',
    'occupation' => 'Software Developer',
    'employer_name' => 'ABC Tech Solutions',
    'monthly_income' => 45000.00,
    'proof_of_income_file' => 'proof_income_carlos.jpg',
    'num_people_moving_in' => 2,
    'has_pets' => true,
    'expected_move_in' => '2025-06-01',
    'length_of_stay' => 12,  // in months
    'payment_method' => 'Bank Transfer',
    'security_deposit_ack' => true,
    'agreed_to_terms' => true,
    'confirmation_text' => 'I confirm that the information provided is correct.',
    'status' => 'Pending'
  ],
  (object)[
    'application_id' => 2,
    'user_id' => 102,
    'full_name' => 'Maya Santos',
    'email' => 'maya.santos@primeraliving.com',
    'mobile_number' => '09173456789',
    'date_of_birth' => '1985-11-10',
    'current_address' => '456 Sunshine Ave, Quezon City',
    'valid_id_file' => 'id_file_maya.jpg',
    'employment_status' => 'Self-employed',
    'occupation' => 'Freelance Writer',
    'employer_name' => 'Freelance',
    'monthly_income' => 25000.00,
    'proof_of_income_file' => 'proof_income_maya.jpg',
    'num_people_moving_in' => 1,
    'has_pets' => false,
    'expected_move_in' => '2025-07-15',
    'length_of_stay' => 24,
    'payment_method' => 'Cash',
    'security_deposit_ack' => true,
    'agreed_to_terms' => true,
    'confirmation_text' => 'I have read and agreed to the terms.',
    'status' => 'Approved'
  ]
];
?>

<div class="container-fluid">
  <div class="mb-4">
    <h2>Rental Applications</h2>
  </div>

  <!-- Start of the table -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Application ID</th>
          <th>User ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Mobile Number</th>
          <th>Date of Birth</th>
          <th>Current Address</th>
          <th>Valid ID</th>
          <th>Employment Status</th>
          <th>Occupation</th>
          <th>Employer Name</th>
          <th>Monthly Income</th>
          <th>Proof of Income</th>
          <th>Num. of People Moving In</th>
          <th>Has Pets</th>
          <th>Expected Move-In</th>
          <th>Length of Stay</th>
          <th>Payment Method</th>
          <th>Security Deposit Acknowledged</th>
          <th>Agreed to Terms</th>
          <th>Confirmation Text</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($applications as $application): ?>
          <tr>
            <td><?= $application->application_id ?></td>
            <td><?= $application->user_id ?></td>
            <td><?= $application->full_name ?></td>
            <td><?= $application->email ?></td>
            <td><?= $application->mobile_number ?></td>
            <td><?= $application->date_of_birth ?></td>
            <td><?= $application->current_address ?></td>
            <td><a href="<?= $application->valid_id_file ?>" target="_blank">View ID</a></td>
            <td><?= $application->employment_status ?></td>
            <td><?= $application->occupation ?></td>
            <td><?= $application->employer_name ?></td>
            <td><?= number_format($application->monthly_income, 2) ?></td>
            <td><a href="<?= $application->proof_of_income_file ?>" target="_blank">View Proof</a></td>
            <td><?= $application->num_people_moving_in ?></td>
            <td><?= $application->has_pets ? 'Yes' : 'No' ?></td>
            <td><?= $application->expected_move_in ?></td>
            <td><?= $application->length_of_stay ?> months</td>
            <td><?= $application->payment_method ?></td>
            <td>
              <span class="badge bg-<?= $application->security_deposit_ack ? 'success' : 'danger' ?>">
                <?= $application->security_deposit_ack ? 'Yes' : 'No' ?>
              </span>
            </td>
            <td>
              <span class="badge bg-<?= $application->agreed_to_terms ? 'success' : 'danger' ?>">
                <?= $application->agreed_to_terms ? 'Agreed' : 'Not Agreed' ?>
              </span>
            </td>
            <td><?= $application->confirmation_text ?></td>
            <td>
              <span class="badge bg-<?= $application->status === 'Pending' ? 'warning' : ($application->status === 'Approved' ? 'success' : 'danger') ?>">
                <?= ucfirst($application->status) ?>
              </span>
            </td>
            <td>
              <a href="#" class="btn btn-sm btn-success">Approve</a>
              <a href="#" class="btn btn-sm btn-danger">Reject</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- End of the table -->
</div>

<?php include '../layouts/footer.php'; ?>
