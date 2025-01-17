<?php
require_once '../server/conn.php';

class AttendanceDailyApi {
    private $conn;

    // กำหนดการเชื่อมต่อฐานข้อมูลใน constructor
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ฟังก์ชันดึงข้อมูลการเข้าทำงาน
    public function getAttendanceData() {
        try {
            // คำสั่ง SQL
            $query = "
                SELECT 
                    u.firstname, 
                    DATE_FORMAT(a.attendance_time, '%Y-%m') AS attendance_month, 
                    COUNT(a.attendance_time) AS attendance_count
                FROM 
                    attendances a
                INNER JOIN 
                    users u 
                ON 
                    a.employee_id = u.id
                GROUP BY 
                    u.id, DATE_FORMAT(a.attendance_time, '%Y-%m')
                ORDER BY 
                    u.firstname, attendance_month;
            ";

            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($query);
            // ดำเนินการคำสั่ง SQL
            $stmt->execute();
            // ดึงข้อมูลผลลัพธ์ทั้งหมด
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // เตรียมข้อมูลให้อยู่ในรูปแบบที่ต้องการ
            $data = [];
            foreach ($result as $row) {
                $firstname = $row['firstname'];
                $month = $row['attendance_month'];
                $count = $row['attendance_count'];

                // จัดกลุ่มข้อมูลตามชื่อพนักงาน
                if (!isset($data[$firstname])) {
                    $data[$firstname] = [];
                }
                $data[$firstname][$month] = $count;
            }

            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return []; // คืนค่าผลลัพธ์เป็น array ว่างกรณีเกิดข้อผิดพลาด
        }
    }
}

// การใช้งาน
$attendance = new AttendanceDailyApi($conn);  
$data = $attendance->getAttendanceData(); 

// แสดงผลข้อมูลที่ได้
echo json_encode($data);  // ส่งข้อมูลในรูปแบบ JSON
?>
