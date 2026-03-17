<?php
header('Content-Type: application/json; charset=utf-8');

$use_local_storage = false;
$conn = null;

// Try to connect to external database
try {
    $conn = @new mysqli("localhost", "u455592622_DO_admin", "+4XgXT&TrKT", "u455592622_Direct_online");
    
    if ($conn->connect_error) {
        $use_local_storage = true;
    } else {
        $conn->set_charset("utf8mb4");
    }
} catch (Exception $e) {
    $use_local_storage = true;
}

// Function to save locally if DB is not available
function save_locally($data) {
    $file = __DIR__ . '/submissions.json';
    $submissions = [];
    
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $submissions = json_decode($content, true) ?: [];
    }
    
    $data['submitted_at'] = date('Y-m-d H:i:s');
    $data['id'] = uniqid();
    $submissions[] = $data;
    
    return file_put_contents($file, json_encode($submissions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
