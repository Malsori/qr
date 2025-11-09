<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lock Opening Animation</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
    body {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #30343F 0%, #1a1d26 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow: hidden;
    }

    html {
      height: 100%;
    }

    .container {
      text-align: center;
      position: relative;
    }

    .title {
      color: #F5B700;
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 3rem;
      text-shadow: 0 0 20px rgba(245, 183, 0, 0.5);
    }

    .lock-container {
      position: relative;
      width: 200px;
      height: 250px;
      margin: 0 auto;
    }

    .lock-body {
      width: 120px;
      height: 140px;
      background: #00798C;
      border-radius: 20px;
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0 10px 40px rgba(0, 121, 140, 0.4);
    }

    .lock-shackle {
      width: 80px;
      height: 90px;
      border: 15px solid #00798C;
      border-bottom: none;
      border-radius: 50px 50px 0 0;
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      transform-origin: bottom center;
    }

    .lock-shackle.open {
      transform: translateX(-50%) translateX(-45px) rotate(-45deg);
    }

    .keyhole {
      width: 20px;
      height: 20px;
      background: #30343F;
      border-radius: 50%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .keyhole::after {
      content: '';
      width: 8px;
      height: 25px;
      background: #30343F;
      position: absolute;
      top: 15px;
      left: 50%;
      transform: translateX(-50%);
      clip-path: polygon(30% 0%, 70% 0%, 100% 100%, 0% 100%);
    }

    .sparkle {
      position: absolute;
      width: 8px;
      height: 8px;
      background: #F5B700;
      clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
      opacity: 0;
      animation: sparkle 1.5s ease-in-out infinite;
    }

    @keyframes sparkle {
      0%, 100% {
        opacity: 0;
        transform: scale(0) rotate(0deg);
      }
      50% {
        opacity: 1;
        transform: scale(1.5) rotate(180deg);
      }
    }

    .sparkle:nth-child(1) {
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }

    .sparkle:nth-child(2) {
      top: 30%;
      right: 15%;
      animation-delay: 0.3s;
    }

    .sparkle:nth-child(3) {
      top: 60%;
      left: 5%;
      animation-delay: 0.6s;
    }

    .sparkle:nth-child(4) {
      top: 70%;
      right: 10%;
      animation-delay: 0.9s;
    }

    .sparkle:nth-child(5) {
      top: 40%;
      left: 50%;
      animation-delay: 0.45s;
    }

    .sparkle:nth-child(6) {
      top: 15%;
      right: 30%;
      animation-delay: 0.75s;
    }

    .glow {
      position: absolute;
      width: 150px;
      height: 150px;
      background: radial-gradient(circle, rgba(242, 95, 92, 0.4) 0%, transparent 70%);
      border-radius: 50%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0;
      transition: opacity 0.8s ease;
    }

    .glow.active {
      opacity: 1;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: translate(-50%, -50%) scale(1);
      }
      50% {
        transform: translate(-50%, -50%) scale(1.2);
      }
    }

    .unlock-button {
      margin-top: 4rem;
      padding: 1rem 2.5rem;
      font-size: 1.2rem;
      font-weight: bold;
      color: #30343F;
      background: #F5B700;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 5px 20px rgba(245, 183, 0, 0.4);
      transition: all 0.3s ease;
    }

    .unlock-button:hover {
      background: #ffc61a;
      transform: translateY(-2px);
      box-shadow: 0 7px 25px rgba(245, 183, 0, 0.6);
    }

    .unlock-button:active {
      transform: translateY(0);
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>
  <div class="container">
   <h1 class="title" id="title">Unlock</h1>
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
   </div><button class="unlock-button" id="unlockBtn">Unlock</button>
  </div>
  <script>
    const defaultConfig = {
      title_text: "Unlock",
      primary_color: "#F5B700",
      secondary_color: "#00798C",
      accent_color: "#F25F5C",
      background_color: "#30343F"
    };

    async function onConfigChange(config) {
      const titleElement = document.getElementById('title');
      const buttonElement = document.getElementById('unlockBtn');
      const lockBody = document.querySelector('.lock-body');
      const lockShackle = document.getElementById('shackle');
      const sparkles = document.querySelectorAll('.sparkle');
      const glow = document.getElementById('glow');

      titleElement.textContent = config.title_text || defaultConfig.title_text;
      titleElement.style.color = config.primary_color || defaultConfig.primary_color;
      titleElement.style.textShadow = `0 0 20px ${config.primary_color || defaultConfig.primary_color}80`;

      buttonElement.style.background = config.primary_color || defaultConfig.primary_color;
      buttonElement.style.color = config.background_color || defaultConfig.background_color;
      buttonElement.style.boxShadow = `0 5px 20px ${config.primary_color || defaultConfig.primary_color}66`;

      lockBody.style.background = config.secondary_color || defaultConfig.secondary_color;
      lockBody.style.boxShadow = `0 10px 40px ${config.secondary_color || defaultConfig.secondary_color}66`;
      
      lockShackle.style.borderColor = config.secondary_color || defaultConfig.secondary_color;

      sparkles.forEach(sparkle => {
        sparkle.style.background = config.primary_color || defaultConfig.primary_color;
      });

      glow.style.background = `radial-gradient(circle, ${config.accent_color || defaultConfig.accent_color}66 0%, transparent 70%)`;

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
        ["title_text", config.title_text || defaultConfig.title_text]
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

    const unlockBtn = document.getElementById('unlockBtn');
    const shackle = document.getElementById('shackle');
    const glow = document.getElementById('glow');

    unlockBtn.addEventListener('click', () => {
      shackle.classList.toggle('open');
      glow.classList.toggle('active');
      
      if (shackle.classList.contains('open')) {
        unlockBtn.textContent = 'Lock';
      } else {
        unlockBtn.textContent = 'Unlock';
      }
    });
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99b6138e458d5d8b',t:'MTc2MjYxNTQ1Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>