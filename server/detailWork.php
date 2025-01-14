<?php
require_once 'conn.php';

class DetailWork
{
    private $conn;
    private $table_attendances = "attendances";
    private $table_users = "users";
    private $table_leaves = "leaves";

    public $employee_id;

    public function __construct()
    {
        $database = new Conn();
        $db = $database->getConnection();
        $this->conn = $db;
    }

    public function readInfo($employee_id)
    {
        try {
            // Fetch attendance data
            $attendanceQuery = "SELECT u.id, u.title, u.firstname, u.surname, 
                                        a.attendance_date, a.created_at, a.departure_time
                                 FROM " . $this->table_users . " u
                                 INNER JOIN " . $this->table_attendances . " a ON u.id = a.employee_id
                                 WHERE u.id = :employee_id
                                 ORDER BY a.attendance_date DESC
                                 LIMIT 5";
            $attendanceStmt = $this->conn->prepare($attendanceQuery);
            $attendanceStmt->bindParam(':employee_id', $employee_id);
            $attendanceStmt->execute();
            $attendanceResult = $attendanceStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch leave data
            $leaveQuery = "SELECT u.id, u.title, u.firstname, u.surname, 
                                   l.leave_type, l.leave_date, l.reason
                           FROM " . $this->table_users . " u
                           INNER JOIN " . $this->table_leaves . " l ON u.id = l.employee_id
                           WHERE u.id = :employee_id
                           ORDER BY l.leave_date DESC
                           LIMIT 5";
            $leaveStmt = $this->conn->prepare($leaveQuery);
            $leaveStmt->bindParam(':employee_id', $employee_id);
            $leaveStmt->execute();
            $leaveResult = $leaveStmt->fetchAll(PDO::FETCH_ASSOC);

            // Return both attendance and leave data in separate arrays
            return [
                'attendance' => $attendanceResult,
                'leave' => $leaveResult
            ];
        } catch (Exception $e) {
            // Handle error and return an appropriate message
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function countAttendance($employee_id)
    {
        try {
            // คำสั่ง SQL สำหรับนับจำนวนการเข้าทำงาน
            $attendanceQuery = "SELECT COUNT(attendance_date) FROM " . $this->table_attendances . " WHERE employee_id = :employee_id";

            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($attendanceQuery);
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $stmt->execute();

            // ดึงค่าจำนวนการเข้าทำงาน
            $attendanceCount = $stmt->fetchColumn();

            return $attendanceCount;
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function countSickLeave($employee_id)
    {
        try {
            // คำสั่ง SQL สำหรับนับจำนวนลาป่วย
            $sickLeaveQuery = "SELECT COUNT(leave_type) AS prersonal_leave FROM " . $this->table_leaves . " WHERE leave_type = 'ลาป่วย' AND employee_id = :employee_id";

            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($sickLeaveQuery);
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $stmt->execute();

            // ดึงค่าจำนวนลาป่วย
            $sickLeaveCount = $stmt->fetchColumn();

            return $sickLeaveCount;
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function countPersonalLeave($employee_id)
    {
        try {
            // คำสั่ง SQL สำหรับนับจำนวนลากิจ
            $personalLeaveQuery = "SELECT COUNT(leave_type) AS sick_leave FROM " . $this->table_leaves . " WHERE leave_type = 'ลากิจ' AND employee_id = :employee_id";

            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($personalLeaveQuery);
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $stmt->execute();

            // ดึงค่าจำนวนลากิจ
            $personalLeaveCount = $stmt->fetchColumn();

            return $personalLeaveCount;
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
