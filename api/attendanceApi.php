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
            $attendance->attendance_time = $_POST['attendance_time'];
            $attendance->departure_time = $_POST['departure_time'];

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
        }
    }
}
