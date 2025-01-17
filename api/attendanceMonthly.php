
<?php
require_once '../server/conn.php';

class AttendanceMonthly
{
    private $conn;

    // กำหนดการเชื่อมต่อฐานข้อมูลใน constructor
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getMonthlyAttendanceData()
    {
        try {
            $query = "
                SELECT 
                    DATE_FORMAT(a.attendance_time, '%Y-%m') AS attendance_month,
                    COUNT(a.attendance_time) AS total_attendance_count
                FROM 
                    attendances a
                GROUP BY 
                    DATE_FORMAT(a.attendance_time, '%Y-%m')
                ORDER BY 
                    attendance_month;
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($result as $row) {
                $month = $row['attendance_month'];
                $count = $row['total_attendance_count'];

                $data[$month] = $count;
            }

            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

// การใช้งาน
$attendance = new AttendanceMonthly($conn);
$data = $attendance->getMonthlyAttendanceData();

// แสดงผลข้อมูลในรูปแบบ JSON สำหรับใช้งานกับ Chart.js
echo json_encode($data);
?>
