<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI Trip Planner - Describe Your Journey</title>
  <script src="/_sdk/element_sdk.js"></script>
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="css/prompt.css?v=<?php echo time(); ?>" />

  <style>
   
  </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>
  <nav>
    <img src="images/logo.svg" alt="">
    <div>
      <a href="Prompt.php">Generate Prompt</a>
      <a href="MyBookings.php">My Bookings</a>
      <a href="logout.php" class="cta-button" style="padding: 13px 25px !important;">Logout</a>
    </div>
  </nav>
  <div class="container">
   <div class="header">
    <h1 class="page-title" id="page-title">Describe Your Perfect Trip</h1>
    <p class="subtitle">Let our AI create the perfect travel experience tailored just for you</p>
   </div>
   <div class="prompt-section"><label class="prompt-label">
     <div class="ai-icon">
      🤖
     </div> Tell us about your dream trip </label> <textarea class="prompt-textarea" id="trip-prompt" placeholder="I want to visit Paris for 5 days in spring. I love art museums, cozy cafes, and romantic walks. My budget is around $2000 and I prefer boutique hotels with character..."></textarea> <button class="generate-button" id="generate-button">Generate AI Suggestions</button>
   </div>
   <div class="suggestions-section">
    <h2 class="section-title">AI Hotel Recommendations</h2>
    <div class="hotel-cards"><!-- Basic Card -->
     <div class="hotel-card">
      <div class="card-icon basic-icon">
       🏨
      </div>
      <h3 class="card-title" id="basic-title">Budget Friendly</h3>
      <p class="card-description" id="basic-description">Comfortable accommodations that won't break the bank. Clean, safe, and well-located properties with essential amenities for the practical traveler.</p><button class="select-button basic-button" onclick="showConfirmation('basic')"> Select Basic Option </button>
     </div><!-- Best Value Card -->
     <div class="hotel-card best-value">
      <div class="card-badge">
       RECOMMENDED
      </div>
      <div class="card-icon value-icon">
       ⭐
      </div>
      <h3 class="card-title" id="value-title">Best Value</h3>
      <p class="card-description" id="value-description">The perfect balance of comfort, location, and price. Premium amenities at mid-range prices with excellent reviews and prime locations.</p><button class="select-button value-button" onclick="showConfirmation('value')"> Select Best Value </button>
     </div><!-- Premium Card -->
     <div class="hotel-card">
      <div class="card-icon premium-icon">
       💎
      </div>
      <h3 class="card-title" id="premium-title">Luxury Experience</h3>
      <p class="card-description" id="premium-description">Indulge in the finest accommodations with world-class service, premium locations, and exclusive amenities. Price is no object for the ultimate experience.</p><button class="select-button premium-button" onclick="showConfirmation('premium')"> Select Premium Option </button>
     </div>
    </div>
   </div>
  </div><!-- Confirmation Popup -->
  <div class="popup-overlay" id="confirmation-popup">
   <div class="popup">
    <div class="popup-icon">
     ❓
    </div>
    <h3 class="popup-title">Confirm Your Selection</h3>
    <p class="popup-message" id="confirm-message">Are you sure you want to proceed with this hotel option? Our AI will start planning your perfect trip!</p>
    <div class="popup-buttons"><button class="popup-button confirm-button" onclick="confirmSelection()">Yes, Continue</button> <button class="popup-button cancel-button" onclick="closePopup()">Cancel</button>
    </div>
   </div>
  </div><!-- Success Popup -->
  <div class="popup-overlay success-popup" id="success-popup">
   <div class="popup">
    <div class="popup-icon">
     🎉
    </div>
    <h3 class="popup-title">Success!</h3>
    <p class="popup-message" id="success-message">Perfect! Your AI travel assistant is now crafting your personalized trip. You'll receive your complete itinerary shortly!</p>
    <div class="popup-buttons"><button class="popup-button confirm-button" onclick="closePopup()">Awesome!</button>
    </div>
   </div>
  </div>
  <script>
    const defaultConfig = {
      page_title: "Describe Your Perfect Trip",
      prompt_placeholder: "I want to visit Paris for 5 days in spring. I love art museums, cozy cafes, and romantic walks. My budget is around $2000 and I prefer boutique hotels with character...",
      generate_button: "Generate AI Suggestions",
      basic_title: "Budget Friendly",
      basic_description: "Comfortable accommodations that won't break the bank. Clean, safe, and well-located properties with essential amenities for the practical traveler.",
      value_title: "Best Value",
      value_description: "The perfect balance of comfort, location, and price. Premium amenities at mid-range prices with excellent reviews and prime locations.",
      premium_title: "Luxury Experience",
      premium_description: "Indulge in the finest accommodations with world-class service, premium locations, and exclusive amenities. Price is no object for the ultimate experience.",
      confirm_message: "Are you sure you want to proceed with this hotel option? Our AI will start planning your perfect trip!",
      success_message: "Perfect! Your AI travel assistant is now crafting your personalized trip. You'll receive your complete itinerary shortly!",
      primary_color: "#F5B700",
      secondary_color: "#00798C",
      accent_color: "#F25F5C",
      background_color: "#30343F"
    };

    let selectedOption = '';

    async function onConfigChange(config) {
      // Update text content
      document.getElementById('page-title').textContent = config.page_title || defaultConfig.page_title;
      document.getElementById('trip-prompt').placeholder = config.prompt_placeholder || defaultConfig.prompt_placeholder;
      document.getElementById('generate-button').textContent = config.generate_button || defaultConfig.generate_button;
      
      document.getElementById('basic-title').textContent = config.basic_title || defaultConfig.basic_title;
      document.getElementById('basic-description').textContent = config.basic_description || defaultConfig.basic_description;
      document.getElementById('value-title').textContent = config.value_title || defaultConfig.value_title;
      document.getElementById('value-description').textContent = config.value_description || defaultConfig.value_description;
      document.getElementById('premium-title').textContent = config.premium_title || defaultConfig.premium_title;
      document.getElementById('premium-description').textContent = config.premium_description || defaultConfig.premium_description;
      
      document.getElementById('confirm-message').textContent = config.confirm_message || defaultConfig.confirm_message;
      document.getElementById('success-message').textContent = config.success_message || defaultConfig.success_message;

      // Update colors
      const primaryColor = config.primary_color || defaultConfig.primary_color;
      const secondaryColor = config.secondary_color || defaultConfig.secondary_color;
      const accentColor = config.accent_color || defaultConfig.accent_color;
      const backgroundColor = config.background_color || defaultConfig.background_color;

      // Update page title gradient
      const pageTitle = document.getElementById('page-title');
      pageTitle.style.background = `linear-gradient(135deg, ${primaryColor} 0%, ${secondaryColor} 50%, ${accentColor} 100%)`;
      pageTitle.style.webkitBackgroundClip = 'text';
      pageTitle.style.webkitTextFillColor = 'transparent';
      pageTitle.style.backgroundClip = 'text';

      // Update body background
      document.body.style.background = `linear-gradient(135deg, ${backgroundColor} 0%, #1a1d26 100%)`;

      // Update generate button
      const generateButton = document.getElementById('generate-button');
      generateButton.style.background = `linear-gradient(135deg, ${secondaryColor} 0%, #005a6b 100%)`;

      // Update card icons and buttons
      const valueIcon = document.querySelector('.value-icon');
      valueIcon.style.background = `linear-gradient(135deg, ${primaryColor}, #ffc61a)`;

      const basicIcon = document.querySelector('.basic-icon');
      basicIcon.style.background = `linear-gradient(135deg, ${secondaryColor}, #005a6b)`;

      const premiumIcon = document.querySelector('.premium-icon');
      premiumIcon.style.background = `linear-gradient(135deg, ${accentColor}, #e04845)`;

      // Update buttons
      const valueButton = document.querySelector('.value-button');
      valueButton.style.background = `linear-gradient(135deg, ${primaryColor}, #ffc61a)`;

      const basicButton = document.querySelector('.basic-button');
      basicButton.style.background = `linear-gradient(135deg, ${secondaryColor}, #005a6b)`;

      const premiumButton = document.querySelector('.premium-button');
      premiumButton.style.background = `linear-gradient(135deg, ${accentColor}, #e04845)`;
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
        ["prompt_placeholder", config.prompt_placeholder || defaultConfig.prompt_placeholder],
        ["generate_button", config.generate_button || defaultConfig.generate_button],
        ["basic_title", config.basic_title || defaultConfig.basic_title],
        ["basic_description", config.basic_description || defaultConfig.basic_description],
        ["value_title", config.value_title || defaultConfig.value_title],
        ["value_description", config.value_description || defaultConfig.value_description],
        ["premium_title", config.premium_title || defaultConfig.premium_title],
        ["premium_description", config.premium_description || defaultConfig.premium_description],
        ["confirm_message", config.confirm_message || defaultConfig.confirm_message],
        ["success_message", config.success_message || defaultConfig.success_message]
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

    let selectedHotel = null;

function showConfirmation(option) {
  // Find the chosen hotel info based on option (basic, value, premium)
  let hotelTitle = document.getElementById(`${option}-title`).textContent;
  let description = document.getElementById(`${option}-description`).textContent;

  // Extract price if possible (formatted like "City - $200: description")
  let priceMatch = description.match(/\$(\d+)/);
  let price = priceMatch ? priceMatch[1] : 0;

  selectedHotel = { option, name: hotelTitle, price };

  const popup = document.getElementById('confirmation-popup');
  popup.classList.add('active');
}

async function confirmSelection() {
  closePopup();

  if (!selectedHotel) {
    showCustomAlert('No hotel selected.');
    return;
  }

  // Prepare booking data
  const bookingData = {
    hotel_name: selectedHotel.name,
    price: selectedHotel.price
  };

  try {
    const response = await fetch('book_hotel.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(bookingData)
    });

    const result = await response.json();
    if (result.success) {
      setTimeout(() => {
        const successPopup = document.getElementById('success-popup');
        successPopup.classList.add('active');
        createConfetti();
      }, 300);
    } else {
      showCustomAlert('Booking failed: ' + result.message);
    }
  } catch (error) {
    console.error('Booking error:', error);
    showCustomAlert('Successfully saved.');
  }
}


    function closePopup() {
      const confirmPopup = document.getElementById('confirmation-popup');
      const successPopup = document.getElementById('success-popup');
      confirmPopup.classList.remove('active');
      successPopup.classList.remove('active');
    }

    function createConfetti() {
      const colors = ['#F5B700', '#00798C', '#F25F5C'];
      
      for (let i = 0; i < 50; i++) {
        setTimeout(() => {
          const confetti = document.createElement('div');
          confetti.className = 'confetti';
          confetti.style.left = Math.random() * 100 + '%';
          confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
          confetti.style.animationDelay = Math.random() * 2 + 's';
          confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
          document.body.appendChild(confetti);
          
          setTimeout(() => {
            confetti.remove();
          }, 4000);
        }, i * 50);
      }
    }

   let hotels = [];

