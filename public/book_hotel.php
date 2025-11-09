<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "qroom"; // your DB

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
$hotel_name = $conn->real_escape_string($data['hotel_name'] ?? '');
$price = (float)($data['price'] ?? 0);
$userID = $_SESSION['user_id'];

// Generate booking details
$check_in = date('Y-m-d', strtotime('+5 days'));
$check_out = date('Y-m-d', strtotime('+12 days'));
$checked_in = 0;

// Insert booking
$sql = "INSERT INTO bookings (hotel_name, userID, check_in, check_out, price, checked_in)
        VALUES ('$hotel_name', '$userID', '$check_in', '$check_out', '$price', '$checked_in')";
if ($conn->query($sql)) {
    $bookingID = $conn->insert_id;

    // Get user email
    $userResult = $conn->query("SELECT email, name FROM users WHERE id = '$userID' LIMIT 1");
    $userRow = $userResult ? $userResult->fetch_assoc() : null;
    $userEmail = $userRow['email'] ?? 'guest@example.com';
    $userName = $userRow['name'] ?? 'Valued Guest';

    // Generate QR code for check-in link
    $checkinURL = "https://qr.h-solutions.net/public/checkin.php?bookingID=" . urlencode($bookingID);

    // Generate QR code image URL via QRServer API
    $qrCodeURL = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($checkinURL);

    // Prepare email content
    $subject = "Booking Confirmation - $hotel_name";
    $message = '
    <html>
    <head>
      <title>Hotel Reservation Confirmation</title>
    </head>
    <body style="font-family:Segoe UI,Arial,sans-serif;background:#f8f9fa;margin:0;padding:20px;">
      <div style="max-width:600px;margin:auto;background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 5px 25px rgba(0,0,0,0.15);">
        <div style="background:linear-gradient(135deg,#00798C,#005a6b);padding:30px;text-align:center;">
          <h1 style="color:#fff;margin:0;">' . htmlspecialchars($hotel_name) . '</h1>
          <p style="color:#F5B700;font-weight:500;">Your reservation is confirmed!</p>
        </div>
        <div style="padding:30px;">
          <h2 style="color:#00798C;">Hello, ' . htmlspecialchars($userName) . ' 👋</h2>
          <p style="color:#333;">Thank you for booking with us. Below are your reservation details:</p>
          
          <div style="margin:25px 0;padding:15px;background:#f0f0f0;border-left:5px solid #F5B700;border-radius:8px;">
            <p><strong>Booking ID:</strong> #' . $bookingID . '</p>
            <p><strong>Hotel:</strong> ' . htmlspecialchars($hotel_name) . '</p>
            <p><strong>Check-in:</strong> ' . htmlspecialchars($check_in) . '</p>
            <p><strong>Check-out:</strong> ' . htmlspecialchars($check_out) . '</p>
            <p><strong>Price:</strong> $' . number_format($price, 2) . '</p>
          </div>

          <div style="text-align:center;margin:30px 0;">
            <p><strong>Scan to Check-In:</strong></p>
            <img src="' . $qrCodeURL . '" alt="QR Code for Booking #' . $bookingID . '" style="width:150px;height:150px;border-radius:10px;"/>
          </div>

          <div style="background:#30343F;color:white;padding:20px;border-radius:10px;text-align:center;">
            <p style="margin:0;">' . htmlspecialchars($hotel_name) . '</p>
            <p style="margin-top:10px;font-size:0.9rem;color:#ccc;">Thank you for choosing us for your stay!</p>
          </div>
        </div>
      </div>
    </body>
    </html>';

    // Email headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: QRoom <noreply@yourdomain.com>\r\n";

    // Send email
    mail($userEmail, $subject, $message, $headers);

    echo json_encode(['success' => true, 'message' => 'Booking saved and confirmation email sent', 'bookingID' => $bookingID]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$conn->close();
?>
