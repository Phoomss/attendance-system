<?php
    require_once '../server/conn.php';
    require_once '../server/leave.php';

    session_start();

    $database = new Conn();
    $db = $database->getConnection();

    $leave = new Leave($db);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['action'])){
            $action = $_POST['action'];

            if($action === 'create'){
                $leave->employee_id = $_POST['employee_id'];
                $leave->leave_type = $_POST['leave_type'];
                $leave->leave_date = $_POST['leave_date'];
                $leave->reason = $_POST['reason'];

                $stmt = $leave->create();

                if($stmt){
                    echo json_encode([
                        "success" => true,
                        "message" => "บันทึกข้อมูลสำเร็จ",
                        "status_code" => 200,
                    ]);
                }else{
                    echo json_encode([
                        "success" => false,
                        "message" => "บันทึกข้อมูลไม่สำเร็จ",
                        "status_code" => 500,
                    ]);
                }
            }else if($action === 'update'){
                $leave->id = $_POST['id'];
                $leave->employee_id = $_POST['employee_id'];
                $leave->leave_type = $_POST['leave_type'];
                $leave->leave_date = $_POST['leave_date'];
                $leave->reason = $_POST['reason'];

                $stmt = $leave->update();

                if($stmt){
                    echo json_encode([
                        "success" => true,
                        "message" => "แก้ไขข้อมูลสำเร็จ",
                        "status_code" => 200,
                    ]);
                }else{
                    echo json_encode([
                        "success" => false,
                        "message" => "แก้ไขข้อมูลไม่สำเร็จ",
                        "status_code" => 500,
                    ]);
                }
            }else if($action === 'delete'){
                $leave->id = $_POST['id'];

                $stmt = $leave->delete();

                if($stmt){
                    echo json_encode([
                        "success" => true,
                        "message" => "ลบข้อมูลสำเร็จ",
                        "status_code" => 200,
                    ]);
                }else{
                    echo json_encode([
                        "success" => false,
                        "message" => "ลบข้อมูลไม่สำเร็จ",
                        "status_code" => 500,
                    ]);
                }
            }
        }
    }
?>