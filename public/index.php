<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AI Travel Platform - Plan Your Perfect Trip</title>

  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>

  <link rel="stylesheet" href="css/navbar.css?v=<?php echo time(); ?>" />

  <style>
    :root{
      --primary-color: #F5B700;
      --secondary-color: #00798C;
      --accent-color: #F25F5C;
      --background-color: #30343F;
    }

    /* Reset & base */
    *, *::before, *::after { box-sizing: border-box; }
    html { height: 100%; scroll-behavior: smooth; }
    body {
      margin: 0;
      min-height: 100%;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: #30343F;
      background: #ffffff;
      overflow-x: hidden;
    }

    /* HERO */
    .hero {
      min-height: 100%;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, #f5b700 20%, #00798c 65%);
      position: relative;
      overflow:hidden;
      padding:8rem 2rem 4rem;
      text-align:center;
    }
    .hero::before{
      content:"";
      position:absolute; inset:0;
      background:
        radial-gradient(circle at 30% 20%, rgba(245,183,0,0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 80%, rgba(0,121,140,0.1) 0%, transparent 50%);
      animation: float 20s ease-in-out infinite;
      z-index:0;
    }
    @keyframes float {
      0%,100%{ transform: translateY(0) rotate(0); }
      50%{ transform: translateY(-20px) rotate(1deg); }
    }
    .hero-content{ position:relative; z-index:2; max-width:800px; color:#fff; }
    .hero h1{
      font-size:clamp(2.5rem,5vw,4rem);
      font-weight:800;
      margin:0 0 1.5rem 0;
      line-height:1.2;
      background: linear-gradient(135deg,#fff 0%, var(--primary-color) 100%);
      -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
    }
    .hero p{
      font-size:1.25rem;
      color: rgba(255,255,255,0.8);
      margin:0 auto 3rem auto;
      max-width:600px;
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

    /* HOW IT WORKS */
    .how-it-works{ padding:6rem 2rem; background:#ffffff; }
    .container{ max-width:1200px; margin:0 auto; }
    .section-title{ text-align:center; font-size:2.5rem; font-weight:700; color:#30343F; margin-bottom:3rem; }
    .steps-grid{ display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:2rem; margin-top:4rem; }
    .step-card{
      text-align:center;
      padding:2rem;
      border-radius:20px;
      background:#ffffff;
      box-shadow: 0 10px 40px rgba(0,0,0,0.05);
      position:relative;
      overflow:hidden;
      transition: all .3s ease;
    }
    .step-card::before{
      content:"";
      position:absolute; left:0; right:0; top:0; height:4px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
    }
    .step-card:hover{ transform:translateY(-10px); box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
    .step-number{
      width:60px; height:60px; border-radius:50%;
      display:flex; align-items:center; justify-content:center;
      font-size:1.5rem; font-weight:bold; margin:0 auto 1.5rem;
      background: linear-gradient(135deg, var(--secondary-color), #005a6b); color:#fff;
    }
    .step-card h3{ font-size:1.3rem; font-weight:600; color:#30343F; margin-bottom:1rem; }
    .step-card p{ color:#666; line-height:1.6; margin:0; }

    /* AI DEMO */
    .ai-demo{ padding:6rem 2rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
    .demo-container{ display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center; max-width:1200px; margin:0 auto; }
    .demo-content h2{ font-size:2.2rem; font-weight:700; color:#30343F; margin-bottom:1.5rem; }
    .demo-content p{ font-size:1.1rem; color:#666; margin-bottom:2rem; }
    .demo-mockup{ background:#ffffff; border-radius:20px; padding:2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.1); position:relative; }
    .ai-suggestion{ display:flex; align-items:center; gap:1rem; padding:1rem; border-radius:15px; background: linear-gradient(135deg, rgba(0,121,140,0.06), rgba(245,183,0,0.06)); margin-bottom:1rem; animation: slideIn .5s ease forwards; opacity:0; transform:translateX(-20px); }
    .ai-suggestion:nth-child(1){ animation-delay:.2s } .ai-suggestion:nth-child(2){ animation-delay:.4s } .ai-suggestion:nth-child(3){ animation-delay:.6s }
    @keyframes slideIn{ to{ opacity:1; transform:translateX(0); } }
    .hotel-image{ width:60px; height:60px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; background: linear-gradient(135deg, var(--primary-color), #ffc61a); }
    .hotel-info h4{ margin:0 0 .5rem 0; color:#30343F; font-weight:600; }
    .hotel-info p{ margin:0; color:#666; font-size:.9rem; }
    .ai-badge{ background: linear-gradient(135deg, var(--accent-color), #e04845); color:#fff; padding:.3rem .8rem; border-radius:20px; font-size:.8rem; font-weight:600; margin-left:auto; }

    /* TRUST */
    .trust-section{ padding:6rem 2rem; background:#ffffff; }
    .trust-grid{ display:grid; grid-template-columns: repeat(auto-fit, minmax(300px,1fr)); gap:2rem; margin-top:4rem; }
    .trust-card{ text-align:center; padding:2.5rem 2rem; border-radius:20px; background:linear-gradient(135deg,#fff 0%, #f8f9fa 100%); border:2px solid #e9ecef; transition:all .3s ease; }
    .trust-card:hover{ transform:translateY(-5px); border-color:var(--primary-color); box-shadow: 0 15px 50px rgba(245,183,0,0.1); }
    .trust-icon{ width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:2rem; background: linear-gradient(135deg, var(--secondary-color), #005a6b); color:#fff; }
    .trust-card h3{ font-size:1.4rem; font-weight:600; color:#30343F; margin-bottom:1rem; }

    /* FINAL CTA + FOOTER */
    .final-cta{ padding:6rem 2rem; background: linear-gradient(135deg,#30343F 0%, #1a1d26 100%); text-align:center; position:relative; overflow:hidden; color:#fff; }
    .final-cta::before{ content:""; position:absolute; inset:0; background: radial-gradient(circle at 50% 50%, rgba(245,183,0,0.1) 0%, transparent 70%); }
    .final-cta h2{ font-size:2.5rem; font-weight:700; margin-bottom:1.5rem; position:relative; z-index:2; }
    .final-cta p{ font-size:1.2rem; color: rgba(255,255,255,0.8); margin-bottom:3rem; position:relative; z-index:2; }

    .footer{ background:#30343F; color: rgba(255,255,255,0.8); padding:3rem 2rem 2rem; text-align:center; }
    .footer-content{ max-width:1200px; margin:0 auto; }
    .footer p{ margin: .5rem 0; font-size:.9rem; }
    .footer-credits{ color:var(--primary-color); font-weight:600; }

    /* Animations */
    .fade-in{ opacity:0; transform:translateY(30px); transition: all .6s ease; }
    .fade-in.visible{ opacity:1; transform:translateY(0); }

    /* Responsive */
    @media (max-width: 1024px){
      .demo-container{ grid-template-columns:1fr; gap:3rem; }
    }
    @media (max-width: 768px){
      .nav-links{ display:none; }
      .hamburger{ display:flex; }
      .hero{ padding:6rem 1rem 3rem; }
      .steps-grid{ grid-template-columns:1fr; }
      .trust-grid{ grid-template-columns:1fr; }
      .section-title{ font-size:2rem; }
    }
  </style>

  <!-- keep view-transition as in original -->
  <style>@view-transition { navigation: auto; }</style>
</head>
<body>
  <!-- NAVIGATION -->
  <nav class="navbar" aria-label="Top Navigation">
    <div class="nav-container">
      <a class="logo" href="#">
        <img src="images/logo.svg" alt="Logo" width="150" style="display:block;">
      </a>

      <!-- Desktop links -->
      <ul class="nav-links" role="menubar" aria-label="Primary">
        <li role="none"><a role="menuitem" href="#how-it-works">How It Works</a></li>
        <li role="none"><a role="menuitem" href="#demo">AI Demo</a></li>
        <li role="none"><a role="menuitem" href="#trust">Security</a></li>
        <li role="none"><a role="menuitem" class="cta-button" style="padding: 13px 25px !important" href="Login.php">Register/Login</a></li>
      </ul>

      <!-- Mobile hamburger -->
      <button class="hamburger" aria-label="Open menu" aria-expanded="false" id="hamburgerBtn">
        <span class="bar" aria-hidden="true"></span>
      </button>
    </div>
  </nav>

  <!-- Drawer (right side) -->
  <aside class="drawer" id="mobileDrawer" aria-hidden="true" aria-label="Mobile menu">
    <button class="close-btn" id="drawerClose" aria-label="Close menu">&times;</button>
    <nav>
      <a href="#how-it-works" data-drawer-link>How It Works</a>
      <a href="#demo" data-drawer-link>AI Demo</a>
      <a href="#trust" data-drawer-link>Security</a>
      <a href="Login.php" class="cta-button" style="padding: 13px 25px !important" data-drawer-link>Register/Login</a>
    </nav>
  </aside>

  <!-- HERO -->
  <section class="hero" id="home">
    <div class="hero-content">
      <h1 id="hero-headline">Your Next Trip — Planned, Booked &amp; Ready in 60 Seconds</h1>
      <p id="hero-subtext">AI-powered travel planning that understands your preferences, finds perfect hotels, and delivers secure QR check-in codes. The future of travel is here.</p>
      <a id="cta-button" class="cta-button" href="Prompt.php">Plan My Trip</a>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="how-it-works" id="how-it-works">
    <div class="container">
      <h2 class="section-title fade-in">How It Works</h2>
      <div class="steps-grid">
        <div class="step-card fade-in">
          <div class="step-number">1</div>
          <h3>Plan</h3>
          <p>Tell our AI about your dream trip - destination, dates, budget, and preferences. Our intelligent system learns what you love.</p>
        </div>
        <div class="step-card fade-in">
          <div class="step-number">2</div>
          <h3>AI Suggestions</h3>
          <p>Get personalized hotel recommendations powered by advanced AI algorithms that match your style and budget perfectly.</p>
        </div>
        <div class="step-card fade-in">
          <div class="step-number">3</div>
          <h3>Booking</h3>
          <p>Book instantly with our secure platform. No hidden fees, no surprises - just transparent, competitive pricing.</p>
        </div>
        <div class="step-card fade-in">
          <div class="step-number">4</div>
          <h3>QR Check-in</h3>
          <p>Receive your secure QR code for contactless check-in. Skip the front desk and go straight to your room.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- AI DEMO -->
  <section class="ai-demo" id="demo">
    <div class="demo-container">
      <div class="demo-content fade-in">
        <h2>AI That Knows Your Style</h2>
        <p>Our advanced AI analyzes millions of data points to understand your preferences and recommend hotels that match your unique travel style. See how it works in real-time.</p>
      </div>
      <div class="demo-mockup fade-in">
        <div class="ai-suggestion">
          <div class="hotel-image">🏨</div>
          <div class="hotel-info">
            <h4>The Modern Boutique</h4>
            <p>Perfect match for your minimalist style • $180/night</p>
          </div>
          <div class="ai-badge">98% Match</div>
        </div>
        <div class="ai-suggestion">
          <div class="hotel-image">🌟</div>
          <div class="hotel-info">
            <h4>Luxury Downtown Suite</h4>
            <p>Premium location, business amenities • $320/night</p>
          </div>
          <div class="ai-badge">94% Match</div>
        </div>
        <div class="ai-suggestion">
          <div class="hotel-image">🏖️</div>
          <div class="hotel-info">
            <h4>Seaside Resort &amp; Spa</h4>
            <p>Relaxation focused, ocean views • $250/night</p>
          </div>
          <div class="ai-badge">91% Match</div>
        </div>
      </div>
    </div>
  </section>

  <!-- TRUST & SECURITY -->
  <section class="trust-section" id="trust">
    <div class="container">
      <h2 class="section-title fade-in">Trust &amp; Security</h2>
      <div class="trust-grid">
        <div class="trust-card fade-in">
          <div class="trust-icon">🔒</div>
          <h3>Secure QR Codes</h3>
          <p>Military-grade encryption protects your check-in codes. Each QR code is unique, time-limited, and impossible to duplicate.</p>
        </div>
        <div class="trust-card fade-in">
          <div class="trust-icon">✅</div>
          <h3>Verified Hotels</h3>
          <p>Every hotel partner is thoroughly vetted and verified. We work only with trusted properties that meet our high standards.</p>
        </div>
        <div class="trust-card fade-in">
          <div class="trust-icon">🛡️</div>
          <h3>Encrypted Data</h3>
          <p>Your personal information is protected with bank-l evel security. We never share your data and use it only to improve your experience.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="final-cta">
    <div class="container">
      <h2>Ready to Experience the Future of Travel?</h2>
      <p>Join thousands of travelers who've discovered smarter, faster, and more secure trip planning.</p>
      <a class="cta-button" href="Prompt.php">Start My Trip Plan</a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-content">
      <p>© 2025 <span id="company-name-footer">QRoom</span> | All rights reserved</p>
      <p class="footer-credits" id="footer-credits">Built with ❤️ by the QRoom Team</p>
      <p>Revolutionizing travel, one trip at a time.</p>
    </div>
  </footer>

  <!-- SCRIPTS (cleaned & preserved behavior) -->
  <script>
    const defaultConfig = {
      hero_headline: "Your Next Trip — Planned, Booked & Ready in 60 Seconds",
      hero_subtext: "AI-powered travel planning that understands your preferences, finds perfect hotels, and delivers secure QR check-in codes. The future of travel is here.",
      cta_button_text: "Plan My Trip",
      company_name: "TravelAI",
      footer_credits: "Built with ❤️ by the TravelAI Team",
      primary_color: "#F5B700",
      secondary_color: "#00798C",
      accent_color: "#F25F5C",
      background_color: "#30343F"
    };

    async function onConfigChange(config) {
      // Text updates
      document.getElementById('hero-headline').textContent = config.hero_headline || defaultConfig.hero_headline;
      document.getElementById('hero-subtext').textContent = config.hero_subtext || defaultConfig.hero_subtext;
      document.getElementById('cta-button').textContent = config.cta_button_text || defaultConfig.cta_button_text;
      const companyNameElements = document.querySelectorAll('#company-name, #company-name-footer');
      companyNameElements.forEach(el => el.textContent = config.company_name || defaultConfig.company_name);
      document.getElementById('footer-credits').textContent = config.footer_credits || defaultConfig.footer_credits;

      // Colors (CSS variables)
      const primaryColor = config.primary_color || defaultConfig.primary_color;
      const secondaryColor = config.secondary_color || defaultConfig.secondary_color;
      const accentColor = config.accent_color || defaultConfig.accent_color;
      const backgroundColor = config.background_color || defaultConfig.background_color;

      document.documentElement.style.setProperty('--primary-color', primaryColor);
      document.documentElement.style.setProperty('--secondary-color', secondaryColor);
      document.documentElement.style.setProperty('--accent-color', accentColor);
      document.documentElement.style.setProperty('--background-color', backgroundColor);

      // Update inline styles for CTA / badges (to mirror the original behavior)
      document.querySelectorAll('.cta-button').forEach(btn => {
        btn.style.background = `linear-gradient(135deg, ${primaryColor} 0%, #ffc61a 100%)`;
        btn.style.boxShadow = `0 10px 40px ${primaryColor}4D`; // keep original's hex alpha approach
      });
      document.querySelectorAll('.step-number').forEach(n => {
        n.style.background = `linear-gradient(135deg, ${secondaryColor}, #005a6b)`;
      });
      document.querySelectorAll('.trust-icon').forEach(i => {
        i.style.background = `linear-gradient(135deg, ${secondaryColor}, #005a6b)`;
      });
      document.querySelectorAll('.ai-badge').forEach(b => {
        b.style.background = `linear-gradient(135deg, ${accentColor}, #e04845)`;
      });
    }

    function mapToCapabilities(config) {
      return {
        recolorables: [
          { get: () => config.background_color || defaultConfig.background_color, set: (v)=> { config.background_color = v; window.elementSdk.setConfig({ background_color: v }); } },
          { get: () => config.secondary_color || defaultConfig.secondary_color, set: (v)=> { config.secondary_color = v; window.elementSdk.setConfig({ secondary_color: v }); } },
          { get: () => config.primary_color || defaultConfig.primary_color, set: (v)=> { config.primary_color = v; window.elementSdk.setConfig({ primary_color: v }); } },
          { get: () => config.accent_color || defaultConfig.accent_color, set: (v)=> { config.accent_color = v; window.elementSdk.setConfig({ accent_color: v }); } }
        ],
        borderables: [],
        fontEditable: undefined,
        fontSizeable: undefined
      };
    }

    function mapToEditPanelValues(config){
      return new Map([
        ["hero_headline", config.hero_headline || defaultConfig.hero_headline],
        ["hero_subtext", config.hero_subtext || defaultConfig.hero_subtext],
        ["cta_button_text", config.cta_button_text || defaultConfig.cta_button_text],
        ["company_name", config.company_name || defaultConfig.company_name],
        ["footer_credits", config.footer_credits || defaultConfig.footer_credits]
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

    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, observerOptions);
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.style.background = 'rgba(255,255,255,0.98)';
        navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
      } else {
        navbar.style.background = 'rgba(255,255,255,0.95)';
        navbar.style.boxShadow = 'none';
      }
    });

    (function setupDrawer(){
      const hamburger = document.getElementById('hamburgerBtn');
      const drawer = document.getElementById('mobileDrawer');
      const closeBtn = document.getElementById('drawerClose');
      const drawerLinks = document.querySelectorAll('[data-drawer-link]');

      function openDrawer(){
        drawer.classList.add('open');
        drawer.setAttribute('aria-hidden', 'false');
        hamburger.setAttribute('aria-expanded', 'true');
        // trap focus: set focus to first link (basic, not full focus-trap)
        setTimeout(()=> { drawer.querySelector('a')?.focus(); }, 150);
        document.body.style.overflow = 'hidden';
      }
      function closeDrawer(){
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        hamburger.setAttribute('aria-expanded', 'false');
        hamburger.focus();
        document.body.style.overflow = '';
      }

      hamburger.addEventListener('click', () => {
        const expanded = hamburger.getAttribute('aria-expanded') === 'true';
        if (!expanded) openDrawer(); else closeDrawer();
      });
      closeBtn.addEventListener('click', closeDrawer);

      // close when a link is clicked
      drawerLinks.forEach(a => a.addEventListener('click', closeDrawer));

      // close on Escape
      window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer.classList.contains('open')) closeDrawer();
      });

      // close if user taps outside the drawer (on the page)
      window.addEventListener('click', (ev) => {
        if (!drawer.contains(ev.target) && !hamburger.contains(ev.target) && drawer.classList.contains('open')) {
          closeDrawer();
        }
      });
    })();

    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', (e) => {
        const targetId = a.getAttribute('href').slice(1);
        if (!targetId) return;
        const el = document.getElementById(targetId);
        if (el) {
          // allow browser default hash but also smooth scroll
          e.preventDefault();
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
          history.pushState(null, '', `#${targetId}`);
        }
      });
    });
  </script>

  <!-- keep original iframe/challenge script injection (kept verbatim) -->
  <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99b6399630b68f3c',t:'MTc2MjYxNzAxNC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
</body>
</html>
