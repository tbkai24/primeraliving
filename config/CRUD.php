<?php
class CRUD {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* ================= CREATE ================= */

    // Create a new user account
    public function createUser($first, $last, $email, $hashedPassword, $vericodeHash, $vericodeExpires, $mobile_number = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO users 
            (first_name, last_name, email, password, mobile_number, verified, vericode_hash, vericode_expires, created_at)
            VALUES (?, ?, ?, ?, ?, 0, ?, ?, NOW())
        ");
        if ($stmt->execute([$first, $last, $email, $hashedPassword, $mobile_number, $vericodeHash, $vericodeExpires])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    // Add a new payment
    public function addPayment($user_id, $amount, $method) {
        $stmt = $this->pdo->prepare("INSERT INTO payments (user_id, amount, method, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $amount, $method]);
    }

    // Add a new lease
    public function addLease($user_id, $unit_id, $start_date, $end_date, $rent) {
        $stmt = $this->pdo->prepare("INSERT INTO leases (user_id, unit_id, start_date, end_date, rent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $unit_id, $start_date, $end_date, $rent]);
    }

    // Add a new support ticket with category, default status = 'pending'
    public function addTicket($user_id, $subject, $message, $category = 'Other') {
        $stmt = $this->pdo->prepare("
            INSERT INTO tickets (user_id, category, subject, message, status, created_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        return $stmt->execute([$user_id, $category, $subject, $message]);
    }

    // Add a new transaction (optional property)
    public function addTransaction($user_id, $tenant_email, $invoice_id, $description, $amount, $status, $method, $property = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_history (user_id, tenant_email, invoice_id, description, amount, status, method, property, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$user_id, $tenant_email, $invoice_id, $description, $amount, $status, $method, $property]);
    }

    /* ================= READ ================= */


    // Get unverified user by email
    public function getUnverifiedUserByEmail($email){
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND verified = 0");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
    // Get user by ID
    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Get user by email
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Get all payments for a user
    public function getUserPayments($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get lease info for a user
    public function getUserLease($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM leases WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Get all tickets for a user (with category)
    public function getUserTickets($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT ticket_id, ticket_number, user_id, category, subject, message, status, created_at 
            FROM tickets 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all transactions for tenant email (with optional date filter)
    public function getTransactionsByEmail($email, $startDate = null, $endDate = null) {
        $query = "SELECT * FROM transaction_history WHERE tenant_email = ?";
        $params = [$email];

        if ($startDate && $endDate) {
            $query .= " AND DATE(created_at) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        $query .= " ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all transactions for a specific user
    public function getUserTransactions($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_history WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all transactions (for admin)
    public function getAllTransactions() {
        $stmt = $this->pdo->query("SELECT * FROM transaction_history ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= UPDATE ================= */

    // Update user profile
    public function updateUserProfile($user_id, $first, $last, $email, $mobile_number) {
        $stmt = $this->pdo->prepare("
            UPDATE users SET first_name = ?, last_name = ?, email = ?, mobile_number = ? WHERE user_id = ?
        ");
        return $stmt->execute([$first, $last, $email, $mobile_number, $user_id]);
    }

    // Update user password
    public function updateUserPassword($user_id, $hashedPassword) {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        return $stmt->execute([$hashedPassword, $user_id]);
    }

    // Mark user as verified
    public function markUserVerified($user_id) {
        $stmt = $this->pdo->prepare("UPDATE users SET verified = 1, vericode_hash = NULL, vericode_expires = NULL WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    // Update payment record
    public function updatePayment($payment_id, $amount, $method) {
        $stmt = $this->pdo->prepare("UPDATE payments SET amount = ?, method = ? WHERE payment_id = ?");
        return $stmt->execute([$amount, $method, $payment_id]);
    }

    // Update lease record
    public function updateLease($lease_id, $start_date, $end_date, $rent) {
        $stmt = $this->pdo->prepare("UPDATE leases SET start_date = ?, end_date = ?, rent = ? WHERE lease_id = ?");
        return $stmt->execute([$start_date, $end_date, $rent, $lease_id]);
    }

    // Update ticket: status, category, subject, message (optional)
    public function updateTicketStatus($ticket_id, $status, $category = null, $subject = null, $message = null) {
        $fields = ['status = ?'];
        $params = [$status];

        if ($category !== null) { $fields[] = "category = ?"; $params[] = $category; }
        if ($subject !== null)  { $fields[] = "subject = ?"; $params[] = $subject; }
        if ($message !== null)  { $fields[] = "message = ?"; $params[] = $message; }

        $params[] = $ticket_id;
        $sql = "UPDATE tickets SET " . implode(', ', $fields) . " WHERE ticket_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Update transaction details
    public function updateTransaction($id, $description, $amount, $status, $method, $property = null) {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_history
            SET description = ?, amount = ?, status = ?, method = ?, property = ?
            WHERE id = ?
        ");
        return $stmt->execute([$description, $amount, $status, $method, $property, $id]);
    }

    // Update transaction status
    public function updateTransactionStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE transaction_history SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    // Set or update verification code for a user
public function setVerificationCode($email, $vericodeHash, $vericodeExpires, $resetVerified = false) {
    $sql = "UPDATE users SET vericode_hash = ?, vericode_expires = ?";
    $params = [$vericodeHash, $vericodeExpires];

    if ($resetVerified) {
        $sql .= ", verified = 0";
    }

    $sql .= " WHERE email = ?";
    $params[] = $email;

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

    /* ================= DELETE ================= */

    public function deleteUser($user_id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    public function deletePayment($payment_id) {
        $stmt = $this->pdo->prepare("DELETE FROM payments WHERE payment_id = ?");
        return $stmt->execute([$payment_id]);
    }

    public function deleteLease($lease_id) {
        $stmt = $this->pdo->prepare("DELETE FROM leases WHERE lease_id = ?");
        return $stmt->execute([$lease_id]);
    }

    public function deleteTicket($ticket_id) {
        $stmt = $this->pdo->prepare("DELETE FROM tickets WHERE ticket_id = ?");
        return $stmt->execute([$ticket_id]);
    }

    public function deleteTransaction($id) {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_history WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /* ================= GENERIC HELPERS ================= */

    // Fetch a single row from any table
    public function fetchSingle($table, $column, $value) {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE $column = ?");
        $stmt->execute([$value]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Run custom query with parameters
    public function runQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

