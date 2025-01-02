<?php
// แบบปกติ
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_db";

try {
    // สร้างการเชื่อมต่อ PDO แบบปกติ
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // ตั้งค่าให้ PDO จัดการกับข้อผิดพลาด
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ฟังก์ชันสำหรับเชื่อมต่อฐานข้อมูล
class Conn
{
    private $host = "localhost";
    private $db_name = "attendance_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
