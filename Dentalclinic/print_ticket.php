<?php
include 'index.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT a.*, d.name, s.service_name
        FROM appointmentsb a
        LEFT JOIN dentists d ON a.dentist_id = d.dentist_id
        LEFT JOIN services s ON a.service_id = s.service_id
        WHERE appointment_id = {$id}";
$res = mysqli_query($db, $sql);
$appt = mysqli_fetch_assoc($res);
if (!$appt) {
    die("ไม่พบข้อมูลการนัด");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>บัตรคิว #<?php echo $appt['appointment_id']; ?></title>
<style>
  body { margin:0; font-family:"Prompt",sans-serif; }
  .ticket {
      width: 80mm;
      padding:10px;
      font-size:13px;
  }
  .center { text-align:center; }
  .line { border-top:1px dashed #000; margin:6px 0; }
  @media print {
      button { display:none; }
      body { margin:0; }
  }
</style>
</head>
<body onload="window.print();">
<div class="ticket">
    <div class="center">
        <strong>คลินิกทันตกรรม</strong><br>
        <span>บัตรจองคิว</span>
    </div>
    <div class="line"></div>
    เลขที่นัด: <?php echo $appt['appointment_id']; ?><br>
    ผู้ป่วย: <?php echo htmlspecialchars($appt['patient_name']); ?><br>
    หมอ: <?php echo htmlspecialchars($appt['dentist_name']); ?><br>
    บริการ: <?php echo htmlspecialchars($appt['service_name']); ?><br>
    วันที่: <?php echo $appt['appointment_date']; ?><br>
    เวลา: <?php echo substr($appt['appointment_time'],0,5); ?> น.<br>
    สถานะ: <?php echo $appt['status']; ?><br>
    <div class="line"></div>
    <div class="center">กรุณามาก่อนเวลาอย่างน้อย 10 นาที</div>
</div>
<button onclick="window.print();">พิมพ์อีกครั้ง</button>
</body>
</html>
