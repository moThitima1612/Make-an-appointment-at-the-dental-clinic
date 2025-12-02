<?php
include 'index.php';

// ดึงข้อมูลนัด
$events = [];
$sql = "SELECT appointment_id, patient_name, appointment_date,
               appointment_time, status, service_id
        FROM appointmentsb";
$result = mysqli_query($db, $sql);

$serviceColor = [
    1 => "#667eea",
    2 => "#764ba2",
    3 => "#0ba360",
    4 => "#f093fb",
    5 => "#4facfe"
];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $start = $row['appointment_date'];
        if (!empty($row['appointment_time'])) $start .= 'T'.$row['appointment_time'];

        $color = '#667eea';
        if ($row['status'] == 'confirmed') $color = '#10b981';
        elseif ($row['status'] == 'pending') $color = '#f59e0b';
        elseif ($row['status'] == 'cancelled') $color = '#ef4444';

        if (isset($serviceColor[$row['service_id']])) {
            $color = $serviceColor[$row['service_id']];
        }

        $events[] = [
            'id'          => $row['appointment_id'],
            'title'       => $row['patient_name'],
            'start'       => $start,
            'color'       => $color,
            'service_id'  => $row['service_id'],
            'status'      => $row['status']
        ];
    }
}

// นับจำนวนคิวต่อวัน
$dailyCounts = [];
$sqlCount = "SELECT appointment_date, COUNT(*) AS total
             FROM appointmentsb
             WHERE status <> 'cancelled'
             GROUP BY appointment_date";
$resCount = mysqli_query($db, $sqlCount);
while ($r = mysqli_fetch_assoc($resCount)) {
    $dailyCounts[$r['appointment_date']] = $r['total'];
}

// วันหยุดไทย (2025)
$thaiHolidays = [
    ['date' => '2025-01-01', 'title' => 'วันขึ้นปีใหม่'],
    ['date' => '2025-02-12', 'title' => 'วันมาฆบูชา'],
    ['date' => '2025-04-06', 'title' => 'วันจักรี'],
    ['date' => '2025-04-13', 'title' => 'วันสงกรานต์'],
    ['date' => '2025-04-14', 'title' => 'วันสงกรานต์'],
    ['date' => '2025-04-15', 'title' => 'วันสงกรานต์'],
    ['date' => '2025-05-01', 'title' => 'วันแรงงานแห่งชาติ'],
    ['date' => '2025-05-05', 'title' => 'วันฉัตรมงคล'],
    ['date' => '2025-07-12', 'title' => 'วันอาสาฬหบูชา'],
    ['date' => '2025-07-13', 'title' => 'วันเข้าพรรษา'],
    ['date' => '2025-12-05', 'title' => 'วันพ่อแห่งชาติ'],
    ['date' => '2025-12-10', 'title' => 'วันรัฐธรรมนูญ'],
    ['date' => '2025-12-31', 'title' => 'วันสิ้นปี'],
];

