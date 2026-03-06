<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Only POST requests are allowed"
    ]);
    exit;
}

$api_key = trim($_POST['api_key'] ?? '');
$naam = trim($_POST['naam'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefoon = trim($_POST['telefoon'] ?? '');
$bericht = trim($_POST['bericht'] ?? '');

if ($api_key === '' || $naam === '' || $email === '' || $bericht === '') {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Required fields are missing"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email address"
    ]);
    exit;
}

/*
  Zoek klant op basis van api_key
*/
$stmt = $conn->prepare("SELECT id, naam, domain FROM klanten_DO WHERE api_key = ? LIMIT 1");
$stmt->bind_param("s", $api_key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid API key"
    ]);
    exit;
}

$klant = $result->fetch_assoc();
$klant_id = (int)$klant['id'];

/*
  Sla formulier op
*/
$stmt = $conn->prepare("
    INSERT INTO formulieren_DO (klant_id, naam, email, telefoon, bericht)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("issss", $klant_id, $naam, $email, $telefoon, $bericht);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Form submitted successfully"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to save form"
    ]);
}
?>