<?php
require_once '../server/conn.php';
class LeaveCountApi
{
    private $conn;

    // กำหนดการเชื่อมต่อฐานข้อมูลใน constructor
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // ฟังก์ชันดึงข้อมูลการลาแยกประเภทในแต่ละเดือน
    public function getLeaveData()
    {
        $sql = "
        SELECT 
            DATE_FORMAT(leave_date, '%Y-%m') AS leave_month,
            employee_id,
            COUNT(CASE WHEN leave_type = 'ลาป่วย' THEN 1 END) AS sick_leave_count,
            COUNT(CASE WHEN leave_type = 'ลากิจ' THEN 1 END) AS personal_leave_count
        FROM 
            leaves
        GROUP BY 
            leave_month, employee_id
        ORDER BY 
            leave_month DESC, employee_id;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // คืนค่าข้อมูลที่ดึงมา
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ฟังก์ชันเตรียมข้อมูลสำหรับกราฟ
    public function prepareChartData()
    {
        $leaveData = $this->getLeaveData();

        $months = [];
        $sickLeaveCounts = [];
        $personalLeaveCounts = [];

        foreach ($leaveData as $data) {
            $months[] = $data['leave_month'];
            $sickLeaveCounts[] = $data['sick_leave_count'];
            $personalLeaveCounts[] = $data['personal_leave_count'];
        }

        return [
            'months' => $months,
            'sickLeaveCounts' => $sickLeaveCounts,
            'personalLeaveCounts' => $personalLeaveCounts
        ];
    }
}

$leaveCount = new LeaveCountApi($conn);
$data = $leaveCount->prepareChartData();
echo json_encode($data);  
?>