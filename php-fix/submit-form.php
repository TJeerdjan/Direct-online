<?php
/**
 * Universal Contact Form Handler — Direct-Online
 * Works for all clients. Place alongside db.php on each client site.
 * The api_key in the form identifies which client the submission belongs to.
 */

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Only POST requests are allowed"]);
    exit;
}

// Support both JSON and form-data input
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (strpos($contentType, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    $input = $_POST;
}

$api_key  = trim($input['api_key'] ?? '');
$naam     = trim($input['naam'] ?? '');
$email    = trim($input['email'] ?? '');
$telefoon = trim($input['telefoon'] ?? '');
$bericht  = trim($input['bericht'] ?? '');

// Validate required fields
if ($api_key === '' || $naam === '' || $email === '' || $bericht === '') {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vul alle verplichte velden in (api_key, naam, email, bericht)"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Ongeldig e-mailadres"]);
    exit;
}

// Look up client by api_key
$stmt = $conn->prepare("SELECT id FROM klanten_DO WHERE api_key = ? LIMIT 1");
$stmt->bind_param("s", $api_key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Ongeldige API key"]);
    exit;
}

$klant = $result->fetch_assoc();
$client_id = (int)$klant['id'];

// Insert into formulieren_DO
$stmt2 = $conn->prepare("
    INSERT INTO formulieren_DO (client_id, naam, email, telefoon, bericht)
    VALUES (?, ?, ?, ?, ?)
");
$stmt2->bind_param("issss", $client_id, $naam, $email, $telefoon, $bericht);

if ($stmt2->execute()) {
    echo json_encode(["status" => "success", "message" => "Formulier succesvol verzonden"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Kon formulier niet opslaan"]);
}

$conn->close();
?>
