<?php
session_start();
require_once '../../server/LineLogin.php';
require_once '../../server/conn.php';
require_once '../../server/attendance.php';
require_once '../../server/detailWork.php';

// Ensure session variables are set
if (!isset($_SESSION['profile']) && !isset($_SESSION['userInfo'])) {
    die("Session data is missing.");
}

$profile = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$userInfo = isset($_SESSION['userInfo']) ? $_SESSION['userInfo'] : null;

// Determine which email to use
if ($profile && isset($profile->email)) {
    $user_sesstion = $profile->email; // Use email from profile object
} else {
    $user_sesstion = $userInfo['email']; // Use email from userInfo array
}

// Check if user_sesstion contains an email
if (!$user_sesstion) {
    die("No email found in session data.");
}

// Fetch user data from database using email
$stmt = $conn->prepare("SELECT id, title, firstname, surname, name, username, phone, email, picture, role FROM users WHERE email = :email");
$stmt->bindParam(':email', $user_sesstion);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Login</title>
    <?php require_once '../../script/script.js' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require_once 'navbar.php'; ?>
    <?php require_once 'popup.php'; ?>

    <main class="container py-4">
        <div class="row justify-content-center">
            <!-- User Profile Section -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow profile-card text-center">

                    <?php
                    // ตรวจสอบว่ามีข้อมูลใน $userData หรือไม่
                    $defaultImage = "user2.png";
                    $profilePicture = isset($userData['picture']) && !empty($userData['picture']) ? htmlspecialchars($userData['picture']) : $defaultImage;

                    // ตรวจสอบชื่อผู้ใช้
                    $name = isset($userData['title'], $userData['firstname'], $userData['surname'])
                        ? htmlspecialchars($userData['title'] . $userData['firstname'] . ' ' . $userData['surname'])
                        : htmlspecialchars($userData['name']);

                    // ตรวจสอบอีเมล
                    $email = isset($userData['email']) ? htmlspecialchars($userData['email']) : "ไม่มีอีเมล";

                    // ตรวจสอบบทบาทผู้ใช้
                    $role = isset($userData['role'])
                        ? ($userData['role'] === 'employee' ? 'พนักงาน' : 'อื่นๆ')
                        : 'ไม่ระบุบทบาท';
                    ?>
                    <div class="card-body">
                        <img src="<?php echo $profilePicture; ?>" class="rounded-circle mb-3" alt="Profile Picture" style="width: 120px; height: 120px;">

                        <!-- แสดงชื่อ -->
                        <h5 class="card-title text-primary"><?php echo $name; ?></h5>

                        <!-- แสดงอีเมล -->
                        <p class="card-text text-muted">
                            <i class="bi bi-envelope"></i> <?php echo $email; ?>
                        </p>

                        <!-- แสดงบทบาท -->
                        <p class="card-text text-muted">
                            <i class="bi bi-people"></i> <?php echo $role; ?>
                        </p>
                        <a href="profile.php" class="btn btn-outline-primary w-100">แก้ไขข้อมูลส่วนตัว</a>
                    </div>
                </div>
            </div>

            <!-- Attendance Details Section -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        รายละเอียดการเข้างาน
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <?php require_once 'attendanceCreate.php' ?>
                            <?php require_once 'attendanceUpdate.php' ?>
                            <?php require_once 'createLeave.php' ?>
                        </div>

                        <div class="my-4">
                            <?php require_once 'attendanceDetail.php'; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</body>

</html>