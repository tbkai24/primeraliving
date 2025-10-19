<?php
require_once '../config/config.php';
require_once '../config/crud.php';

header('Content-Type: application/json');

$crud = new CRUD($pdo);
$action = $_POST['action'] ?? '';

if($action === 'get_reports'){
    try {
        // =====================
        // Summary Cards
        // =====================
        $transactions = $crud->getAllTransactions();
        $currentMonth = date('m');
        $currentRevenue = 0;
        $latePayments = 0;
        $expiringLeases = 0;

        foreach($transactions as $tx){
            $txMonth = date('m', strtotime($tx['created_at']));
            if($txMonth == $currentMonth && strtolower($tx['status']) === 'paid'){
                $currentRevenue += (float)$tx['amount'];
            }
            if(strtolower($tx['status']) === 'late'){
                $latePayments++;
            }
        }

        $leases = $crud->getLeasesWithDueSoon(30);
        $expiringLeases = count($leases);

        // =====================
        // Chart Data
        // =====================
        $chartLabels = [];
        $chartData = [];
        for($m=1;$m<=12;$m++){
            $monthTotal = 0;
            foreach($transactions as $tx){
                $txMonth = date('n', strtotime($tx['created_at']));
                if($txMonth == $m && strtolower($tx['status']) === 'paid'){
                    $monthTotal += (float)$tx['amount'];
                }
            }
            $chartLabels[] = date('F', mktime(0,0,0,$m,1));
            $chartData[] = $monthTotal;
        }

        echo json_encode([
            'success' => true,
            'summary' => [
                'currentRevenue' => $currentRevenue,
                'latePayments' => $latePayments,
                'expiringLeases' => $expiringLeases
            ],
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData
            ],
            'transactions' => $transactions
        ]);
    } catch(Exception $e){
        echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
    }
    exit;
}

echo json_encode(['success'=>false,'message'=>'Invalid action']);
