<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

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


$user_id = $_SESSION['user_id'];

// Fetch bookings for this user
$stmt = $conn->prepare("SELECT id, hotel_name, check_in, check_out, nr_people, price, checked_in FROM bookings WHERE userID = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bookings - Travel Dashboard</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
    body {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #30343F 0%, #1a1d26 100%);
      color: #30343F;
      min-height: 100%;
      position: relative;
      overflow-x: hidden;
    }

    html {
      height: 100%;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at 25% 25%, rgba(245, 183, 0, 0.06) 0%, transparent 50%),
                  radial-gradient(circle at 75% 75%, rgba(0, 121, 140, 0.06) 0%, transparent 50%),
                  radial-gradient(circle at 50% 10%, rgba(242, 95, 92, 0.04) 0%, transparent 50%);
      animation: float 30s ease-in-out infinite;
      pointer-events: none;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-25px) rotate(1deg); }
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      position: relative;
      z-index: 2;
    }

    .header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .page-title {
      font-size: clamp(2.5rem, 5vw, 3.5rem);
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 0.5rem;
      background: linear-gradient(135deg, #F5B700 0%, #00798C 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-shadow: 0 0 30px rgba(245, 183, 0, 0.3);
    }

    .page-subtitle {
      font-size: 1.2rem;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 1rem;
    }

    .booking-stats {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-bottom: 3rem;
      flex-wrap: wrap;
    }

    .stat-item {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border-radius: 16px;
      padding: 1rem 2rem;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: #F5B700;
      display: block;
    }

    .stat-label {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .bookings-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .booking-card {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 2rem;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .booking-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, #F5B700, #00798C, #F25F5C);
    }

    .booking-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
    }

    .booking-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1.5rem;
    }

    .booking-status {
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-confirmed {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
    }

    .status-upcoming {
      background: linear-gradient(135deg, #F5B700, #ffc61a);
      color: #30343F;
    }

    .status-checkin {
      background: linear-gradient(135deg, #00798C, #005a6b);
      color: white;
    }

    .booking-reference {
      font-size: 0.9rem;
      color: #666;
      font-weight: 600;
    }

    .hotel-info {
      margin-bottom: 2rem;
    }

    .hotel-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: #30343F;
      margin-bottom: 0.5rem;
    }

    .hotel-location {
      color: #666;
      font-size: 1rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .location-icon {
      color: #F25F5C;
    }

    .booking-details {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .detail-group {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 1rem;
    }

    .detail-label {
      font-size: 0.8rem;
      color: #666;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .detail-value {
      font-size: 1rem;
      font-weight: 600;
      color: #30343F;
    }

    .qr-section {
      display: flex;
      align-items: center;
      gap: 1.5rem;
      margin-bottom: 2rem;
      padding: 1.5rem;
      background: linear-gradient(135deg, rgba(245, 183, 0, 0.1), rgba(0, 121, 140, 0.1));
      border-radius: 16px;
      border: 2px dashed rgba(245, 183, 0, 0.3);
    }

    .qr-placeholder {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      border: 3px solid #F5B700;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: #F5B700;
      position: relative;
      overflow: hidden;
      flex-shrink: 0;
    }

    .qr-placeholder::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(45deg, #F5B700, #ffc61a, #F5B700);
      background-size: 200% 200%;
      border-radius: 15px;
      z-index: -1;
      animation: qr-glow 2s ease-in-out infinite;
    }

    @keyframes qr-glow {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    .qr-info {
      flex: 1;
    }

    .qr-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #30343F;
      margin-bottom: 0.5rem;
    }

    .qr-description {
      color: #666;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .booking-actions {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .action-button {
      flex: 1;
      min-width: 140px;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.9rem;
    }

    .primary-button {
      background: linear-gradient(135deg, #00798C, #005a6b);
      color: white;
    }

    .secondary-button {
      background: rgba(245, 183, 0, 0.1);
      color: #F5B700;
      border: 2px solid #F5B700;
    }

    .action-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .primary-button:hover {
      box-shadow: 0 8px 25px rgba(0, 121, 140, 0.3);
    }

    .secondary-button:hover {
      background: #F5B700;
      color: #30343F;
    }

    .guest-info {
      grid-column: 1 / -1;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(135deg, rgba(0, 121, 140, 0.1), rgba(245, 183, 0, 0.1));
      border-radius: 12px;
      padding: 1rem;
    }

    .guest-details {
      display: flex;
      gap: 2rem;
    }

    .guest-item {
      text-align: center;
    }

    .guest-number {
      font-size: 1.2rem;
      font-weight: 700;
      color: #00798C;
      display: block;
    }

    .guest-label {
      font-size: 0.8rem;
      color: #666;
      text-transform: uppercase;
    }

    .total-price {
      text-align: right;
    }

    .price-amount {
      font-size: 1.5rem;
      font-weight: 800;
      color: #F25F5C;
      display: block;
    }

    .price-label {
      font-size: 0.8rem;
      color: #666;
      text-transform: uppercase;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        padding: 1rem;
      }

      .bookings-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }

      .booking-card {
        padding: 1.5rem;
      }

      .booking-details {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .qr-section {
        flex-direction: column;
        text-align: center;
      }

      .booking-actions {
        flex-direction: column;
      }

      .action-button {
        min-width: auto;
      }

      .guest-info {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }

      .guest-details {
        justify-content: center;
      }

      .booking-stats {
        gap: 1rem;
      }

      .stat-item {
        padding: 0.75rem 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .booking-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }

      .guest-details {
        flex-direction: column;
        gap: 1rem;
      }
    }

    nav{
      position: sticky;
      top: 0;
      width: 100%;
      background-color: white;
      display: flex;
      padding: 10px 5%;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      z-index: 999;
    }

    nav img {
      width: 150px
    }

    nav>div{
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .cta-button{
      display:inline-block;
      background: linear-gradient(135deg, var(--primary-color) 0%, #ffc61a 100%);
      color: #30343F;
      padding:1rem 3rem;
      border-radius:50px;
      text-decoration:none;
      font-weight:700;
      font-size:1.1rem;
      transition: all .3s ease;
      box-shadow: 0 10px 40px rgba(245,183,0,0.3);
      position:relative;
      overflow:hidden;
    }
    .cta-button::before{
      content:"";
      position:absolute; top:0; left:-100%; width:100%; height:100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left .5s;
    }
    .cta-button:hover::before{ left:100%; }
    .cta-button:hover{ transform:translateY(-2px); box-shadow: 0 15px 50px rgba(245,183,0,0.4); }
  </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>

  <nav>
    <img src="./images/logo.svg" alt="">
    <div>
      <a href="Prompt.php">Generate Prompt</a>
      <a href="MyBookings.php">My Bookings</a>
      <a href="logout.php" class="cta-button" style="padding: 13px 25px !important;">Logout</a>
    </div>
  </nav>
  <div class="container">
    <div class="header">
        <h1 class="page-title" id="page-title">My Bookings</h1>
        <p class="page-subtitle" id="page-subtitle">Manage your upcoming trips and access your digital check-in codes</p>
        <div class="booking-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo count($bookings); ?></span>
                <span class="stat-label">Active Bookings</span>
            </div>
        </div>
    </div>
    <div class="bookings-grid">
        <?php foreach ($bookings as $booking): ?>
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-status <?php echo $booking['checked_in'] ? 'status-checkin' : 'status-upcoming'; ?>">
                        <?php echo $booking['checked_in'] ? 'Checked In' : 'Upcoming'; ?>
                    </div>
                    <div class="booking-reference">
                        Ref: #TRV-<?php echo str_pad($booking['id'], 4, '0', STR_PAD_LEFT); ?>
                    </div>
                </div>
                <div class="hotel-info">
                    <h3 class="hotel-name"><?php echo htmlspecialchars($booking['hotel_name']); ?></h3>
                    <div class="hotel-location"><span class="location-icon">📍</span> Location unknown</div>
                </div>
                <div class="booking-details">
                    <div class="detail-group">
                        <div class="detail-label">Check-in</div>
                        <div class="detail-value"><?php echo date("M d, Y", strtotime($booking['check_in'])); ?></div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Check-out</div>
                        <div class="detail-value"><?php echo date("M d, Y", strtotime($booking['check_out'])); ?></div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Guests</div>
                        <div class="detail-value"><?php echo $booking['nr_people']; ?> People</div>
                    </div>
                    <div class="guest-info">
                        <div class="total-price">
                            <span class="price-amount">$<?php echo number_format($booking['price'], 2); ?></span>
                            <span class="price-label">Total</span>
                        </div>
                    </div>
                </div>
                <?php
$bookingID = $booking['id'];
$qrLink = "https://qr.h-solutions.net/public/checkin.php?bookingID=" . $bookingID;
$qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrLink);
?>
<div class="qr-section">
  <div class="qr-placeholder">
    <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code for booking #<?= $bookingID ?>" style="width:100px;height:100px;border-radius:12px;border:3px solid <?= htmlspecialchars($primaryColor) ?>;">
  </div>
  <div class="qr-info">
    <div class="qr-title">Digital Check‑in Code</div>
    <div class="qr-description">
      Scan this QR code to check in instantly.
    </div>
  </div>
</div>


                <div class="booking-actions">
                    <button class="action-button primary-button">View Details</button>
                    <button class="action-button secondary-button">Contact Hotel</button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($bookings)): ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</div>
  <script>
    const defaultConfig = {
      page_title: "My Bookings",
      page_subtitle: "Manage your upcoming trips and access your digital check-in codes",
      hotel_1_name: "The Grand Boutique Hotel",
      hotel_1_location: "Downtown Paris, France",
      hotel_2_name: "Seaside Resort & Spa",
      hotel_2_location: "Santorini, Greece",
      hotel_3_name: "Mountain Lodge Retreat",
      hotel_3_location: "Aspen, Colorado, USA",
      view_details_text: "View Details",
      contact_hotel_text: "Contact Hotel",
      primary_color: "#F5B700",
      secondary_color: "#00798C",
      accent_color: "#F25F5C",
      background_color: "#30343F"
    };

    async function onConfigChange(config) {
      // Update text content
      document.getElementById('page-title').textContent = config.page_title || defaultConfig.page_title;
      document.getElementById('page-subtitle').textContent = config.page_subtitle || defaultConfig.page_subtitle;
      
      document.getElementById('hotel-1-name').textContent = config.hotel_1_name || defaultConfig.hotel_1_name;
      document.getElementById('hotel-1-location').textContent = config.hotel_1_location || defaultConfig.hotel_1_location;
      document.getElementById('hotel-2-name').textContent = config.hotel_2_name || defaultConfig.hotel_2_name;
      document.getElementById('hotel-2-location').textContent = config.hotel_2_location || defaultConfig.hotel_2_location;
      document.getElementById('hotel-3-name').textContent = config.hotel_3_name || defaultConfig.hotel_3_name;
      document.getElementById('hotel-3-location').textContent = config.hotel_3_location || defaultConfig.hotel_3_location;

      // Update button text
      const viewDetailsButtons = document.querySelectorAll('[id^="view-details-"]');
      viewDetailsButtons.forEach(btn => {
        btn.textContent = config.view_details_text || defaultConfig.view_details_text;
      });

      const contactHotelButtons = document.querySelectorAll('[id^="contact-hotel-"]');
      contactHotelButtons.forEach(btn => {
        btn.textContent = config.contact_hotel_text || defaultConfig.contact_hotel_text;
      });

      // Update colors
      const primaryColor = config.primary_color || defaultConfig.primary_color;
      const secondaryColor = config.secondary_color || defaultConfig.secondary_color;
      const accentColor = config.accent_color || defaultConfig.accent_color;
      const backgroundColor = config.background_color || defaultConfig.background_color;

      // Update page title gradient
      const pageTitle = document.getElementById('page-title');
      pageTitle.style.background = `linear-gradient(135deg, ${primaryColor} 0%, ${secondaryColor} 100%)`;
      pageTitle.style.webkitBackgroundClip = 'text';
      pageTitle.style.webkitTextFillColor = 'transparent';
      pageTitle.style.backgroundClip = 'text';

      // Update body background
      document.body.style.background = `linear-gradient(135deg, ${backgroundColor} 0%, #1a1d26 100%)`;

      // Update QR placeholders
      const qrPlaceholders = document.querySelectorAll('.qr-placeholder');
      qrPlaceholders.forEach(placeholder => {
        placeholder.style.borderColor = primaryColor;
        placeholder.style.color = primaryColor;
      });

      // Update primary buttons
      const primaryButtons = document.querySelectorAll('.primary-button');
      primaryButtons.forEach(btn => {
        btn.style.background = `linear-gradient(135deg, ${secondaryColor}, #005a6b)`;
      });

      // Update secondary buttons
      const secondaryButtons = document.querySelectorAll('.secondary-button');
      secondaryButtons.forEach(btn => {
        btn.style.color = primaryColor;
        btn.style.borderColor = primaryColor;
      });

      // Update stat numbers
      const statNumbers = document.querySelectorAll('.stat-number');
      statNumbers.forEach(num => {
        num.style.color = primaryColor;
      });

      // Update price amounts
      const priceAmounts = document.querySelectorAll('.price-amount');
      priceAmounts.forEach(price => {
        price.style.color = accentColor;
      });

      // Update guest numbers
      const guestNumbers = document.querySelectorAll('.guest-number');
      guestNumbers.forEach(num => {
        num.style.color = secondaryColor;
      });
    }

    function mapToCapabilities(config) {
      return {
        recolorables: [
          {
            get: () => config.background_color || defaultConfig.background_color,
            set: (value) => {
              config.background_color = value;
              window.elementSdk.setConfig({ background_color: value });
            }
          },
          {
            get: () => config.secondary_color || defaultConfig.secondary_color,
            set: (value) => {
              config.secondary_color = value;
              window.elementSdk.setConfig({ secondary_color: value });
            }
          },
          {
            get: () => config.primary_color || defaultConfig.primary_color,
            set: (value) => {
              config.primary_color = value;
              window.elementSdk.setConfig({ primary_color: value });
            }
          },
          {
            get: () => config.accent_color || defaultConfig.accent_color,
            set: (value) => {
              config.accent_color = value;
              window.elementSdk.setConfig({ accent_color: value });
            }
          }
        ],
        borderables: [],
        fontEditable: undefined,
        fontSizeable: undefined
      };
    }

    function mapToEditPanelValues(config) {
      return new Map([
        ["page_title", config.page_title || defaultConfig.page_title],
        ["page_subtitle", config.page_subtitle || defaultConfig.page_subtitle],
        ["hotel_1_name", config.hotel_1_name || defaultConfig.hotel_1_name],
        ["hotel_1_location", config.hotel_1_location || defaultConfig.hotel_1_location],
        ["hotel_2_name", config.hotel_2_name || defaultConfig.hotel_2_name],
        ["hotel_2_location", config.hotel_2_location || defaultConfig.hotel_2_location],
        ["hotel_3_name", config.hotel_3_name || defaultConfig.hotel_3_name],
        ["hotel_3_location", config.hotel_3_location || defaultConfig.hotel_3_location],
        ["view_details_text", config.view_details_text || defaultConfig.view_details_text],
        ["contact_hotel_text", config.contact_hotel_text || defaultConfig.contact_hotel_text]
      ]);
    }

    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange,
        mapToCapabilities,
        mapToEditPanelValues
      });
    }

    // Button functionality
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('action-button')) {
        const buttonText = e.target.textContent;
        const hotelCard = e.target.closest('.booking-card');
        const hotelName = hotelCard.querySelector('.hotel-name').textContent;
        
        if (buttonText.includes('View Details') || buttonText.includes('view details')) {
          showCustomAlert(`Opening detailed view for ${hotelName}...`);
        } else if (buttonText.includes('Contact') || buttonText.includes('contact')) {
          showCustomAlert(`Opening contact options for ${hotelName}...`);
        }
      }
    });

    // Custom alert function
    function showCustomAlert(message) {
      const existingAlert = document.querySelector('.custom-alert');
      if (existingAlert) {
        existingAlert.remove();
      }

      const alert = document.createElement('div');
      alert.className = 'custom-alert';
      alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #00798C, #005a6b);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        font-weight: 600;
        max-width: 300px;
        animation: slideIn 0.3s ease;
      `;
      alert.textContent = message;

      if (!document.querySelector('#alert-styles')) {
        const style = document.createElement('style');
        style.id = 'alert-styles';
        style.textContent = `
          @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
          }
        `;
        document.head.appendChild(style);
      }

      document.body.appendChild(alert);

      setTimeout(() => {
        alert.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => alert.remove(), 300);
      }, 3000);
    }
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99b66324d3568efa',t:'MTc2MjYxODcxNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>