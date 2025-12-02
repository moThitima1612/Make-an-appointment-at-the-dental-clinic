<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <title>ร้านขายอาหารสัตว์ - Premium 5⭐</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" type="image/png" href="https://img.pikbest.com/png-images/20241101/a-shop-logo-mini-mart-magic_11042317.png!w700wp" />
  <style>
    :root {
      --gold: #d4af37;
      --gold-light: #f6e27a;
      --gold-dark: #b08d34;
      --bg: #f1f1f1;
      --card-bg: #ffffff;
      --text-color: #333;
      --nav-bg: #1e2a3a;
      --hover-bg: #2e3b56;
    }
    html { scroll-behavior: smooth; }
    body {
      font-family: "Kanit", sans-serif;
      background: var(--bg);
      color: var(--text-color);
      line-height: 1.6;
    }
    .navbar {
      background: var(--nav-bg);
      border-bottom: 2px solid var(--gold);
      box-shadow: 0 4px 12px rgba(0, 0, 0, .7);
    }
    .navbar-brand, .nav-link { color: #f5f5f5 !important; }
    .navbar-brand img {
      height: 40px;
      margin-right: 8px;
    }
    .navbar-nav .nav-link:hover {
      background-color: var(--hover-bg);
      color: var(--gold) !important;
    }
    .navbar-nav .nav-link.active {
      color: #000 !important;
      font-weight: 700;
      background: linear-gradient(45deg, var(--gold), var(--gold-light));
      border-radius: 22px;
    }
    h2 {
      color: var(--gold);
      font-weight: 600;
      text-align: center;
      margin-top: 40px;
      margin-bottom: 30px;
    }
    .card {
      border: none;
      border-radius: 1rem;
      overflow: hidden;
      background: var(--card-bg);
      color: #000;
      box-shadow: 0 5px 18px rgba(0, 0, 0, .6);
      transition: .35s ease;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(212, 175, 55, .35);
    }
    .card img {
      height: 200px;
      object-fit: cover;
      border-bottom: 2px solid var(--gold);
    }
    .btn-gold {
      background-color: #000;
      color: #fff;
      font-weight: 600;
      border-radius: 30px;
      border: none;
      transition: .25s;
    }
    .btn-gold:hover {
      background-color: #333;
      color: #fff;
    }
    footer {
      background: var(--nav-bg);
      border-top: 2px solid var(--gold);
      padding: 20px;
      text-align: center;
      margin-top: 50px;
      color: #bbb;
    }
    .hero-section {
      background: linear-gradient(135deg, var(--nav-bg), var(--gold-dark));
      color: white;
      padding: 80px 20px;
      text-align: center;
    }

    /* Popup Promotion */
    .promo-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(6px);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
    @keyframes fadeOut { from {opacity:1;} to {opacity:0;} }
    @keyframes slideUp { from {transform:translateY(100px);opacity:0;} to {transform:translateY(0);opacity:1;} }

    .promo-modal {
      background: white;
      border-radius: 25px;
      width: 90%;
      max-width: 500px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
      animation: slideUp 0.4s ease-out;
      position: relative;
    }
    .promo-header {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      text-align: center;
      padding: 30px;
      position: relative;
      color: white;
    }
    .promo-header h2 { font-size: 2rem; font-weight: 700; margin-bottom: 5px; }
    .promo-close {
      position: absolute; top: 15px; right: 15px;
      background: rgba(255,255,255,0.3);
      border: none; color: #fff; font-size: 24px;
      width: 40px; height: 40px; border-radius: 50%;
      cursor: pointer; transition: 0.3s;
    }
    .promo-close:hover { background: rgba(255,255,255,0.5); transform: rotate(90deg); }
    .promo-body { padding: 25px 30px; }
    .promo-item {
      display: flex; align-items: flex-start;
      background: #f8f9fa; border-radius: 15px;
      padding: 15px; margin-bottom: 15px;
      border-left: 4px solid var(--gold);
      transition: 0.3s;
    }
    .promo-item:hover { transform: translateX(5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .promo-item-icon { font-size: 2rem; margin-right: 15px; }
    .promo-item-content h4 { margin: 0; font-size: 1.1rem; font-weight: 600; color: #333; }
    .promo-item-content p { margin: 0; color: #666; font-size: 0.95rem; }
    .promo-footer { background: #fff; padding: 20px 30px 30px 30px; text-align: center; }
    .promo-code-box {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;
    }
    .promo-code { background: white; color: #667eea; padding: 10px 20px; border-radius: 8px; font-size: 1.5rem; font-weight: 700; letter-spacing: 2px; }
    .promo-buttons { display: flex; gap: 10px; justify-content: center; }
    .promo-btn-primary, .promo-btn-secondary {
      flex: 1; padding: 12px; border-radius: 10px; border: none; font-weight: 600; font-size: 1rem; cursor: pointer;
    }
    .promo-btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white; transition: 0.3s;
    }
    .promo-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
    .promo-btn-secondary { background: #f0f0f0; color: #555; }
    .promo-timer { margin-top: 15px; color: #ff6b6b; font-weight: 600; }

  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#dashboard">
        <img src="https://img.pikbest.com/png-images/20241101/a-shop-logo-mini-mart-magic_11042317.png!w700wp" alt="โลโก้">
        ร้านขายอาหารสัตว์
      </a>
      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="#products">สินค้า</a></li>
          <li class="nav-item"><a class="nav-link" href="#reviews">รีวิว</a></li>
          <li class="nav-item"><a class="nav-link" href="#location">ที่ตั้ง</a></li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="showPromotion(); return false;">🎉 โปรโมชั่น</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section id="dashboard" class="hero-section">
    <div class="container">
      <h1>🐾 ยินดีต้อนรับสู่ร้านอาหารสัตว์</h1>
      <p class="lead">อาหารและอุปกรณ์คุณภาพพรีเมียม สำหรับสัตว์เลี้ยงที่คุณรัก</p>
    </div>
  </section>

  <!-- Content -->
  <section id="products" class="container mt-5">
    <h2>🛍️ สินค้าของเรา</h2>
    <div class="row">
      <div class="col-md-4"><div class="card text-center"><img src="https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=400"><div class="p-3"><h5>อาหารแมว Premium</h5><p>150 บาท</p></div></div></div>
      <div class="col-md-4"><div class="card text-center"><img src="https://images.unsplash.com/photo-1623387641168-d9803ddd3f35?w=400"><div class="p-3"><h5>อาหารสุนัข</h5><p>200 บาท</p></div></div></div>
      <div class="col-md-4"><div class="card text-center"><img src="https://images.unsplash.com/photo-1535241749838-299277b6305f?w=400"><div class="p-3"><h5>อาหารปลา</h5><p>180 บาท</p></div></div></div>
    </div>
  </section>

  <!-- Footer -->
  <footer>© 2025 ร้านขายอาหารสัตว์. สงวนลิขสิทธิ์.</footer>

  <!-- ✅ Popup โปรโมชั่น -->
  <div id="promoOverlay" class="promo-overlay">
    <div class="promo-modal" id="promoModal">
      <div class="promo-header">
        <button class="promo-close" onclick="closePromotion()">×</button>
        <h2>โปรโมชั่นใหม่!</h2>
        <p>เฉพาะเดือนนี้เท่านั้น 💥</p>
      </div>

      <div class="promo-body">
        <div class="promo-item">
          <div class="promo-item-icon">🐶</div>
          <div class="promo-item-content">
            <h4>ซื้อครบ 500 บาท</h4>
            <p>รับฟรีของแถมสุดพิเศษ 1 ชิ้น</p>
          </div>
        </div>

        <div class="promo-item">
          <div class="promo-item-icon">🐱</div>
          <div class="promo-item-content">
            <h4>สมาชิกใหม่</h4>
            <p>ลดทันที 15% เมื่อสั่งซื้อครั้งแรก</p>
          </div>
        </div>

        <div class="promo-item">
          <div class="promo-item-icon">🚚</div>
          <div class="promo-item-content">
            <h4>จัดส่งฟรีทั่วไทย</h4>
            <p>เมื่อสั่งครบ 999 บาทขึ้นไป</p>
          </div>
        </div>
      </div>

      <div class="promo-footer">
        <div class="promo-code-box">
          <h5>ใช้โค้ดส่วนลดพิเศษ</h5>
          <div class="promo-code">SAVE10</div>
        </div>

        <div class="promo-buttons">
          <button class="promo-btn-primary" onclick="closePromotion()">ตกลง</button>
          <button class="promo-btn-secondary" onclick="closePromotion()">ปิด</button>
        </div>

        <div class="promo-timer" id="promoTimer">ปิดอัตโนมัติใน 10 วินาที</div>
      </div>
    </div>
  </div>

  <script>
    function showPromotion() {
      const overlay = document.getElementById("promoOverlay");
      overlay.style.display = "flex";
      startPromoTimer();
    }

    function closePromotion() {
      const overlay = document.getElementById("promoOverlay");
      overlay.style.animation = "fadeOut 0.4s forwards";
      setTimeout(() => {
        overlay.style.display = "none";
        overlay.style.animation = "";
      }, 400);
    }

    document.getElementById("promoOverlay").addEventListener("click", e => {
      if (e.target.id === "promoOverlay") closePromotion();
    });

    document.addEventListener("keydown", e => {
      if (e.key === "Escape") closePromotion();
    });

    function startPromoTimer() {
      let timeLeft = 10;
      const timerEl = document.getElementById("promoTimer");
      const countdown = setInterval(() => {
        timeLeft--;
        timerEl.textContent = `ปิดอัตโนมัติใน ${timeLeft} วินาที`;
        if (timeLeft <= 0) {
          clearInterval(countdown);
          closePromotion();
        }
      }, 1000);
    }

    // แสดงอัตโนมัติหลังเข้าเว็บ 3 วินาที
    window.addEventListener("load", () => {
      setTimeout(showPromotion, 3000);
    });
  </script>
</body>
</html>
