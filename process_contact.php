<?php
session_start();
require_once 'config/functions.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid']);
    exit;
}

// Save the message to database
try {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (:name, :email, :message, NOW())");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Pesan telah terkirim ke database. Kami akan segera menghubungi Anda.']);
} catch (Exception $e) {
    error_log("Failed to save to DB from contact form: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesan ke database.']);
}
?>