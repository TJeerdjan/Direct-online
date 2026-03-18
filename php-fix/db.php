<?php
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli("localhost", "u455592622_DO_admin", "+4XgXT&TrKT", "u455592622_Direct_online");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

$conn->set_charset("utf8mb4");
?>
