<?php
require_once 'db.php';

// User login check
function loginUser($email, $password){
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])){
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return true;
    }
    return false;
}

// Register new user
function registerUser($data){
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, surname, birthday, passport_number) VALUES (?, ?, ?, ?, ?, ?)");
    $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
    return $stmt->execute([$data['email'], $hashed, $data['name'], $data['surname'], $data['birthday'], $data['passport_number']]);
}

// Create booking
function createBooking($userID, $hotelName, $checkIn, $checkOut, $price, $nrPeople){
    global $pdo;
    $qrCode = bin2hex(random_bytes(8)); // simple QR code string
    $stmt = $pdo->prepare("INSERT INTO bookings (userID, hotel_name, check_in, check_out, price, nr_people, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userID, $hotelName, $checkIn, $checkOut, $price, $nrPeople, $qrCode]);
    return $qrCode;
}

// Get user bookings
function getUserBookings($userID){
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE userID=?");
    $stmt->execute([$userID]);
    return $stmt->fetchAll();
}

// Check QR for LockAnimation
function validateQR($qr){
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE qr_code=?");
    $stmt->execute([$qr]);
    return $stmt->fetch();
}

// Mark check-in
function markCheckedIn($bookingID){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE bookings SET checked_in=1 WHERE id=?");
    $stmt->execute([$bookingID]);
}
?>
