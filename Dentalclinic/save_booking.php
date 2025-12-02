<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "index.php"; // ไฟล์เชื่อมต่อฐานข้อมูล ($db)

echo "<pre>";
print_r($_POST);
echo "</pre>";

// รับค่าจากฟอร์ม
$patient_name      = mysqli_real_escape_string($db, $_POST['patient_name'] ?? '');
$patient_phone     = mysqli_real_escape_string($db, $_POST['patient_phone'] ?? '');
$service_id        = mysqli_real_escape_string($db, $_POST['service_id'] ?? '');
$dentist_id        = mysqli_real_escape_string($db, $_POST['dentist_id'] ?? '');
$appointment_date  = mysqli_real_escape_string($db, $_POST['appointment_date'] ?? '');
$appointment_time  = mysqli_real_escape_string($db, $_POST['appointment_time'] ?? '');

// ตรวจสอบว่ากรอกครบไหม
if (
    empty($patient_name) || 
    empty($patient_phone) || 
    empty($service_id) || 
    empty($dentist_id) || 
    empty($appointment_date) || 
    empty($appointment_time)
) {
    die("❌ กรุณากรอกข้อมูลให้ครบทุกช่อง");
}

// ตรวจสอบคิวซ้ำ
$check_sql = "
    SELECT * FROM appointmentsb
    WHERE dentist_id = '$dentist_id'
    AND appointment_date = '$appointment_date'
    AND appointment_time = '$appointment_time'
";
$check_result = mysqli_query($db, $check_sql);

if (!$check_result) {
    die("❌ SQL Error (Check): " . mysqli_error($db));
}

if (mysqli_num_rows($check_result) > 0) {
    die("❌ เวลานี้ถูกจองแล้ว กรุณาเลือกเวลาอื่น");
}

// บันทึกคิว
$insert_sql = "
INSERT INTO appointmentsb
(patient_name, patient_phone, service_id, dentist_id, appointment_date, appointment_time, status)
VALUES
('$patient_name', '$patient_phone', '$service_id', '$dentist_id', '$appointment_date', '$appointment_time', 'pending')
";

$insert_result = mysqli_query($db, $insert_sql);

if (!$insert_result) {
    die("❌ SQL Error (Insert): " . mysqli_error($db));
}

// สำเร็จ
echo "
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
Swal.fire({
    title: '🎉 การจองสำเร็จ!',
  html: `
    <div style='
        font-size:16px; 
        margin-top:10px; 
        line-height:1.6;
        display:flex; 
        justify-content:center;
    '>
        <table style='border-collapse:collapse;'>
            <tr>
                <td style='font-weight:bold; padding:4px 10px;'>ชื่อผู้ป่วย</td>
                <td style='padding:4px 10px;'>: $patient_name</td>
            </tr>
            <tr>
                <td style='font-weight:bold; padding:4px 10px;'>เบอร์โทร</td>
                <td style='padding:4px 10px;'>: $patient_phone</td>
            </tr>
            <tr>
                <td style='font-weight:bold; padding:4px 10px;'>วันที่จอง</td>
                <td style='padding:4px 10px;'>: $appointment_date</td>
            </tr>
            <tr>
                <td style='font-weight:bold; padding:4px 10px;'>เวลา</td>
                <td style='padding:4px 10px;'>: $appointment_time</td>
            </tr>
        </table>
    </div>
`,

    icon: 'success',
    confirmButtonText: 'กลับไปหน้าจองคิว',
    confirmButtonColor: '#3085d6'
}).then(() => {
    window.location = 'HomePage.php';
});
</script>
";
exit();


