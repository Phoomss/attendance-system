<?php
    class Leave{
        private $conn;
        private $table_name = "leaves";
        public $id;
        public $employee_id;
        public $leave_type;
        public $leave_date;
        public $reason;

        public function __construct()
        {
            $database = new Conn();
            $db = $database->getConnection();
            $this->conn = $db;
        }

        public function create(){
            $query = "INSERT INTO " . $this->table_name . " SET employee_id=:employee_id, leave_type=:leave_type, leave_date=:leave_date, reason=:reason";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':employee_id', $this->employee_id);
            $stmt->bindParam(':leave_type', $this->leave_type);
            $stmt->bindParam(':leave_date', $this->leave_date);
            $stmt->bindParam(':reason', $this->reason);
            $stmt->execute();
            return $stmt;
        }

        public function read(){
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function readOne(){
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            return $stmt;
        }

        public function update(){
            $query = "UPDATE " . $this->table_name . " SET employee_id=:employee_id, leave_type=:leave_type, leave_date=:leave_date, reason=:reason WHERE id=:id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':employee_id', $this->employee_id);
            $stmt->bindParam(':leave_type', $this->leave_type);
            $stmt->bindParam(':leave_date', $this->leave_date);
            $stmt->bindParam(':reason', $this->reason);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return $stmt;
        }

        public function delete(){
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            return $stmt;
        }
    }
?>