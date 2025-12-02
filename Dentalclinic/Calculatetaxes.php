<?php
include 'index.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลบริการ
$services_sql = "SELECT * FROM services_v1";
$services = mysqli_query($db, $services_sql);

// ดึงข้อมูลหมอ
$dentists_sql = "SELECT * FROM dentists";
$dentists = mysqli_query($db, $dentists_sql);

// นับจำนวนหมอแบบ Dynamic
$dentist_count = mysqli_num_rows($dentists);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>จองคิวคลินิกทันตกรรม</title>

<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="https://img.pikbest.com/png-images/20241101/a-shop-logo-mini-mart-magic_11042317.png!w700wp" />

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Prompt", sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow-x: hidden;
  }

  body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
      radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
  }

  .wrapper {
    width: 100%;
    max-width: 680px;
    position: relative;
    z-index: 1;
  }

  .back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.95);
    color: #667eea;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .back-link:hover {
    transform: translateX(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
  }

  .container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 28px;
    padding: 50px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.5);
    animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(40px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .header {
    text-align: center;
    margin-bottom: 40px;
  }

  .header-icon {
    font-size: 64px;
    margin-bottom: 15px;
    animation: bounce 2s ease-in-out infinite;
  }

  @keyframes bounce {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-10px);
    }
  }

  h2 {
    font-size: 32px;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
  }

  .subtitle {
    color: #64748b;
    font-size: 16px;
    font-weight: 400;
  }

  .form-section {
    margin-bottom: 30px;
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
  }

  .form-group {
    margin-bottom: 24px;
  }

  label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #334155;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .required {
    color: #ef4444;
    font-size: 18px;
  }

  input, select {
    width: 100%;
    padding: 16px;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    font-size: 15px;
    font-family: "Prompt", sans-serif;
    transition: all 0.3s ease;
    background: white;
    color: #1e293b;
    font-weight: 500;
  }

  input::placeholder {
    color: #94a3b8;
  }

  input:focus, select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
  }

  select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 20px;
    padding-right: 50px;
  }

  select option {
    padding: 12px;
  }

  .input-icon {
    position: relative;
  }

  .input-icon::before {
    content: attr(data-icon);
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    pointer-events: none;
  }

  .input-icon input {
    padding-left: 50px;
  }

  .info-badge {
    display: inline-block;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    color: #475569;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-left: 10px;
  }

  button {
    width: 100%;
    padding: 18px;
    border: none;
    border-radius: 16px;
    font-size: 18px;
    font-weight: 700;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 40px;
  }

  button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
  }

  button:active {
    transform: translateY(-1px);
  }

  .button-icon {
    font-size: 22px;
  }

  .feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px dashed #e2e8f0;
  }

  .feature-item {
    text-align: center;
    padding: 15px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 14px;
    transition: all 0.3s ease;
  }

  .feature-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  }

  .feature-icon {
    font-size: 28px;
    margin-bottom: 8px;
  }

  .feature-text {
    font-size: 13px;
    color: #475569;
    font-weight: 600;
  }

  /* Responsive */
  @media (max-width: 768px) {
    body {
      padding: 15px;
    }

    .container {
      padding: 30px 25px;
      border-radius: 20px;
    }

    h2 {
      font-size: 26px;
    }

    .header-icon {
      font-size: 48px;
    }

    .feature-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Loading Animation */
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
  }

  /* Success message styles */
  .alert {
    padding: 16px;
    border-radius: 14px;
    margin-bottom: 24px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 2px solid #10b981;
  }

  .alert-error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 2px solid #ef4444;
  }
</style>
</head>
<body>

