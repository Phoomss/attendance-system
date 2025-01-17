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
                                 LIMIT 10";
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
                           ";
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

    public function getDailyAttendanceCount()
    {
        try {
            // ดึงวันที่จากเครื่องในรูปแบบ 'YYYY-MM-DD'
            $currentDate = date('Y-m-d');

            // สร้างคำสั่ง SQL เพื่อดึงข้อมูลการเข้าทำงานในวันปัจจุบัน
            $query = "
                SELECT 
                    a.attendance_date,
                    COUNT(*) AS total_attendances
                FROM 
                    " . $this->table_attendances . " a
                WHERE 
                    a.attendance_date = :currentDate
                GROUP BY 
                    a.attendance_date
            ";

            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($query);

            // Binding พารามิเตอร์สำหรับวันที่ปัจจุบัน
            $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);

            // ดำเนินการคำสั่ง SQL
            $stmt->execute();

            // ดึงข้อมูลผลลัพธ์
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // ส่งคืนผลลัพธ์
            return $result;
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function getDailyDepartureCount()
    {
        try {
            // ใช้วันที่ของเครื่อง (NOW()) เพื่อคำนวณจำนวนการออกจากงานในวันนี้
            $query = "
                SELECT 
                    a.attendance_date,
                    COUNT(*) AS total_departures
                FROM 
                    " . $this->table_attendances . " a
                WHERE 
                    a.attendance_date = CURDATE()  -- ใช้วันที่ของเครื่อง
                    AND a.departure_time IS NOT NULL
                GROUP BY 
                    a.attendance_date
            ";

            // เตรียม statement
            $stmt = $this->conn->prepare($query);

            // ดำเนินการคำสั่ง SQL
            $stmt->execute();

            // ดึงข้อมูลผลลัพธ์
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // ตรวจสอบหากมีข้อมูล
            if ($result) {
                // ส่งคืนจำนวนการออกจากงาน
                return $result['total_departures'];
            } else {
                // หากไม่มีข้อมูลคืนค่า 0
                return 0;
            }
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function getLeaveCountByDate()
    {
        try {
            // ใช้วันที่ของเครื่อง (NOW()) เพื่อแยกประเภทการลา
            $query = "
                SELECT 
                    leave_date,
                    COUNT(CASE WHEN leave_type = 'ลาป่วย' THEN 1 END) AS sick_leave_count,
                    COUNT(CASE WHEN leave_type = 'ลากิจ' THEN 1 END) AS personal_leave_count
                FROM 
                    " . $this->table_leaves . "
                WHERE 
                    leave_date = CURDATE()  -- ใช้วันที่ของเครื่อง
                GROUP BY 
                    leave_date
                ORDER BY 
                    leave_date DESC
            ";

            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($query);

            // ดำเนินการคำสั่ง SQL
            $stmt->execute();

            // ดึงข้อมูลที่ได้มา
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ส่งคืนข้อมูลผลลัพธ์
            return $result;
        } catch (Exception $e) {
            // จัดการข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

 
}
