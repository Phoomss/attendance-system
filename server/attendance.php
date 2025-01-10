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
        $query = "INSERT INTO " . $this->table_name . " (employee_id, attendance_date, attendance_time) 
                  VALUES (:employee_id, :attendance_date, :attendance_time)";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าที่ได้รับจากผู้ใช้
        if (empty($this->employee_id) || empty($this->attendance_date) || empty($this->attendance_time)) {
            throw new Exception("ข้อมูลไม่ครบถ้วน");
        }

        // ใช้ bindParam สำหรับผูกค่าตัวแปร
        $stmt->bindParam(':employee_id', $this->employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':attendance_date', $this->attendance_date, PDO::PARAM_STR);
        $stmt->bindParam(':attendance_time', $this->attendance_time, PDO::PARAM_STR);
        $stmt->bindParam(':attendance_time', $this->attendance_time, PDO::PARAM_STR);

        // ป้องกันข้อผิดพลาดในการ execute
        try {
            $stmt->execute();
            return [
                "success" => true,
                "message" => "บันทึกข้อมูลสำเร็จ"
            ];
        } catch (PDOException $e) {
            // จัดการข้อผิดพลาดโดยไม่แสดงข้อมูลสำคัญต่อผู้ใช้
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล"
            ];
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

    public function readInfo($id)
    {
        try {
            $query = "SELECT * 
                      FROM " . $this->table_name . " 
                      WHERE id = :id 
                      ORDER BY created_at DESC 
                      LIMIT 1";
    
            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($query);
            
            // ผูกค่าพารามิเตอร์
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
            // เรียกใช้งานคำสั่ง SQL
            $stmt->execute();
    
            // ดึงข้อมูล
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // หากเกิดข้อผิดพลาดในการเชื่อมต่อหรือการ query ให้จับข้อผิดพลาดและแสดงข้อความ
            return ["error" => "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage()];
        }
    }    

    public function checkAttendance($employee_id) {
        try {
            $attendance_date = date('Y-m-d'); // วันที่ปัจจุบัน
    
            // แก้ไขคำสั่ง SQL ให้ถูกต้อง
            $query = "SELECT * FROM " . $this->table_name . " WHERE employee_id = :employee_id AND attendance_date = :attendance_date";
            
            // เตรียมคำสั่ง SQL
            $stmt = $this->conn->prepare($query);
            
            // ผูกค่าพารามิเตอร์
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $stmt->bindParam(':attendance_date', $attendance_date, PDO::PARAM_STR);
    
            // เรียกใช้งานคำสั่ง SQL
            $stmt->execute();
    
            // ตรวจสอบผลลัพธ์
            if ($stmt->rowCount() > 0) {
                return ["exists" => true]; // มีข้อมูลการลงเวลา
            } else {
                return ["exists" => false]; // ไม่มีข้อมูลการลงเวลา
            }
        } catch (PDOException $e) {
            // หากเกิดข้อผิดพลาดในการเชื่อมต่อหรือการ query ให้จับข้อผิดพลาดและแสดงข้อความ
            return ["error" => "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage()];
        }
    }    

    public function update()
    {
        // สร้างคำสั่ง SQL สำหรับการอัพเดทข้อมูล
        $query = "UPDATE " . $this->table_name . " 
        SET employee_id = :employee_id,attendance_date = :attendance_date, attendance_time = :attendance_time, departure_time = :departure_time 
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าที่ได้รับจากผู้ใช้
        if (empty($this->id) || empty($this->employee_id)) {
            throw new Exception("ข้อมูลไม่ครบถ้วน");
        }

        // ผูกค่าตัวแปร
        $stmt->bindParam(':employee_id', $this->employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':attendance_date', $this->attendance_date, PDO::PARAM_STR);
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
