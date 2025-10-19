<?php
require_once '../config/config.php';
require_once '../config/crud.php';
require_once '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ======================
// Initialize PDO & CRUD
// ======================
try {
    $pdo = new PDO(
        $config['db_dsn'],
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]));
}

$crud = new CRUD($pdo);

// ======================
// FETCH LEASES FOR DASHBOARD
// ======================
if (isset($_POST['action']) && $_POST['action'] === 'fetch_leases') {
    try {
        $leases = $crud->getAllLeases(); // returns leases with user and unit info
        $today = new DateTime();

        foreach ($leases as &$lease) {
            $nextDue = isset($lease['next_due']) ? new DateTime($lease['next_due']) : null;

            if ($nextDue) {
                $lease['days_left'] = (int)$today->diff($nextDue)->format('%r%a');
            } else {
                $lease['days_left'] = null;
            }
        }

        echo json_encode(['status' => 'success', 'data' => $leases]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ======================
// SEND REMINDER EMAIL
// ======================
if (isset($_POST['action']) && $_POST['action'] === 'send_reminder' && isset($_POST['lease_id'])) {
    sendReminderEmail($_POST['lease_id']);
    exit;
}

// ======================
// MANUAL BULK REMINDER
// ======================
if (isset($_GET['cron']) && $_GET['cron'] === 'run') {
    $leases = $crud->getLeasesWithDueSoon(7);

    foreach ($leases as $lease) {
        sendReminderEmail($lease['lease_id'], false);
    }

    echo "Cron executed. " . count($leases) . " reminder(s) sent.\n";
    exit;
}

// ======================
// FUNCTION: SEND REMINDER EMAIL
// ======================
function sendReminderEmail($lease_id, $isManual = true) {
    global $crud, $config;

    $lease = $crud->getLeaseById($lease_id);

    if (!$lease) {
        if ($isManual) {
            echo json_encode(['status' => 'error', 'message' => 'Lease not found.']);
        }
        return;
    }

    $today = new DateTime();
    $nextDue = isset($lease['next_due']) ? new DateTime($lease['next_due']) : null;

    if ($nextDue) {
        $daysLeft = (int)$today->diff($nextDue)->format('%r%a');
        $lease['days_left'] = $daysLeft;
    } else {
        $daysLeft = null;
        $lease['days_left'] = null;
    }

    // Skip sending if manual check and not yet due
    if ($daysLeft > 7 && $isManual) {
        echo json_encode(['status' => 'error', 'message' => 'Reminder not yet due.']);
        return;
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $config['smtp']['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp']['user'];
        $mail->Password   = $config['smtp']['pass'];
        $mail->SMTPSecure = $config['smtp']['secure'];
        $mail->Port       = $config['smtp']['port'];
        $mail->setFrom($config['smtp']['user'], 'Primera Living');

        $mail->addAddress($lease['email'], $lease['first_name'] . ' ' . $lease['last_name']);
        $mail->isHTML(true);
        $unit = $lease['unit_name'] ?? 'your unit';
        $dueDate = $lease['next_due'] ?? '-';
        $mail->Subject = "Rent Payment Reminder - {$unit}";
        $mail->Body = "
            <p>Hi <b>{$lease['first_name']} {$lease['last_name']}</b>,</p>
            <p>This is a friendly reminder that your rent for <b>{$unit}</b> is due on <b>{$dueDate}</b> (in {$daysLeft} day(s)).</p>
            <p>Please ensure timely payment to avoid penalties.</p>
            <p>Thank you,<br><b>Primera Living Team</b></p>
        ";

        $mail->send();

        // Mark as reminded
        $crud->updateLease($lease_id, $lease['unit_id'], $lease['start_date'], $lease['end_date'], $lease['rent'], date('Y-m-d'));

        if ($isManual) {
            echo json_encode(['status' => 'success', 'message' => 'Reminder sent successfully!']);
        }
    } catch (Exception $e) {
        if ($isManual) {
            echo json_encode(['status' => 'error', 'message' => 'Mailer error: ' . $e->getMessage()]);
        }
    }
}
