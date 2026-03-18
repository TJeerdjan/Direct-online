<?php
/**
 * Public Data API — Direct-Online
 * Returns portfolio, testimonials, and settings for a client website.
 * Place alongside db.php on each client site.
 *
 * Usage:
 *   api.php?key=API_KEY                    → all public data
 *   api.php?key=API_KEY&type=portfolio     → portfolio only
 *   api.php?key=API_KEY&type=testimonials  → testimonials only
 *   api.php?key=API_KEY&type=settings      → site settings only
 */

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'db.php';

$api_key = trim($_GET['key'] ?? '');
$type = trim($_GET['type'] ?? 'all');

if ($api_key === '') {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "API key is verplicht (?key=...)"]);
    exit;
}

// Look up client
$stmt = $conn->prepare("SELECT id, naam, slug, domain FROM klanten_DO WHERE api_key = ? AND status = 'active' LIMIT 1");
$stmt->bind_param("s", $api_key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Ongeldige API key of inactieve klant"]);
    exit;
}

$klant = $result->fetch_assoc();
$client_id = (int)$klant['id'];
$response = ["status" => "success", "client" => $klant['naam']];

// Portfolio
if ($type === 'all' || $type === 'portfolio') {
    $stmt = $conn->prepare("
        SELECT id, title, slug, description, category, external_url, sort_order
        FROM projects_DO
        WHERE client_id = ? AND is_visible = 1
        ORDER BY sort_order ASC
    ");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $portfolio = [];
    while ($row = $result->fetch_assoc()) {
        $portfolio[] = $row;
    }
    $response['portfolio'] = $portfolio;
}

// Testimonials
if ($type === 'all' || $type === 'testimonials') {
    $stmt = $conn->prepare("
        SELECT id, client_name, client_title, client_company, quote, rating, sort_order
        FROM testimonials
        WHERE client_id = ? AND is_visible = 1
        ORDER BY sort_order ASC
    ");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $testimonials = [];
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
    $response['testimonials'] = $testimonials;
}

// Settings
if ($type === 'all' || $type === 'settings') {
    $stmt = $conn->prepare("
        SELECT site_name, tagline, primary_color, accent_color,
               social_instagram, social_linkedin, social_facebook, social_twitter
        FROM client_settings
        WHERE client_id = ? LIMIT 1
    ");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $settings = $result->fetch_assoc() ?? [];
    $response['settings'] = $settings;
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$conn->close();
?>
