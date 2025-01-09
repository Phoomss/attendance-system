<?php
require_once 'conn.php';

class User
{
    private $conn;
    private $table_name = "users";
    public $id;
    public $title;
    public $firstname;
    public $surname;
    public $username;
    public $phone;
    public $password;

    public function __construct()
    {
        $database = new Conn();
        $db = $database->getConnection();
        $this->conn = $db;
    }

    public function create()
    {
        // Correct SQL query
        $query = "INSERT INTO " . $this->table_name . " (title, firstname, surname, username, phone, password) 
                  VALUES (:title, :firstname, :surname, :username, :phone, :password)";

        $stmt = $this->conn->prepare($query);

        // Hash the password
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':password', $hashedPassword);

        try {
            if ($stmt->execute()) {
                return [
                    "success" => true,
                    "message" => "การลงทะเบียนผู้ใช้สำเร็จ"
                ];
            }

            // Log error and return failure
            error_log("Register error: " . implode(" ", $stmt->errorInfo()));
            return [
                "success" => false,
                "message" => "ไม่สามารถลงทะเบียนผู้ใช้ได้"
            ];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage()
            ];
        }
    }

    public function getAllUser()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "success" => true,
                "data" => $result,
                "message" => "ดึงข้อมูลผู้ใช้ทั้งหมดสำเร็จ"
            ];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการดึงข้อมูลผู้ใช้ทั้งหมด: " . $e->getMessage()
            ];
        }
    }

    public function getUserInfo($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return [
                    "success" => true,
                    "data" => $result,
                    "message" => "ดึงข้อมูลผู้ใช้สำเร็จ"
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "ไม่พบข้อมูลผู้ใช้ที่ระบุ"
                ];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการดึงข้อมูลผู้ใช้: " . $e->getMessage()
            ];
        }
    }

    public function updateProfile($id)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      firstname = :firstname, 
                      surname = :surname, 
                      username = :username, 
                      phone = :phone";
    
        if (!empty($this->password)) {
            $query .= ", password = :password";
        }
    
        $query .= " WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        if (!empty($this->password)) {
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
        }       
    
        try {
            if ($stmt->execute()) {
                http_response_code(200);
                return [
                    "success" => true,
                    "message" => "อัปเดตโปรไฟล์สำเร็จ"
                ];
            }
    
            error_log("Update profile error: " . implode(" ", $stmt->errorInfo()));
            return [
                "success" => false,
                "message" => "ไม่สามารถอัปเดตโปรไฟล์ได้"
            ];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            http_response_code(500);
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการอัปเดตโปรไฟล์: " . $e->getMessage()
            ];
        }
    }    

    public function update($id)
    {
        // ตรวจสอบว่ามีผู้ใช้ที่ระบุหรือไม่
        $queryCheck = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id = :id";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtCheck->execute();
        $rowCount = $stmtCheck->fetchColumn();

        if ($rowCount == 0) {
            return [
                "success" => false,
                "message" => "ไม่พบข้อมูลผู้ใช้ที่ระบุ"
            ];
        }

        // ทำการอัปเดตข้อมูล
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      firstname = :firstname, 
                      surname = :surname, 
                      username = :username, 
                      phone = :phone 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return [
                    "success" => true,
                    "message" => "อัปเดตข้อมูลผู้ใช้สำเร็จ"
                ];
            }

            error_log("Update error: " . implode(" ", $stmt->errorInfo()));
            return [
                "success" => false,
                "message" => "ไม่สามารถอัปเดตข้อมูลผู้ใช้ได้"
            ];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $e->getMessage()
            ];
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return [
                    "success" => true,
                    "message" => "ลบข้อมูลผู้ใช้สำเร็จ"
                ];
            }

            error_log("Delete error: " . implode(" ", $stmt->errorInfo()));
            return [
                "success" => false,
                "message" => "ไม่สามารถลบข้อมูลผู้ใช้ได้"
            ];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage()
            ];
        }
    }
}
