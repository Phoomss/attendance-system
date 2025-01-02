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

    public function __construct(){
        $database = new Conn();
        $db = $database->getConnection();
        $this->conn = $db;
    }
    function login()
    {
        try {
            $query = "SELECT id, email, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);

            // Bind the email parameter
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            // Check if a user was found
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify password
                if (password_verify($this->password, $row['password'])) {
                    $this->id = $row['id'];
                    $this->email = $row['email'];
                    $this->role = $row['role']; // Include role if needed for further processing

                    return [
                        "success" => true,
                        "message" => "Login successful",
                        "data" => [
                            "id" => $this->id,
                            "email" => $this->email,
                            "role" => $this->role
                        ]
                    ];
                } else {
                    return [
                        "success" => false,
                        "message" => "Invalid password"
                    ];
                }
            }

            return [
                "success" => false,
                "message" => "User not found"
            ];
        } catch (PDOException $e) {
            error_log("Error during login for email {$this->email}: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "An error occurred during login"
            ];
        }
    }

    function register()
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (title, firstname, surname, email, password, role) VALUES (:title, :firstname, :surname, :email, :password, :role)";
            $stmt = $this->conn->prepare($query);
    
            // Hash the password
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
            // Assign default role
            $defaultRole = 'employee';
    
            // Bind parameters
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':surname', $this->surname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $defaultRole);
    
            // Execute query
            if ($stmt->execute()) {
                return [
                    "success" => true,
                    "message" => "User registered successfully",
                ];
            }
    
            // Log error and return failure
            error_log("Register error: " . implode(" ", $stmt->errorInfo()));
            return [
                "success" => false,
                "message" => "Failed to register user"
            ];
        } catch (PDOException $e) {
            // Catch and log exceptions
            error_log("Exception during registration: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "An error occurred during registration"
            ];
        }
    }    
}
