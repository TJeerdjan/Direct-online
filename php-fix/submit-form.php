<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /?form=error#kennismaking");
    exit;
}

$api_key = trim($_POST['api_key'] ?? '');
$naam = trim($_POST['naam'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefoon = trim($_POST['telefoon'] ?? '');
$bericht = trim($_POST['bericht'] ?? '');

// Validate required fields
if ($api_key === '' || $naam === '' || $email === '') {
    error_log("Form error: missing required fields");
    header("Location: /?form=error#kennismaking");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Form error: invalid email format");
    header("Location: /?form=error#kennismaking");
    exit;
}

// Optionele extra check op vaste key
$valid_api_key = '3b1f7c9e5a2d4f8c6e1b9a7d3c5f2e8a6d4b1c9f7e3a5d2c8b6f1a9e4c7d3b5f';
if ($api_key !== $valid_api_key) {
    error_log("Form error: invalid api_key");
    header("Location: /?form=error#kennismaking");
    exit;
}

$saved = false;

// Database gebruiken
if (!$use_local_storage && $conn) {
    // Step 1: Find the client by api_key
    $stmt = $conn->prepare("SELECT id FROM klanten_DO WHERE api_key = ? LIMIT 1");

    if ($stmt) {
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $klant = $result->fetch_assoc();
            $client_id = (int)$klant['id'];

            // Step 2: Insert into formulieren_DO with client_id (was klant_id before migration)
            $stmt2 = $conn->prepare("
                INSERT INTO formulieren_DO (client_id, naam, email, telefoon, bericht)
                VALUES (?, ?, ?, ?, ?)
            ");

            if ($stmt2) {
                $stmt2->bind_param("issss", $client_id, $naam, $email, $telefoon, $bericht);
                $saved = $stmt2->execute();
                if (!$saved) {
                    error_log("Form DB error on INSERT: " . $stmt2->error);
                }
            } else {
                error_log("Form DB error on prepare INSERT: " . $conn->error);
            }
        } else {
            error_log("Form error: no client found with this api_key in klanten_DO");
        }
    } else {
        error_log("Form DB error on prepare SELECT: " . $conn->error);
    }
}

// Fallback local storage
if (!$saved) {
    $data = [
        'naam' => $naam,
        'email' => $email,
        'telefoon' => $telefoon,
        'bericht' => $bericht
    ];
    $saved = save_locally($data);
    if ($saved) {
        error_log("Form: saved locally as DB fallback");
    }
}

if ($saved) {
    header("Location: /?form=success#kennismaking");
    exit;
} else {
    error_log("Form error: could not save anywhere");
    header("Location: /?form=error#kennismaking");
    exit;
}
?>
