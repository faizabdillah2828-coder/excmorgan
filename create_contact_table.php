<?php
// File: create_contact_table.php
// This file ensures the contact_messages table exists in the database

require_once 'config/database.php';

try {
    // Create the contact_messages table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "Tabel contact_messages sudah siap digunakan.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>