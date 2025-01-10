<?php
require_once '../server/conn.php';
require_once '../server/attendance.php';

session_start();

$database = new Conn();
$db = $database->getConnection();
$attendance = new Attendance($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            $attendance->employee_id = $_POST['employee_id'];
            $attendance->attendance_date = $_POST['attendance_date'];
            $attendance->attendance_time = $_POST['attendance_time'];
            // $attendance->departure_time = $_POST['departure_time'];

            $stmt = $attendance->create();

            if ($stmt) {
                echo json_encode([
                    "success" => true,
                    "message" => "บันทึกข้อมูลสำเร็จ",
                    "status_code" => 200,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "บันทึกข้อมูลไม่สำเร็จ",
                    "status_code" => 500,
                ]);
            }
        } else if ($action === 'update') {
            $attendance->id = $_POST['id'];
            $attendance->employee_id = $_POST['employee_id'];
            $attendance->attendance_date = $_POST['attendance_date'];
            $attendance->attendance_time = $_POST['attendance_time'];
            $attendance->departure_time = $_POST['departure_time'];

            $stmt = $attendance->update();

            if ($stmt) {
                echo json_encode([
                    "success" => true,
                    "message" => "แก้ไขข้อมูลสำเร็จ",
                    "status_code" => 200,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "แก้ไขข้อมูลไม่สำเร็จ",
                    "status_code" => 500,
                ]);
            }
        } else if ($action === 'delete') {
            $attendance->id = $_POST['id'];
            $stmt = $attendance->delete();

            if ($stmt) {
                echo json_encode([
                    "success" => true,
                    "message" => "ลบข้อมูลสำเร็จ",
                    "status_code" => 200,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "ลบข้อมูลไม่สำเร็จ",
                    "status_code" => 500,
                ]);
            }
        } else if ($action === "checkAttendance") {
            $employee_id = $_POST['employee_id'];

            // เรียกใช้ฟังก์ชัน checkAttendance
            $attendanceStatus = $attendance->checkAttendance($employee_id);

            // ส่งผลลัพธ์กลับไปยัง client
            echo json_encode($attendanceStatus);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action === 'getLatest') {
            $attendance->id = $_GET['id'];
            $stmt = $attendance->readInfo($_GET['id']);

            if ($stmt->num_rows > 0) {
                $data = $stmt->fetch_assoc();
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลล่าสุด']);
            }
            exit();
        }
    }
}