<div class="wrapper">
  <a href="HomePage.php" class="back-link">
    <span>←</span>
    <span>กลับไปปฏิทิน</span>
  </a>

  <div class="container">
    <div class="header">
      <div class="header-icon">🦷</div>
      <h2>จองคิวคลินิกทันตกรรม</h2>
      <p class="subtitle">กรุณากรอกข้อมูลเพื่อทำการจองคิว</p>
    </div>

    <form method="post" action="save_booking.php" id="bookingForm">
      
      <!-- ข้อมูลผู้ป่วย -->
      <div class="form-section">
        <div class="section-title">
          <span>👤</span>
          <span>ข้อมูลผู้ป่วย</span>
        </div>

        <div class="form-group">
          <label>
            <span>ชื่อ-นามสกุลผู้ป่วย</span>
            <span class="required">*</span>
          </label>
          <div class="input-icon" data-icon="👤">
            <input type="text" name="patient_name" required placeholder="กรอกชื่อ-นามสกุล">
          </div>
        </div>

        <div class="form-group">
          <label>
            <span>เบอร์โทรติดต่อ</span>
            <span class="required">*</span>
          </label>
          <div class="input-icon" data-icon="📱">
            <input type="tel" name="patient_phone" required placeholder="กรอกเบอร์โทร (เช่น 081-234-5678)" pattern="[0-9-]{10,12}">
          </div>
        </div>
      </div>

      <!-- ข้อมูลบริการ -->
      <div class="form-section">
        <div class="section-title">
          <span>🔧</span>
          <span>ข้อมูลบริการ</span>
        </div>

        <div class="form-group">
          <label>
            <span>บริการที่ต้องการ</span>
            <span class="required">*</span>
          </label>
          <select name="service_id" required>
            <option value="">-- เลือกบริการ --</option>
            <?php while($svc = $services->fetch_assoc()): ?>
              <option value="<?= $svc['service_id']; ?>">
                <?= $svc['service_name']; ?> (⏱️ <?= $svc['duration_minutes']; ?> นาที)
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label>
            <span>หมอผู้ตรวจ</span>
            <span class="required">*</span>
            <span class="info-badge">📋 มี <?= $dentist_count ?> ท่าน</span>
          </label>
          <select name="dentist_id" required>
            <option value="">-- เลือกคุณหมอ --</option>
            <?php while($dn = $dentists->fetch_assoc()): ?>
              <option value="<?= $dn['dentist_id']; ?>">👨‍⚕️ <?= $dn['name']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <!-- วันและเวลา -->
      <div class="form-section">
        <div class="section-title">
          <span>📅</span>
          <span>วันและเวลานัดหมาย</span>
        </div>

        <div class="form-group">
          <label>
            <span>วันที่ต้องการเข้ารับบริการ</span>
            <span class="required">*</span>
          </label>
          <input type="date" name="appointment_date" required min="<?= date('Y-m-d'); ?>">
        </div>

        <div class="form-group">
          <label>
            <span>เวลาที่ต้องการเข้ารับบริการ</span>
            <span class="required">*</span>
          </label>
          <input type="time" name="appointment_time" required>
        </div>
      </div>

      <button type="submit">
        <span class="button-icon">✨</span>
        <span>ยืนยันการจองคิว</span>
      </button>

      <!-- คุณสมบัติเด่น -->
      <div class="feature-grid">
        <div class="feature-item">
          <div class="feature-icon">⚡</div>
          <div class="feature-text">จองง่าย รวดเร็ว</div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">🔒</div>
          <div class="feature-text">ข้อมูลปลอดภัย</div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">📱</div>
          <div class="feature-text">แจ้งเตือน SMS</div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">🎁</div>
          <div class="feature-text">สะสมแต้มฟรี</div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// Form validation และ loading state
document.getElementById('bookingForm').addEventListener('submit', function(e) {
  const button = this.querySelector('button[type="submit"]');
  const buttonText = button.querySelector('span:last-child');
  const originalText = buttonText.textContent;
  
  // Disable button และแสดง loading
  button.disabled = true;
  button.style.opacity = '0.7';
  buttonText.textContent = 'กำลังจอง...';
  
  // เพิ่ม loading spinner
  const spinner = document.createElement('div');
  spinner.className = 'loading';
  button.querySelector('.button-icon').replaceWith(spinner);
});

// Set minimum date to today
document.querySelector('input[type="date"]').min = new Date().toISOString().split('T')[0];

// Phone number formatting
const phoneInput = document.querySelector('input[type="tel"]');
phoneInput.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length > 3 && value.length <= 6) {
    value = value.slice(0, 3) + '-' + value.slice(3);
  } else if (value.length > 6) {
    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
  }
  e.target.value = value;
});

// Add smooth scroll animation on page load
window.addEventListener('load', function() {
  document.querySelector('.container').style.opacity = '0';
  setTimeout(() => {
    document.querySelector('.container').style.transition = 'opacity 0.6s ease';
    document.querySelector('.container').style.opacity = '1';
  }, 100);
});
</script>

</body>
</html>