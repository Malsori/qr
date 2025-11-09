<?php
// checkin.php
session_start();

$host = "localhost";
$dbUser = "root";      // Local MySQL username
$dbPass = "";          // Local MySQL password (usually empty)
$dbName = "qroom"; // Your database name

// Create connection
$conn = new mysqli($host, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['bookingID'])) {
    die("Invalid request.");
}

$bookingID = intval($_GET['bookingID']);

// Update booking to checked in
$sql = "UPDATE bookings SET checked_in = 1 WHERE id = $bookingID LIMIT 1";
if ($conn->query($sql)) {
    $statusMessage = "Booking #$bookingID checked in successfully!";
} else {
    $statusMessage = "Error updating booking: " . $conn->error;
}
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Check-in</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
    /* Paste your full animation CSS here */
    body { box-sizing: border-box; margin: 0; padding: 0; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #30343F 0%, #1a1d26 100%); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
    html { height: 100%; }
    .container { text-align: center; position: relative; }
    .title { color: #F5B700; font-size: 2.5rem; font-weight: bold; margin-bottom: 3rem; text-shadow: 0 0 20px rgba(245, 183, 0, 0.5); }
    .lock-container { position: relative; width: 200px; height: 250px; margin: 0 auto; }
    .lock-body { width: 120px; height: 140px; background: #00798C; border-radius: 20px; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); box-shadow: 0 10px 40px rgba(0, 121, 140, 0.4); }
    .lock-shackle { width: 80px; height: 90px; border: 15px solid #00798C; border-bottom: none; border-radius: 50px 50px 0 0; position: absolute; top: 0; left: 50%; transform: translateX(-50%); transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55); transform-origin: bottom center; }
    .lock-shackle.open { transform: translateX(-50%) translateX(-45px) rotate(-45deg); }
    .keyhole { width: 20px; height: 20px; background: #30343F; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
    .keyhole::after { content: ''; width: 8px; height: 25px; background: #30343F; position: absolute; top: 15px; left: 50%; transform: translateX(-50%); clip-path: polygon(30% 0%, 70% 0%, 100% 100%, 0% 100%); }
    .sparkle { position: absolute; width: 8px; height: 8px; background: #F5B700; clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); opacity: 0; animation: sparkle 1.5s ease-in-out infinite; }
    @keyframes sparkle { 0%,100% { opacity:0; transform: scale(0) rotate(0deg); } 50% { opacity:1; transform: scale(1.5) rotate(180deg); } }
    .sparkle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
    .sparkle:nth-child(2) { top: 30%; right: 15%; animation-delay: 0.3s; }
    .sparkle:nth-child(3) { top: 60%; left: 5%; animation-delay: 0.6s; }
    .sparkle:nth-child(4) { top: 70%; right: 10%; animation-delay: 0.9s; }
    .sparkle:nth-child(5) { top: 40%; left: 50%; animation-delay: 0.45s; }
    .sparkle:nth-child(6) { top: 15%; right: 30%; animation-delay: 0.75s; }
    .glow { position: absolute; width: 150px; height: 150px; background: radial-gradient(circle, rgba(242, 95, 92, 0.4) 0%, transparent 70%); border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0; transition: opacity 0.8s ease; }
    .glow.active { opacity: 1; animation: pulse 2s ease-in-out infinite; }
    @keyframes pulse { 0%,100% { transform: translate(-50%, -50%) scale(1); } 50% { transform: translate(-50%, -50%) scale(1.2); } }
    .unlock-button { margin-top: 4rem; padding: 1rem 2.5rem; font-size: 1.2rem; font-weight: bold; color: #30343F; background: #F5B700; border: none; border-radius: 50px; cursor: pointer; box-shadow: 0 5px 20px rgba(245, 183, 0, 0.4); transition: all 0.3s ease; }
    .unlock-button:hover { background: #ffc61a; transform: translateY(-2px); box-shadow: 0 7px 25px rgba(245, 183, 0, 0.6); }
    .unlock-button:active { transform: translateY(0); }
  </style>
 </head>
 <body>
  <div class="container">
   <h1 class="title" id="title"><?php echo htmlspecialchars($statusMessage); ?></h1>
   <div class="lock-container">
    <div class="glow" id="glow"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="lock-shackle" id="shackle"></div>
    <div class="lock-body">
     <div class="keyhole"></div>
    </div>
   </div>
  </div>
  <script>
    const shackle = document.getElementById('shackle');
    const glow = document.getElementById('glow');

    // Optionally auto-open lock animation after successful check-in
    window.addEventListener('load', () => {
      shackle.classList.add('open');
      glow.classList.add('active');
      unlockBtn.textContent = 'Lock';
    });
  </script>
 </body>
</html>
