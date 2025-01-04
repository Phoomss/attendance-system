<?php
require_once '../server/conn.php';
require_once '../server/auth.php';

session_start();

$database = new Conn();
$db = $database->getConnection();

$auth = new Auth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = isset($_POST['identifier']) ? $_POST['identifier'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($identifier && $password) {
        $user_data = $auth->login($identifier, $password);

        if ($user_data['success']) {
            $_SESSION['userInfo'] = $user_data['data'];
            $_SESSION['user_id'] = $user_data['data']['id'];
            $_SESSION['username'] = $user_data['data']['username'];
            $_SESSION['email'] = $user_data['data']['email'];
            $_SESSION['role'] = $user_data['data']['role'];

            echo json_encode([
                "success" => true,
                "message" => "เข้าสู่ระบบสำเร็จ",
                "status_code" => 200,
                "role" => $user_data['data']['role'],
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $user_data['message'],
                "status_code" => 500,
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "กรุณากรอกข้อมูลให้ครบถ้วน",
            "status_code" => 500,
        ]);
    }
}
?>
