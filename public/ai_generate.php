<?php
session_start();
require_once './../includes/db.php';
require_once './../includes/ai.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = trim($_POST['prompt'] ?? '');
    if (!$prompt) {
        echo json_encode(['error' => 'No prompt provided']);
        exit;
    }

    // Fetch hotels from AI API
    $hotels = fetchHotelsFromAPI($prompt);

    if (empty($hotels)) {
        echo json_encode(['error' => 'No hotels found']);
        exit;
    }

    // Prepare booking data
    $user_id = $_SESSION['user_id'];

    // Get user info for confirmation email later
    $stmt = $pdo->prepare("SELECT name, surname, passport_number, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Add booking (for now, create for all 3)
    $insert = $pdo->prepare("INSERT INTO bookings (userID, check_in, check_out, nr_people, checked_in) VALUES (?, ?, ?, ?, 0)");
    $check_in = date('Y-m-d');
    $check_out = date('Y-m-d', strtotime('+5 days'));
    $nr_people = 2;
    $insert->execute([$user_id, $check_in, $check_out, $nr_people]);

    $booking_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'hotels' => $hotels,
        'booking_id' => $booking_id,
        'user' => $user
    ]);
}
?>
