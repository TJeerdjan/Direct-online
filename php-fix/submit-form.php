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
    header("Location: /?form=error#kennismaking");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /?form=error#kennismaking");
    exit;
}

// Optionele extra check op vaste key
$valid_api_key = '3b1f7c9e5a2d4f8c6e1b9a7d3c5f2e8a6d4b1c9f7e3a5d2c8b6f1a9e4c7d3b5f';
if ($api_key !== $valid_api_key) {
    header("Location: /?form=error#kennismaking");
    exit;
}

$saved = false;

// Database gebruiken
if (!$use_local_storage && $conn) {
    $stmt = $conn->prepare("SELECT id FROM klanten_DO WHERE api_key = ? LIMIT 1");

    if ($stmt) {
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $klant = $result->fetch_assoc();
            $klant_id = (int)$klant['id'];

            // FIX: Changed 'klant_id' to 'client_id' to match migrated DB schema
            $stmt2 = $conn->prepare("
                INSERT INTO formulieren_DO (client_id, naam, email, telefoon, bericht)
                VALUES (?, ?, ?, ?, ?)
            ");

            if ($stmt2) {
                $stmt2->bind_param("issss", $klant_id, $naam, $email, $telefoon, $bericht);
                $saved = $stmt2->execute();
            }
        }
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
}

if ($saved) {
    header("Location: /?form=success#kennismaking");
    exit;
} else {
    header("Location: /?form=error#kennismaking");
    exit;
}
?>
