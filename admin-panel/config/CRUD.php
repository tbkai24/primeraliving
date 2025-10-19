<?php
class CRUD {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    // ===================================================
    // ===================== CREATE =====================
    // ===================================================

    public function createAdmin($fullname, $email, $password, $role = 'admin'){
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO admins (fullname, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        if($stmt->execute([$fullname, $email, $hashed, $role])){
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function createUser($first, $last, $email, $hashedPassword, $vericodeHash, $vericodeExpires, $mobile_number = null){
        $stmt = $this->pdo->prepare("INSERT INTO users (first_name, last_name, email, password, mobile_number, verified, vericode_hash, vericode_expires, created_at) VALUES (?, ?, ?, ?, ?, 0, ?, ?, NOW())");
        if($stmt->execute([$first, $last, $email, $hashedPassword, $mobile_number, $vericodeHash, $vericodeExpires])){
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function createProperty($unit_name, $description, $address, $rent_amount, $status = 'available', $main_image = null, $images = []){
        $stmt = $this->pdo->prepare("INSERT INTO properties (unit_name, description, address, rent_amount, availability_status, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if(!$stmt->execute([$unit_name, $description, $address, $rent_amount, $status, $main_image])) return false;
        $property_id = $this->pdo->lastInsertId();
        if(!empty($images)){
            $imgStmt = $this->pdo->prepare("INSERT INTO property_images (property_id, image) VALUES (?, ?)");
            foreach($images as $img){ $imgStmt->execute([$property_id, $img]); }
        }
        return $property_id;
    }

    public function addPayment($user_id, $amount, $method){
        $stmt = $this->pdo->prepare("INSERT INTO payments (user_id, amount, method, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $amount, $method]);
    }

    public function addLease($user_id, $unit_id, $start_date, $end_date, $rent){
        $stmt = $this->pdo->prepare("INSERT INTO leases (user_id, unit_id, start_date, end_date, rent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $unit_id, $start_date, $end_date, $rent]);
    }

    public function addTicket($user_id, $subject, $message, $category = 'Other'){
        $stmt = $this->pdo->prepare("INSERT INTO tickets (user_id, category, subject, message, status, created_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
        return $stmt->execute([$user_id, $category, $subject, $message]);
    }

    public function addTransaction($user_id, $tenant_email, $invoice_id, $description, $amount, $status, $method, $property = null){
        $stmt = $this->pdo->prepare("INSERT INTO transaction_history (user_id, tenant_email, invoice_id, description, amount, status, method, property, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $tenant_email, $invoice_id, $description, $amount, $status, $method, $property]);
    }

    public function addTicketReply($ticket_id, $sender, $sender_name, $message){
        $stmt = $this->pdo->prepare("INSERT INTO ticket_replies(ticket_id, sender, sender_name, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$ticket_id, $sender, $sender_name, $message]);
    }

    // ===================================================
    // ===================== READ ========================
    // ===================================================

    public function getAllAdmins(){
        $stmt = $this->pdo->query("SELECT * FROM admins ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE admin_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllUsers(){
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllProperties(){
        $stmt = $this->pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $imgStmt = $this->pdo->prepare("SELECT image FROM property_images WHERE property_id = ?");
        foreach($properties as &$prop){
            $imgStmt->execute([$prop['property_id']]);
            $prop['images'] = $imgStmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return $properties;
    }

    public function getPropertyById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM properties WHERE property_id = ?");
        $stmt->execute([$id]);
        $prop = $stmt->fetch(PDO::FETCH_ASSOC);
        if($prop){
            $imgStmt = $this->pdo->prepare("SELECT image FROM property_images WHERE property_id = ?");
            $imgStmt->execute([$id]);
            $prop['images'] = $imgStmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return $prop ?: null;
    }

    public function getAllLeases(){
        $stmt = $this->pdo->query("
            SELECT l.*, u.first_name, u.last_name, p.unit_name
            FROM leases l
            JOIN users u ON l.user_id = u.user_id
            JOIN properties p ON l.unit_id = p.property_id
            ORDER BY l.start_date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLeaseById($id){
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.first_name, u.last_name, p.unit_name
            FROM leases l
            JOIN users u ON l.user_id = u.user_id
            JOIN properties p ON l.unit_id = p.property_id
            WHERE l.lease_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getLeasesWithDueSoon($days = 7){
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.email, u.first_name, u.last_name, p.unit_name,
                   DATE_ADD(l.last_paid, INTERVAL 1 MONTH) AS next_due,
                   DATEDIFF(DATE_ADD(l.last_paid, INTERVAL 1 MONTH), CURDATE()) AS days_left
            FROM leases l
            JOIN users u ON l.user_id = u.user_id
            JOIN properties p ON l.unit_id = p.property_id
            HAVING days_left <= ? AND days_left >= 0
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTransactions(){
        $stmt = $this->pdo->query("SELECT * FROM transaction_history WHERE archived = 0 ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_history WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getTickets($keyword = '', $status = '', $category = '') {
        $query = "SELECT t.*, u.first_name, u.last_name 
                  FROM tickets t
                  JOIN users u ON t.user_id = u.user_id
                  WHERE 1=1";
        $params = [];
        if($keyword !== ''){
            $query .= " AND (t.subject LIKE ? OR t.message LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if($status !== ''){
            $query .= " AND t.status = ?";
            $params[] = $status;
        }
        if($category !== ''){
            $query .= " AND t.category = ?";
            $params[] = $category;
        }
        $query .= " ORDER BY t.created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketById($ticket_id){
        $stmt = $this->pdo->prepare("
            SELECT t.*, u.first_name, u.last_name 
            FROM tickets t
            JOIN users u ON t.user_id = u.user_id
            WHERE t.ticket_id = ?
        ");
        $stmt->execute([$ticket_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getTicketMessages($ticket_id){
        $stmt = $this->pdo->prepare("
            SELECT CONCAT(u.first_name,' ',u.last_name) AS sender_name, t.message, t.created_at
            FROM tickets t
            JOIN users u ON t.user_id = u.user_id
            WHERE t.ticket_id = ?
            
            UNION ALL
            
            SELECT tr.sender_name, tr.message, tr.created_at
            FROM ticket_replies tr
            WHERE tr.ticket_id = ?
            
            ORDER BY created_at ASC
        ");
        $stmt->execute([$ticket_id, $ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTicketStatus($ticket_id, $status){
        $stmt = $this->pdo->prepare("UPDATE tickets SET status=? WHERE ticket_id=?");
        return $stmt->execute([$status,$ticket_id]);
    }

    // ===================================================
    // ===================== UPDATE ======================
    // ===================================================

    public function updateAdmin($id, $fullname, $email, $role){
        $stmt = $this->pdo->prepare("UPDATE admins SET fullname = ?, email = ?, role = ? WHERE admin_id = ?");
        return $stmt->execute([$fullname, $email, $role, $id]);
    }

    public function updateAdminPassword($id, $password){
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
        return $stmt->execute([$hashed, $id]);
    }

    public function updateUserStatus($id, $status){
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE user_id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updateProperty($id, $unit_name, $description, $address, $rent_amount, $status, $main_image = null, $images = []){
        if($main_image){
            $stmt = $this->pdo->prepare("UPDATE properties SET unit_name=?, description=?, address=?, rent_amount=?, availability_status=?, image=? WHERE property_id=?");
            $stmt->execute([$unit_name, $description, $address, $rent_amount, $status, $main_image, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE properties SET unit_name=?, description=?, address=?, rent_amount=?, availability_status=? WHERE property_id=?");
            $stmt->execute([$unit_name, $description, $address, $rent_amount, $status, $id]);
        }
        if(!empty($images)){
            $imgStmt = $this->pdo->prepare("INSERT INTO property_images (property_id, image) VALUES (?, ?)");
            foreach($images as $img){ $imgStmt->execute([$id, $img]); }
        }
        return true;
    }

    public function updateLease($lease_id, $unit_id, $start_date, $end_date, $rent, $last_paid){
        $stmt = $this->pdo->prepare("UPDATE leases SET unit_id = ?, start_date = ?, end_date = ?, rent = ?, last_paid = ? WHERE lease_id = ?");
        return $stmt->execute([$unit_id, $start_date, $end_date, $rent, $last_paid, $lease_id]);
    }

    // ===================================================
    // ===================== DELETE ======================
    // ===================================================

    public function deleteAdmin($id){
        $stmt = $this->pdo->prepare("DELETE FROM admins WHERE admin_id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteUser($id){
        $this->pdo->prepare("DELETE FROM tickets WHERE user_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM payments WHERE user_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM leases WHERE user_id = ?")->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteProperty($id){
        $this->pdo->prepare("DELETE FROM property_images WHERE property_id = ?")->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM properties WHERE property_id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteLease($lease_id){
        $stmt = $this->pdo->prepare("DELETE FROM leases WHERE lease_id = ?");
        return $stmt->execute([$lease_id]);
    }

    public function deleteTicket($ticket_id){
        $this->pdo->prepare("DELETE FROM ticket_replies WHERE ticket_id = ?")->execute([$ticket_id]);
        $stmt = $this->pdo->prepare("DELETE FROM tickets WHERE ticket_id = ?");
        return $stmt->execute([$ticket_id]);
    }

    public function deleteTransaction($id){
        $stmt = $this->pdo->prepare("DELETE FROM transaction_history WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ===================================================
    // ===================== REPORTS =====================
    // ===================================================

    public function getCurrentMonthRevenue(){
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0) 
            FROM payments 
            WHERE MONTH(created_at) = MONTH(CURDATE()) 
            AND YEAR(created_at) = YEAR(CURDATE())
        ");
        $stmt->execute();
        return (float) $stmt->fetchColumn();
    }

    public function getLatePaymentsCount(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM payments WHERE status='late'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getExpiringLeasesCount(){
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM leases 
            WHERE end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getMonthlyRevenue(){
        $stmt = $this->pdo->prepare("
            SELECT MONTH(created_at) AS month, IFNULL(SUM(amount),0) AS revenue 
            FROM payments 
            WHERE YEAR(created_at) = YEAR(CURDATE()) 
            GROUP BY MONTH(created_at) 
            ORDER BY month ASC
        ");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $revenue = [];

        for($m=1;$m<=12;$m++){
            $found = false;
            foreach($data as $row){
                if((int)$row['month'] === $m){
                    $labels[] = date('F', mktime(0,0,0,$m,1));
                    $revenue[] = (float)$row['revenue'];
                    $found = true;
                    break;
                }
            }
            if(!$found){
                $labels[] = date('F', mktime(0,0,0,$m,1));
                $revenue[] = 0;
            }
        }

        return ['labels'=>$labels,'revenue'=>$revenue];
    }
}
?>
