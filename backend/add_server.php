<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$server_name = $_POST['server_name'] ?? '';
$ip_address = $_POST['ip_address'] ?? '';
$user_id = $_SESSION['user_id'];

if (!$server_name || !$ip_address) {
    echo json_encode(['success' => false, 'message' => 'All fields required']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO Servers (server_name, ip_address, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$server_name, $ip_address, $user_id]);
    echo json_encode(['success' => true, 'message' => 'Server added successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>