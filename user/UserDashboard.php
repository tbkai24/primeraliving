<?php
require_once "../config/config.php";
require_once "../config/CRUD.php";

if (session_status() === PHP_SESSION_NONE) session_start();

// Session check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$crud = new CRUD($pdo);
$user_id = $_SESSION['user_id'];

$user = $crud->getUserById($user_id);
$payments = $crud->getUserPayments($user_id);
$lease = $crud->getUserLease($user_id);
$tickets = $crud->getUserTickets($user_id);

// Lease progress
$leaseProgress = 0;
if ($lease) {
    $leaseProgress = ceil(
        (time() - strtotime($lease['start_date'])) /
        (strtotime($lease['end_date']) - strtotime($lease['start_date'])) * 100
    );
    if ($leaseProgress > 100) $leaseProgress = 100;
}

// Payments chart last 6 months
$paymentLabels = [];
$paymentAmounts = [];
for($i=5; $i>=0; $i--){
    $month = date("Y-m", strtotime("-$i month"));
    $paymentLabels[] = date("M Y", strtotime($month));
    $total = 0;
    foreach($payments as $p){
        if(substr($p['created_at'],0,7) === $month){
            $total += $p['amount'];
        }
    }
    $paymentAmounts[] = $total;
}

// Find next upcoming payment
$nextPayment = null;
$today = strtotime(date('Y-m-d'));
foreach ($payments as $p) {
    if (!empty($p['due_date'])) {
        $due = strtotime($p['due_date']);
        if ($due >= $today) {
            if ($nextPayment === null || $due < strtotime($nextPayment['due_date'])) {
                $nextPayment = $p;
            }
        }
    }
}

include '../includes/header.php';
?>

<section class="dashboard-section py-5">
  <div class="dashboard-overlay"></div>

  <div class="container mt-5 pt-4 position-relative fade-in-dashboard" style="z-index: 2;">

    <!-- Welcome Back / Next Payment -->
    <div class="mb-4">
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <strong>Welcome back, <?= htmlspecialchars($user['first_name']) ?>!</strong>
                <?php if($nextPayment): ?>
                    Your next payment of $<?= htmlspecialchars($nextPayment['amount']) ?> is due on <?= $nextPayment['due_date'] ?>.
                <?php else: ?>
                    You have no upcoming payments.
                <?php endif; ?>
            </div>
            <?php if($nextPayment): ?>
                <a href="payments.php" class="btn btn-sm btn-primary">Pay Now</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3">

        <!-- Profile Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-primary">
                <div class="card-body text-center">
                    <img src="<?= APPURL ?>/asset/images/default-avatar.png" class="rounded-circle mb-2" width="60">
                    <h5 class="card-title"><?= htmlspecialchars($user['first_name'].' '.$user['last_name']) ?></h5>
                    <p class="card-text mb-1"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p class="card-text mb-1"><strong>Status:</strong> <?= htmlspecialchars($user['status'] ?? 'N/A') ?></p>
                    <a href="edit_profile.php" class="btn btn-sm btn-primary mt-2 w-100">Edit Profile</a>
                </div>
            </div>
        </div>

        <!-- Payments Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-dollar-sign fa-2x text-success me-2"></i>
                        <h5 class="mb-0">Payments</h5>
                    </div>
                    <p><strong>Total Payments:</strong> <?= count($payments) ?></p>
                    <?php if(!empty($payments)): ?>
                        <p><strong>Last Payment:</strong> <?= htmlspecialchars($payments[0]['amount']) ?> on <?= $payments[0]['created_at'] ?></p>
                    <?php else: ?>
                        <p>No payments yet</p>
                    <?php endif; ?>
                    <a href="payments.php" class="btn btn-sm btn-success mt-2 w-100">View All Payments</a>
                </div>
            </div>
        </div>

        <!-- Lease Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-home fa-2x text-warning me-2"></i>
                        <h5 class="mb-0">Lease Info</h5>
                    </div>
                    <?php if ($lease): ?>
                        <p><strong>Unit:</strong> <?= htmlspecialchars($lease['unit_name']) ?></p>
                        <p><strong>Start:</strong> <?= $lease['start_date'] ?></p>
                        <p><strong>End:</strong> <?= $lease['end_date'] ?></p>
                        <p><strong>Days Left:</strong> <?= ceil((strtotime($lease['end_date']) - time())/86400) ?> days</p>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $leaseProgress ?>%;" aria-valuenow="<?= $leaseProgress ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $leaseProgress ?>%
                            </div>
                        </div>
                    <?php else: ?>
                        <p>No active lease</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Support Tickets Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-life-ring fa-2x text-danger me-2"></i>
                        <h5 class="mb-0">Support Tickets</h5>
                    </div>
                    <?php 
                        $openCount = count(array_filter($tickets ?? [], fn($t) => $t['status']==='open'));
                        $resolvedCount = count(array_filter($tickets ?? [], fn($t) => $t['status']==='resolved'));
                    ?>
                    <p><span class="badge bg-danger">Open: <?= $openCount ?></span></p>
                    <p><span class="badge bg-success">Resolved: <?= $resolvedCount ?></span></p>
                    <a href="tickets.php" class="btn btn-sm btn-danger mt-2 w-100">View Tickets</a>
                    <a href="tickets/add_ticket.php" class="btn btn-sm btn-outline-danger mt-2 w-100">Open New Ticket</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Transactions -->
    <div class="mt-5">
        <h5>Recent Transactions</h5>
        <?php if(!empty($payments)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($payments,0,5) as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['amount']) ?></td>
                            <td><?= htmlspecialchars($p['method']) ?></td>
                            <td><?= $p['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions yet.</p>
        <?php endif; ?>
    </div>

   <!-- Canvas elements -->
<canvas id="paymentsChart" 
        data-labels='<?= json_encode($paymentLabels) ?>' 
        data-amounts='<?= json_encode($paymentAmounts) ?>'></canvas>

<canvas id="leaseChart" 
        data-progress='<?= $leaseProgress ?>'></canvas>

<!-- Chart.js library -->
<script src="<?= APPURL ?>/asset/js/chart.umd.min.js"></script>

<!-- Your dashboard JS -->
<script src="<?= APPURL ?>/asset/js/dashboard.js"></script>

<?php include '../includes/footer.php'; ?>
