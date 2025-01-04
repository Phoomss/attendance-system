<?php
class Leave {
    private $conn;
    private $table_name = "leaves";
    public $id;
    public $employee_id;
    public $leave_type;
    public $leave_date;
    public $reason;

    public function __construct() {
        $database = new Conn();
        $db = $database->getConnection();
        $this->conn = $db;
    }

    // Method สำหรับสร้างการลา
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (employee_id, leave_type, leave_date, reason) VALUES (:employee_id, :leave_type, :leave_date, :reason)";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าที่รับเข้ามาจากผู้ใช้
        if (empty($this->employee_id) || empty($this->leave_type) || empty($this->leave_date) || empty($this->reason)) {
            throw new Exception("ข้อมูลไม่ครบถ้วน");
        }

        // ผูกค่าตัวแปร
        $stmt->bindParam(':employee_id', $this->employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':leave_type', $this->leave_type, PDO::PARAM_STR);
        $stmt->bindParam(':leave_date', $this->leave_date, PDO::PARAM_STR);
        $stmt->bindParam(':reason', $this->reason, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            // การจัดการข้อผิดพลาดที่เหมาะสม
            die("เกิดข้อผิดพลาดในการบันทึกข้อมูลการลา: " . $e->getMessage());
        }
    }

    // Method สำหรับดึงข้อมูลการลาทั้งหมด
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการดึงข้อมูลการลา: " . $e->getMessage());
        }
    }

    // Method สำหรับดึงข้อมูลการลา 1 รายการ
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบว่า id ถูกต้อง
        if (empty($this->id)) {
            throw new Exception("ID ไม่ถูกต้อง");
        }

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการดึงข้อมูลการลา: " . $e->getMessage());
        }
    }

    // Method สำหรับอัพเดทข้อมูลการลา
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET employee_id = :employee_id, leave_type = :leave_type, leave_date = :leave_date, reason = :reason WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าที่ได้รับจากผู้ใช้
        if (empty($this->id) || empty($this->employee_id)) {
            throw new Exception("ข้อมูลไม่ครบถ้วน");
        }

        // ผูกค่าตัวแปร
        $stmt->bindParam(':employee_id', $this->employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':leave_type', $this->leave_type, PDO::PARAM_STR);
        $stmt->bindParam(':leave_date', $this->leave_date, PDO::PARAM_STR);
        $stmt->bindParam(':reason', $this->reason, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการอัพเดทข้อมูลการลา: " . $e->getMessage());
        }
    }

    // Method สำหรับลบข้อมูลการลา
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // ตรวจสอบค่าของ id
        if (empty($this->id)) {
            throw new Exception("ID ไม่ถูกต้อง");
        }

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("เกิดข้อผิดพลาดในการลบข้อมูลการลา: " . $e->getMessage());
        }
    }
}
?>
