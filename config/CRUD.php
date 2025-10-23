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

    // Create a new ticket
    public function createTicket($user_id, $category, $subject, $message){
        $stmt = $this->pdo->prepare("
            INSERT INTO tickets (user_id, category, subject, message, status, created_at)
            VALUES (?, ?, ?, ?, 'Pending', NOW())
        ");
        return $stmt->execute([$user_id, $category, $subject, $message]);
    }

    // Add a reply to a ticket
    public function addReply($ticket_id, $user_id, $message){
        $ticket = $this->getTicketById($ticket_id, $user_id);
        if(!$ticket) return false;

        $sender = 'user';
        $sender_name = $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name'];
        $stmt = $this->pdo->prepare("
            INSERT INTO ticket_replies (ticket_id, sender, sender_name, message, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$ticket_id, $sender, $sender_name, $message]);
    }

    // Add a new transaction
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

    // Get all tickets of a user
    public function getUserTickets($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT ticket_id, category, subject, status, created_at
            FROM tickets
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a specific ticket by ID
    public function getTicketById($ticket_id, $user_id) {
        $stmt = $this->pdo->prepare("
            SELECT ticket_id, category, subject, message, status, created_at
            FROM tickets
            WHERE ticket_id = ? AND user_id = ?
            LIMIT 1
        ");
        $stmt->execute([$ticket_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get ticket messages + replies
    public function getTicketMessages($ticket_id, $user_id) {
        $stmt = $this->pdo->prepare("
            SELECT CONCAT(u.first_name,' ',u.last_name) AS sender_name, t.message, t.created_at, 'user' AS sender_type
            FROM tickets t
            JOIN users u ON t.user_id = u.user_id
            WHERE t.ticket_id = ? AND t.user_id = ?

            UNION ALL

            SELECT tr.sender_name, tr.message, tr.created_at, tr.sender AS sender_type
            FROM ticket_replies tr
            JOIN tickets tk ON tr.ticket_id = tk.ticket_id
            WHERE tr.ticket_id = ? AND tk.user_id = ?

            ORDER BY created_at ASC
        ");
        $stmt->execute([$ticket_id, $user_id, $ticket_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all transactions for a user
    public function getUserTransactions($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_history WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get transactions by tenant email (optional date filter)
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

    // Close ticket (User)
    public function updateTicketStatus($ticket_id, $user_id, $status){
        $stmt = $this->pdo->prepare("UPDATE tickets SET status = ? WHERE ticket_id = ? AND user_id = ?");
        return $stmt->execute([$status, $ticket_id, $user_id]);
    }

    // Update a reply (optional)
    public function updateReply($reply_id, $user_id, $message){
        $stmt = $this->pdo->prepare("
            UPDATE ticket_replies tr
            JOIN tickets t ON tr.ticket_id = t.ticket_id
            SET tr.message = ?
            WHERE tr.reply_id = ? AND t.user_id = ? AND tr.sender = 'user'
        ");
        return $stmt->execute([$message, $reply_id, $user_id]);
    }

    // Update payment
    public function updatePayment($payment_id, $amount, $method) {
        $stmt = $this->pdo->prepare("UPDATE payments SET amount = ?, method = ? WHERE payment_id = ?");
        return $stmt->execute([$amount, $method, $payment_id]);
    }

    // Update lease
    public function updateLease($lease_id, $start_date, $end_date, $rent) {
        $stmt = $this->pdo->prepare("UPDATE leases SET start_date = ?, end_date = ?, rent = ? WHERE lease_id = ?");
        return $stmt->execute([$start_date, $end_date, $rent, $lease_id]);
    }

    // Update transaction
    public function updateTransaction($id, $description, $amount, $status, $method, $property = null) {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_history
            SET description = ?, amount = ?, status = ?, method = ?, property = ?
            WHERE id = ?
        ");
        return $stmt->execute([$description, $amount, $status, $method, $property, $id]);
    }

    /* ================= DELETE ================= */

    // Delete a ticket (User)
    public function deleteTicket($ticket_id, $user_id) {
        $check = $this->pdo->prepare("SELECT status FROM tickets WHERE ticket_id = ? AND user_id = ?");
        $check->execute([$ticket_id, $user_id]);
        $ticket = $check->fetch(PDO::FETCH_ASSOC);
        if (!$ticket || $ticket['status'] !== 'Pending') return false;

        $this->pdo->prepare("DELETE FROM ticket_replies WHERE ticket_id = ?")->execute([$ticket_id]);
        $stmt = $this->pdo->prepare("DELETE FROM tickets WHERE ticket_id = ?");
        return $stmt->execute([$ticket_id]);
    }

    // Delete a reply (User)
    public function deleteReply($reply_id, $user_id){
        $stmt = $this->pdo->prepare("
            DELETE tr FROM ticket_replies tr
            JOIN tickets t ON tr.ticket_id = t.ticket_id
            WHERE tr.reply_id = ? AND tr.sender = 'user' AND t.user_id = ?
        ");
        return $stmt->execute([$reply_id, $user_id]);
    }

    // Delete payment
    public function deletePayment($payment_id) {
        $stmt = $this->pdo->prepare("DELETE FROM payments WHERE payment_id = ?");
        return $stmt->execute([$payment_id]);
    }

    // Delete lease
    public function deleteLease($lease_id) {
        $stmt = $this->pdo->prepare("DELETE FROM leases WHERE lease_id = ?");
        return $stmt->execute([$lease_id]);
    }

    // Delete transaction
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
