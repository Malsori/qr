<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation Confirmation</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
    body {
      box-sizing: border-box;
      margin: 0;
      padding: 20px;
      height: 100%;
      background: linear-gradient(135deg, #30343F 0%, #1a1d26 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
    }

    html {
      height: 100%;
    }

    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .header {
      background: linear-gradient(135deg, #00798C 0%, #005a6b 100%);
      padding: 40px 30px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(245, 183, 0, 0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .header-content {
      position: relative;
      z-index: 2;
    }

    .hotel-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 20px;
      background: #F5B700;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 30px rgba(245, 183, 0, 0.4);
    }

    .hotel-icon svg {
      width: 30px;
      height: 30px;
      fill: #30343F;
    }

    .header h1 {
      color: #ffffff;
      font-size: 2.2rem;
      font-weight: bold;
      margin: 0 0 10px 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .header p {
      color: #F5B700;
      font-size: 1.1rem;
      margin: 0;
      font-weight: 500;
    }

    .content {
      padding: 40px 30px;
    }

    .confirmation-badge {
      background: linear-gradient(135deg, #F25F5C 0%, #e04845 100%);
      color: white;
      padding: 15px 30px;
      border-radius: 50px;
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
      font-size: 1.1rem;
      box-shadow: 0 5px 20px rgba(242, 95, 92, 0.3);
    }

    .guest-info {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      padding: 25px;
      border-radius: 15px;
      margin-bottom: 30px;
      border-left: 5px solid #F5B700;
    }

    .guest-info h2 {
      color: #30343F;
      font-size: 1.4rem;
      margin: 0 0 15px 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .guest-info h2::before {
      content: '👤';
      font-size: 1.2rem;
    }

    .guest-name {
      font-size: 1.3rem;
      font-weight: bold;
      color: #00798C;
      margin-bottom: 10px;
    }

    .confirmation-number {
      font-size: 1.1rem;
      color: #30343F;
      background: #F5B700;
      padding: 8px 15px;
      border-radius: 25px;
      display: inline-block;
      font-weight: bold;
    }

    .reservation-details {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .detail-card {
      background: #ffffff;
      border: 2px solid #e9ecef;
      border-radius: 15px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .detail-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #F5B700, #00798C, #F25F5C);
    }

    .detail-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .detail-card h3 {
      color: #30343F;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin: 0 0 10px 0;
      font-weight: 600;
    }

    .detail-card p {
      color: #00798C;
      font-size: 1.2rem;
      font-weight: bold;
      margin: 0;
    }

    .hotel-info {
      background: linear-gradient(135deg, #00798C 0%, #005a6b 100%);
      color: white;
      padding: 25px;
      border-radius: 15px;
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
    }

    .hotel-info::after {
      content: '🏨';
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 2rem;
      opacity: 0.3;
    }

    .hotel-info h2 {
      margin: 0 0 15px 0;
      font-size: 1.4rem;
    }

    .hotel-info p {
      margin: 5px 0;
      opacity: 0.9;
    }

    .cta-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .cta-button {
      background: linear-gradient(135deg, #F5B700 0%, #ffc61a 100%);
      color: #30343F;
      padding: 15px 40px;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      box-shadow: 0 5px 20px rgba(245, 183, 0, 0.4);
    }

    .cta-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 7px 25px rgba(245, 183, 0, 0.6);
    }

    .footer {
      background: #30343F;
      color: white;
      padding: 30px;
      text-align: center;
    }

    .footer h3 {
      color: #F5B700;
      margin: 0 0 15px 0;
      font-size: 1.2rem;
    }

    .footer p {
      margin: 5px 0;
      opacity: 0.8;
    }

    .social-links {
      margin-top: 20px;
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .social-link {
      width: 40px;
      height: 40px;
      background: #F5B700;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .social-link:hover {
      transform: scale(1.1);
      background: #ffc61a;
    }

    .divider {
      height: 2px;
      background: linear-gradient(90deg, transparent, #F5B700, transparent);
      margin: 20px 0;
    }

    @media (max-width: 600px) {
      .email-container {
        margin: 10px;
        border-radius: 15px;
      }
      
      .reservation-details {
        grid-template-columns: 1fr;
      }
      
      .header h1 {
        font-size: 1.8rem;
      }
      
      .content {
        padding: 30px 20px;
      }
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>
  <div class="email-container">
   <div class="header">
    <div class="header-content">
     <div class="hotel-icon">
      <svg viewbox="0 0 24 24"><path d="M7,13H9V19H7V13M11,13H13V19H11V13M15,13H17V19H15V13M17,11V9H15V7H17V5H19V11H17M7,11H5V5H7V7H9V9H7V11M19,19V21H5V19H19M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" />
      </svg>
     </div>
     <h1 id="hotel-name">Grand Luxe Hotel</h1>
     <p>Your reservation is confirmed!</p>
    </div>
   </div>
   <div class="content">
    <div class="confirmation-badge">
     ✅ Reservation Confirmed
    </div>
    <div class="guest-info">
     <h2>Guest Information</h2>
     <div class="guest-name" id="guest-name">
      John Smith
     </div>
     <div class="confirmation-number">
      Confirmation: <span id="confirmation-number">#HTL-2024-001</span>
     </div>
    </div>
    <div class="reservation-details">
     <div class="detail-card">
      <h3>Check-in</h3>
      <p id="check-in">March 15, 2024</p>
     </div>
     <div class="detail-card">
      <h3>Check-out</h3>
      <p id="check-out">March 18, 2024</p>
     </div>
     <div class="detail-card">
      <h3>Room Type</h3>
      <p id="room-type">Deluxe Suite</p>
     </div>
     <div class="detail-card">
      <h3>Guests</h3>
      <p>2 Adults</p>
     </div>
    </div>
    <div class="hotel-info">
     <h2>Hotel Details</h2>
     <p><strong id="hotel-name-info">Grand Luxe Hotel</strong></p>
     <p id="hotel-address">123 Luxury Avenue, Paradise City, PC 12345</p>
    </div>
    <div class="divider"></div>
    <div class="cta-section"><a href="#" class="cta-button">View Full Reservation</a>
    </div>
   </div>
   <div class="footer">
    <h3>Need Assistance?</h3>
    <p>📞 <span id="contact-phone">+1 (555) 123-4567</span></p>
    <p>✉️ <span id="contact-email">reservations@grandluxe.com</span></p>
    <div class="social-links"><a href="#" class="social-link">📘</a> <a href="#" class="social-link">📷</a> <a href="#" class="social-link">🐦</a>
    </div>
    <div class="divider"></div>
    <p style="font-size: 0.9rem; opacity: 0.7;">Thank you for choosing us for your stay!</p>
   </div>
  </div>
  <script>
    const defaultConfig = {
      hotel_name: "Grand Luxe Hotel",
      hotel_address: "123 Luxury Avenue, Paradise City, PC 12345",
      guest_name: "John Smith",
      confirmation_number: "#HTL-2024-001",
      room_type: "Deluxe Suite",
      check_in: "March 15, 2024",
      check_out: "March 18, 2024",
      contact_phone: "+1 (555) 123-4567",
      contact_email: "reservations@grandluxe.com",
      primary_color: "#F5B700",
      secondary_color: "#00798C",
      accent_color: "#F25F5C",
      background_color: "#30343F"
    };

    async function onConfigChange(config) {
      // Update hotel information
      const hotelNameElements = document.querySelectorAll('#hotel-name, #hotel-name-info');
      hotelNameElements.forEach(el => {
        el.textContent = config.hotel_name || defaultConfig.hotel_name;
      });

      document.getElementById('hotel-address').textContent = config.hotel_address || defaultConfig.hotel_address;

      // Update guest information
      document.getElementById('guest-name').textContent = config.guest_name || defaultConfig.guest_name;
      document.getElementById('confirmation-number').textContent = config.confirmation_number || defaultConfig.confirmation_number;

      // Update reservation details
      document.getElementById('room-type').textContent = config.room_type || defaultConfig.room_type;
      document.getElementById('check-in').textContent = config.check_in || defaultConfig.check_in;
      document.getElementById('check-out').textContent = config.check_out || defaultConfig.check_out;

      // Update contact information
      document.getElementById('contact-phone').textContent = config.contact_phone || defaultConfig.contact_phone;
      document.getElementById('contact-email').textContent = config.contact_email || defaultConfig.contact_email;

      // Update colors
      const header = document.querySelector('.header');
      const hotelInfo = document.querySelector('.hotel-info');
      const confirmationBadge = document.querySelector('.confirmation-badge');
      const ctaButton = document.querySelector('.cta-button');
      const footer = document.querySelector('.footer');
      const hotelIcon = document.querySelector('.hotel-icon');
      const confirmationNumberSpan = document.querySelector('.confirmation-number');

      header.style.background = `linear-gradient(135deg, ${config.secondary_color || defaultConfig.secondary_color} 0%, #005a6b 100%)`;
      hotelInfo.style.background = `linear-gradient(135deg, ${config.secondary_color || defaultConfig.secondary_color} 0%, #005a6b 100%)`;
      confirmationBadge.style.background = `linear-gradient(135deg, ${config.accent_color || defaultConfig.accent_color} 0%, #e04845 100%)`;
      ctaButton.style.background = `linear-gradient(135deg, ${config.primary_color || defaultConfig.primary_color} 0%, #ffc61a 100%)`;
      footer.style.background = config.background_color || defaultConfig.background_color;
      hotelIcon.style.background = config.primary_color || defaultConfig.primary_color;
      confirmationNumberSpan.style.background = config.primary_color || defaultConfig.primary_color;

      document.body.style.background = `linear-gradient(135deg, ${config.background_color || defaultConfig.background_color} 0%, #1a1d26 100%)`;
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
        ["hotel_name", config.hotel_name || defaultConfig.hotel_name],
        ["hotel_address", config.hotel_address || defaultConfig.hotel_address],
        ["guest_name", config.guest_name || defaultConfig.guest_name],
        ["confirmation_number", config.confirmation_number || defaultConfig.confirmation_number],
        ["room_type", config.room_type || defaultConfig.room_type],
        ["check_in", config.check_in || defaultConfig.check_in],
        ["check_out", config.check_out || defaultConfig.check_out],
        ["contact_phone", config.contact_phone || defaultConfig.contact_phone],
        ["contact_email", config.contact_email || defaultConfig.contact_email]
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
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99b629cb16968eb7',t:'MTc2MjYxNjM2OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>