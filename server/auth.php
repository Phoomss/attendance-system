<?php
require_once 'conn.php';
class Auth
{
    private $conn;
    private $table_name = "users";
    public $id;
    public $title;
    public $firstname;
    public $surname;
    public $email;
    public $password;
    public $role;

    public function __construct()
    {
        $database = new Conn();
        $db = $database->getConnection();
        $this->conn = $db;
    }

    public function login($identifier, $password)
    {
        try {
            $query = "SELECT id, username, email, role, password FROM " . $this->table_name . "
                      WHERE username = :identifier OR email = :identifier LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':identifier', $identifier);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    // ตรวจสอบรหัสผ่านสำเร็จ
                    return [
                        'success' => true,
                        'message' => 'เข้าสู่ระบบสำเร็จ',
                        'data' => [
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'email' => $user['email'],
                            'role' => $user['role']
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'รหัสผ่านไม่ถูกต้อง'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'ชื่อผู้ใช้หรืออีเมลไม่ถูกต้อง'
                ];
            }
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดระหว่างการเข้าสู่ระบบ'
            ];
        }
    }

    function register()
    {
        try {
            // Check if the username or email already exists
            $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username OR email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            // If a row is found, username or email already exists
            if ($stmt->rowCount() > 0) {
                return [
                    "success" => false,
                    "message" => "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้แล้ว"
                ];
            }

            // Proceed with registration if username and email are unique
            $query = "INSERT INTO " . $this->table_name . " (title, firstname, surname, username, email, password, role) 
                      VALUES (:title, :firstname, :surname, :username, :email, :password, :role)";
            $stmt = $this->conn->prepare($query);

            // Hash the password
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            // Assign default role
            $defaultRole = 'employee';

            // Bind parameters
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':surname', $this->surname);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $defaultRole);

            // Execute query
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
            // Catch and log exceptions
            error_log("Exception during registration: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "เกิดข้อผิดพลาดระหว่างการลงทะเบียน"
            ];
        }
    }

    function checkUserRole($role)
    {
        // ตรวจสอบว่า session ถูกเริ่มต้นแล้วและ userInfo มีข้อมูล
        if (!isset($_SESSION['userInfo']) || $_SESSION['userInfo']['role'] != $role) {
            header('Location: ./index.php');
            exit(); // ป้องกันการทำงานต่อหลังจาก redirect
        }

        // print_r($_SESSION['userInfo']); // ถ้าต้องการตรวจสอบข้อมูล
    }
}
