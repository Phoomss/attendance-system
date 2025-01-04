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

    public  function create()
    {
        $query = "INSERT INTO" . $this->table_name . "SET employee_id=:employee_id, attendance_time=:attendance_time";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':employee_id', $this->employee_id);
        $stmt->bindParam(':attendance_time', $this->attendance_time);
        $stmt->bindParam(':departure_time', $this->departure_time);
        $stmt->execute();
        return $stmt;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET employee_id=:employee_id, attendance_time=:attendance_time, departure_time=:departure_time WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':employee_id', $this->employee_id);
        $stmt->bindParam(':attendance_time', $this->attendance_time);
        $stmt->bindParam(':departure_time', $this->departure_time);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }
}
