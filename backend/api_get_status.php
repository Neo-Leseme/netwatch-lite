<?php
session_start();
// Check if user is logged in (Optional security)
// if (!isset($_SESSION['user_id'])) { http_response_code(403); exit; }

require_once 'db.php';
header('Content-Type: application/json');

// Get latest status for each server
$sql = "SELECT s.server_name, s.ip_address, sl.status, sl.response_time, sl.timestamp 
        FROM Servers s 
        JOIN StatusLogs sl ON s.id = sl.server_id 
        WHERE sl.id = (
            SELECT MAX(id) FROM StatusLogs WHERE server_id = s.id
        )";

$stmt = $pdo->query($sql);
$data = $stmt->fetchAll();

echo json_encode($data);
?>