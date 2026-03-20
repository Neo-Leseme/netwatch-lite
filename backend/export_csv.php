<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die('Unauthorized');
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="netwatch_logs_' . date('Y-m-d') . '.csv"');

// Output CSV header
echo "Timestamp,Server Name,IP Address,Status,Response Time (ms)\n";

// Get all logs with server info
$sql = "SELECT sl.timestamp, s.server_name, s.ip_address, sl.status, sl.response_time 
        FROM StatusLogs sl 
        JOIN Servers s ON sl.server_id = s.id 
        ORDER BY sl.timestamp DESC 
        LIMIT 1000";

$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll();

// Output each row
foreach ($logs as $log) {
    echo sprintf(
        "%s,%s,%s,%s,%s\n",
        $log['timestamp'],
        '"'.str_replace('"', '""', $log['server_name']).'"',
        $log['ip_address'],
        $log['status'],
        $log['response_time']
    );
}
?>