<?php
require_once '../server/conn.php';
require_once '../server/user.php';

session_start();

$database = new Conn();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'update') {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "ไม่ได้ระบุ ID ของผู้ใช้",
                    "status_code" => 400,
                ]);
                exit;
            }

            $id = $_POST['id'];
            $user->title = $_POST['title'];
            $user->firstname = $_POST['firstname'];
            $user->surname = $_POST['surname'];
            $user->username = $_POST['username'];
            $user->phone = $_POST['phone'];

            $stmt = $user->update($id);

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
        } else if ($action === 'updateProfile') {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "ไม่ได้ระบุ ID ของผู้ใช้",
                    "status_code" => 400,
                ]);
                exit;
            }

            $id = $_POST['id'];
            $user->title = $_POST['title'];
            $user->firstname = $_POST['firstname'];
            $user->surname = $_POST['surname'];
            $user->username = $_POST['username'];
            $user->phone = $_POST['phone'];
            

            $stmt = $user->updateProfile($id);

            if ($stmt) {
                echo json_encode([
                    "success" => true,
                    "message" => "แก้ไขข้อมูลส่วนตัวสำเร็จ",
                    "status_code" => 200,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "แก้ไขข้อมูลส่วนตัวไม่สำเร็จ",
                    "status_code" => 500,
                ]);
            }
        } else if ($action === 'delete') {
            $user->id = $_POST['id'];
            $stmt = $user->delete($id);

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
