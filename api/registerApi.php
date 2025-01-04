<?php
require_once '../server/conn.php';
require_once '../server/auth.php';

session_start();

$database = new Conn();
$db = $database->getConnection();

$auth = new Auth($db);

// ตรวจสอบการส่งข้อมูลจากฟอร์มว่าได้ส่งข้อมูลมาหรือไม่
$auth->title = $_POST['title'];
$auth->firstname = $_POST['firstname'];
$auth->surname = $_POST['surname'];
$auth->username = $_POST['username'];
$auth->email = $_POST['email'];
$auth->password = $_POST['password'];

// หากข้อมูลครบถ้วน
if ($auth->register()) {
    echo json_encode([
        "success" => true,
        "message" => "ลงทะเบียนสำเร็จ",
        "status_code" => 200,
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "ลงทะเบียนไม่สำเร็จ",
        "status_code" => 500,
    ]);
}

?>