foreach ($thaiHolidays as $h) {
    $events[] = [
        'title'   => $h['title'],
        'start'   => $h['date'],
        'display' => 'background',
        'color'   => '#fef3c7'
    ];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ปฏิทินจองคิว - คลินิกทันตกรรม</title>

<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css">
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

    .container {
        max-width: 1400px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .header-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.5);
        animation: slideDown 0.6s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .title-section {
        flex: 1;
    }

    .main-title {
        font-size: 36px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .subtitle {
        font-size: 16px;
        color: #64748b;
        font-weight: 400;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 14px 28px;
        border-radius: 14px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .calendar-wrapper {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.5);
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #calendar {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* FullCalendar Custom Styles */
    .fc {
        font-family: "Prompt", sans-serif;
    }

    .fc .fc-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .fc .fc-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: #764ba2;
    }

    .fc-daygrid-day:hover {
        background: rgba(102, 126, 234, 0.05);
        transition: background 0.3s ease;
    }

    .fc-event {
        border-radius: 8px;
        border: none;
        padding: 4px 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .fc-event:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .fc .fc-toolbar-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    .legend-section {
        margin-top: 25px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 16px;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .legend-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
    }

    .legend-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        background: white;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .legend-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .legend-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .legend-text {
        font-size: 14px;
        color: #475569;
        font-weight: 500;
    }

    /* Custom SweetAlert2 Styles */
    .swal2-popup {
        border-radius: 24px;
        font-family: "Prompt", sans-serif;
    }

    .swal2-title {
        font-weight: 700;
        color: #1e293b;
    }

    .swal2-confirm {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 12px 32px;
        font-weight: 600;
    }

    .swal2-cancel {
        border-radius: 12px;
        padding: 12px 32px;
        font-weight: 600;
    }

    .detail-card {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 15px;
    }

    .detail-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-row {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #475569;
        min-width: 100px;
    }

    .detail-value {
        color: #1e293b;
    }

    .status-select {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        font-size: 15px;
        font-family: "Prompt", sans-serif;
        background: white;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .status-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .qr-container {
        background: white;
        padding: 20px;
        border-radius: 16px;
        border: 2px dashed #cbd5e1;
        text-align: center;
    }

    .qr-code {
        margin: 15px auto;
        display: inline-block;
    }

    .qr-label {
        font-size: 14px;
        color: #64748b;
        margin-top: 12px;
        font-weight: 500;
    }

    .print-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    @media (max-width: 768px) {
        .main-title {
            font-size: 28px;
        }
        
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .action-buttons {
            width: 100%;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
        }
        
        .legend-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <div class="header-content">
            <div class="title-section">
                <h1 class="main-title">
                    <span>🦷</span>
                    <span>ปฏิทินจองคิวคลินิกทันตกรรม</span>
                </h1>
                <p class="subtitle">จัดการนัดหมายและติดตามสถานะการจองคิวได้อย่างสะดวก</p>
            </div>
            <div class="action-buttons">
                <a href="Calculatetaxes.php" class="btn btn-primary">
                    <span>📅</span>
                    <span>จองคิวใหม่</span>
                </a>
                <a href="javascript:window.print()" class="btn btn-secondary">
                    <span>🖨️</span>
                    <span>พิมพ์ปฏิทิน</span>
                </a>
            </div>
        </div>
    </div>

    <div class="calendar-wrapper">
        <div id="calendar"></div>

        <div class="legend-section">
            <div class="legend-title">📊 คำอธิบายสัญลักษณ์</div>
            <div class="legend-grid">
                <div class="legend-item">
                    <span class="legend-dot" style="background:#10b981;"></span>
                    <span class="legend-text">ยืนยันแล้ว</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#f59e0b;"></span>
                    <span class="legend-text">รออนุมัติ</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#ef4444;"></span>
                    <span class="legend-text">ยกเลิก</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#fef3c7;"></span>
                    <span class="legend-text">วันหยุดนักขัตฤกษ์</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'th',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        height: 700,
        validRange: {
            start: new Date().toISOString().split("T")[0]
        },
        editable: true,
        events: <?php echo json_encode($events, JSON_UNESCAPED_UNICODE); ?>,

        eventDrop: function(info) {
            var start = info.event.start;
            var dateStr = start.toISOString().slice(0,10);
            var timeStr = start.toTimeString().slice(0,8);

            fetch("update_date.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "id=" + info.event.id + "&date=" + dateStr + "&time=" + timeStr
            }).then(r => r.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire({
                        icon: "error",
                        title: "ผิดพลาด",
                        text: data.msg || "ไม่สามารถเลื่อนวันนัดได้",
                        confirmButtonColor: "#667eea"
                    });
                    info.revert();
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "สำเร็จ!",
                        text: "เลื่อนวันนัดเรียบร้อย",
                        confirmButtonColor: "#667eea",
                        timer: 2000
                    });
                }
            }).catch(() => {
                Swal.fire({
                    icon: "error",
                    title: "ผิดพลาด",
                    text: "การเชื่อมต่อผิดพลาด",
                    confirmButtonColor: "#667eea"
                });
                info.revert();
            });
        },

        eventClick: function(info) {
            var id = info.event.id;
            var title = info.event.title;
            var start = info.event.start;

            var dateStr = start.toLocaleDateString('th-TH', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
            var timeStr = start.toLocaleTimeString('th-TH', {
                hour: '2-digit', minute: '2-digit'
            });

            Swal.fire({
                title: '📋 รายละเอียดการนัดหมาย',
                html: `
<div style="font-family:'Prompt',sans-serif;text-align:left;">
    <div class="detail-card">
        <div class="detail-title">
            <span>👤</span>
            <span>ข้อมูลการนัด</span>
        </div>
        <div class="detail-row">
            <div class="detail-label">ชื่อผู้ป่วย:</div>
            <div class="detail-value">${title}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">วันที่นัด:</div>
            <div class="detail-value">${dateStr}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">เวลา:</div>
            <div class="detail-value">${timeStr}</div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-title">
            <span>🔄</span>
            <span>อัปเดตสถานะ</span>
        </div>
        <select id="statusSelect" class="status-select">
            <option value="confirmed">✅ ยืนยันแล้ว</option>
            <option value="pending">⏳ รออนุมัติ</option>
            <option value="cancelled">❌ ยกเลิก</option>
        </select>
    </div>

    <div class="qr-container">
        <div id="qrcode" class="qr-code"></div>
        <div class="qr-label">🔍 QR Code สำหรับเช็กอิน</div>
        <a href="print_ticket.php?id=${id}" target="_blank" class="print-btn">
            🎫 พิมพ์ใบจอง
        </a>
    </div>
</div>
                `,
                width: 520,
                showCancelButton: true,
                confirmButtonText: '💾 บันทึกสถานะ',
                cancelButtonText: '❌ ปิด',
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#94a3b8',
                didOpen: () => {
                    new QRCode(document.getElementById("qrcode"), {
                        text: "checkin.php?id=" + id,
                        width: 180,
                        height: 180
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var newStatus = document.getElementById("statusSelect").value;

                    fetch("update_status.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + id + "&status=" + newStatus
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "อัปเดตสำเร็จ!",
                                text: "สถานะได้รับการบันทึกแล้ว",
                                confirmButtonColor: "#667eea",
                                timer: 2000
                            }).then(()=>{ location.reload(); });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "เกิดข้อผิดพลาด",
                                text: data.msg || "ไม่สามารถบันทึกสถานะได้",
                                confirmButtonColor: "#667eea"
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: "error",
                            title: "เกิดข้อผิดพลาด",
                            text: "การเชื่อมต่อผิดพลาด",
                            confirmButtonColor: "#667eea"
                        });
                    });
                }
            });
        }
    });

    calendar.render();
});
</script>
</body>
</html>