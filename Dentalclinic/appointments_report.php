<?php
include 'index.php';

$start      = $_GET['start']      ?? '';
$end        = $_GET['end']        ?? '';
$dentist_id = isset($_GET['dentist_id']) ? intval($_GET['dentist_id']) : 0;

$where = " WHERE 1=1 ";
if ($start)      $where .= " AND appointment_date >= '".mysqli_real_escape_string($db,$start)."' ";
if ($end)        $where .= " AND appointment_date <= '".mysqli_real_escape_string($db,$end)."' ";
if ($dentist_id) $where .= " AND a.dentist_id = {$dentist_id} ";

$sql = "SELECT a.*, d.dentist_name, s.service_name
        FROM appointmentsb a
        LEFT JOIN dentists d ON a.dentist_id = d.dentist_id
        LEFT JOIN services s ON a.service_id = s.service_id
        {$where}
        ORDER BY appointment_date, appointment_time";

$result = mysqli_query($db, $sql);

// Export Excel
if (isset($_GET['export']) && $_GET['export'] == '1') {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=appointments_report_".date('Ymd_His').".xls");

    echo "<table border='1'>";
    echo "<tr>
            <th>วันที่</th>
            <th>เวลา</th>
            <th>ผู้ป่วย</th>
            <th>เบอร์โทร</th>
            <th>หมอ</th>
            <th>บริการ</th>
            <th>สถานะ</th>
          </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['appointment_date']}</td>
                <td>{$row['appointment_time']}</td>
                <td>".htmlspecialchars($row['patient_name'])."</td>
                <td>".htmlspecialchars($row['patient_phone'])."</td>
                <td>".htmlspecialchars($row['dentist_name'])."</td>
                <td>".htmlspecialchars($row['service_name'])."</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    echo "</table>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายงานการนัดหมาย</title>
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  body { font-family:"Prompt",sans-serif; padding:20px; background:#f5f7fb; }
  .box {
      max-width:900px; margin:auto; background:#fff; padding:20px;
      border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.05);
  }
  h2 { margin-top:0; color:#1c325c; }
  table { border-collapse:collapse; width:100%; margin-top:12px; font-size:14px; }
  th,td { border:1px solid #ddd; padding:6px 8px; }
  th { background:#eef2ff; }
  label { font-size:14px; margin-right:6px; }
  input, select { padding:4px 6px; font-family:"Prompt",sans-serif; }
  .toolbar { margin-bottom:8px; }
  a { color:#2c6fff; text-decoration:none; }
</style>
</head>
<body>
<div class="box">
  <h2>รายงานการนัดหมาย</h2>
  <div class="toolbar">
    <form method="get">
      <label>วันที่เริ่ม</label>
      <input type="date" name="start" value="<?php echo htmlspecialchars($start); ?>">
      <label>ถึง</label>
      <input type="date" name="end" value="<?php echo htmlspecialchars($end); ?>">
      <label>รหัสหมอ</label>
      <input type="number" name="dentist_id" value="<?php echo $dentist_id ?: ''; ?>" style="width:80px;">
      <button type="submit">ค้นหา</button>
      <button type="submit" name="export" value="1">ดาวน์โหลด Excel</button>
      <a href="Calculatetaxes.php">← กลับหน้าจองคิว</a>
    </form>
  </div>

  <table>
    <tr>
      <th>วันที่</th>
      <th>เวลา</th>
      <th>ผู้ป่วย</th>
      <th>เบอร์โทร</th>
      <th>หมอ</th>
      <th>บริการ</th>
      <th>สถานะ</th>
    </tr>
    <?php
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['appointment_date']}</td>
                <td>{$row['appointment_time']}</td>
                <td>".htmlspecialchars($row['patient_name'])."</td>
                <td>".htmlspecialchars($row['patient_phone'])."</td>
                <td>".htmlspecialchars($row['dentist_name'])."</td>
                <td>".htmlspecialchars($row['service_name'])."</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
