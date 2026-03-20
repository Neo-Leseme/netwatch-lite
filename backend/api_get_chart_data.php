<?php
require_once 'db.php';
header('Content-Type: application/json');

// Get last 10 logs for each server
$sql = "SELECT sl.response_time, sl.timestamp, s.server_name 
        FROM StatusLogs sl 
        JOIN Servers s ON sl.server_id = s.id 
        WHERE sl.status = 'online' 
        ORDER BY sl.timestamp DESC 
        LIMIT 10";

$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll();

// Prepare data for Chart.js
$labels = [];
$response_times = [];

foreach (array_reverse($logs) as $log) {
    $labels[] = date('H:i', strtotime($log['timestamp']));
    $response_times[] = $log['response_time'];
}

echo json_encode([
    'labels' => $labels,
    'response_times' => $response_times
]);
?>