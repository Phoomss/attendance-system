<?php
require_once 'conn.php';

class Attendance
{
    private $conn;
    private $table_name = "attendances";
    public $id;
    public $employee_id;
    public $attendance_time;
    public $departure_time;

    public function __construct()
    {
        $database = new Conn();
        $db = $database->getConnection();
        $this->conn = $db;
    }

    public function create()
    {
        // สร้างคำสั่ง SQL โดยใช้ prepared statement เพื่อป้องกัน SQL Injection
        $query = "INSERT INTO " . $this->table_name . " (employee_id, attendance_time) VALUES (:employee_id, :attendance_time)";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าที่ได้รับจากผู้ใช้
        if (empty($this->employee_id) || empty($this->attendance_time)) {
            throw new Exception("ข้อมูลไม่ครบถ้วน");
        }

        // ใช้ bindParam สำหรับผูกค่าตัวแปร
        $stmt->bindParam(':employee_id', $this->employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':attendance_time', $this->attendance_time, PDO::PARAM_STR);

        // ป้องกันข้อผิดพลาดในการ execute
        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            // จัดการข้อผิดพลาดโดยไม่แสดงข้อมูลสำคัญต่อผู้ใช้
            die("เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage());
        }
    }

    public function read()
    {
        // สร้างคำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage());
        }
    }

    public function readOne()
    {
        // สร้างคำสั่ง SQL เพื่อดึงข้อมูลแค่ 1 รายการ
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าของ id ก่อน
        if (empty($this->id)) {
            throw new Exception("ID ไม่ถูกต้อง");
        }

        // ผูกค่า ID
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage());
        }
    }

    public function update()
    {
        // สร้างคำสั่ง SQL สำหรับการอัพเดทข้อมูล
        $query = "UPDATE " . $this->table_name . " SET employee_id = :employee_id, attendance_time = :attendance_time, departure_time = :departure_time WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าที่ได้รับจากผู้ใช้
        if (empty($this->id) || empty($this->employee_id)) {
            throw new Exception("ข้อมูลไม่ครบถ้วน");
        }

        // ผูกค่าตัวแปร
        $stmt->bindParam(':employee_id', $this->employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':attendance_time', $this->attendance_time, PDO::PARAM_STR);
        $stmt->bindParam(':departure_time', $this->departure_time, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการอัพเดทข้อมูล: " . $e->getMessage());
        }
    }

    public function delete()
    {
        // สร้างคำสั่ง SQL สำหรับการลบข้อมูล
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าของ ID
        if (empty($this->id)) {
            throw new Exception("ID ไม่ถูกต้อง");
        }

        // ผูกค่า ID
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage());
        }
    }
}
?>