// Load hotels from JSON
fetch('hotels.json')
  .then(response => response.json())
  .then(data => hotels = data)
  .catch(err => console.error('Failed to load hotels:', err));

// Helper: pick random item from array
function pickRandom(arr) {
  return arr[Math.floor(Math.random() * arr.length)];
}

// Search hotels based on prompt
function searchHotels(prompt) {
  const lowerPrompt = prompt.toLowerCase();

  // Detect city
  const cities = [...new Set(hotels.map(h => h.city.toLowerCase()))];
  const cityMatch = cities.find(city => lowerPrompt.includes(city)) || null;

  // Detect category hints
  let categoryHint = null;
  if (lowerPrompt.includes('cheap') || lowerPrompt.includes('budget')) categoryHint = 'basic';
  else if (lowerPrompt.includes('luxury') || lowerPrompt.includes('premium')) categoryHint = 'premium';
  else categoryHint = 'value';

  // Filter hotels by city first
  let filtered = hotels;
  if (cityMatch) filtered = filtered.filter(h => h.city.toLowerCase() === cityMatch);

  // Further filter by category hint
  const basicHotels = filtered.filter(h => h.category === 'basic');
  const valueHotels = filtered.filter(h => h.category === 'value');
  const premiumHotels = filtered.filter(h => h.category === 'premium');

  return {
    basic: pickRandom(basicHotels.length ? basicHotels : hotels.filter(h => h.category === 'basic')),
    value: pickRandom(valueHotels.length ? valueHotels : hotels.filter(h => h.category === 'value')),
    premium: pickRandom(premiumHotels.length ? premiumHotels : hotels.filter(h => h.category === 'premium'))
  };
}

// Event listener
document.getElementById('generate-button').addEventListener('click', () => {
  const prompt = document.getElementById('trip-prompt').value.trim();
  if (!prompt) {
    showCustomAlert('Please describe your trip first!');
    return;
  }

  const matches = searchHotels(prompt);

  // Update hotel cards
  document.getElementById('basic-title').textContent = matches.basic.name;
  document.getElementById('basic-description').textContent = `${matches.basic.city} - $${matches.basic.price}: ${matches.basic.description}`;

  document.getElementById('value-title').textContent = matches.value.name;
  document.getElementById('value-description').textContent = `${matches.value.city} - $${matches.value.price}: ${matches.value.description}`;

  document.getElementById('premium-title').textContent = matches.premium.name;
  document.getElementById('premium-description').textContent = `${matches.premium.city} - $${matches.premium.price}: ${matches.premium.description}`;

  showCustomAlert('AI suggestions generated! Check out the hotel options below.');
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

    // Close popup when clicking outside
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('popup-overlay')) {
        closePopup();
      }
    });
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99b6529622075bf9',t:'MTc2MjYxODAzOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
