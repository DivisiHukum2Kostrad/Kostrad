<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    echo "MySQL connection successful\n";

    // Try to create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS kostrad");
    echo "Database 'kostrad' created or already exists\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
