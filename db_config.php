<?php
// ============================================================
// Database connection settings.
// Update these 4 values to match your actual MySQL setup
// (the same database your Faculty Management System uses,
// or a new one — just make sure schema.sql has been imported into it).
// ============================================================
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "productbacklog";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
