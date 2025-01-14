<?php
session_start();
require_once '../../server/LineLogin.php';
require_once '../../server/conn.php';
require_once '../../server/attendance.php';

if (!isset($_SESSION['profile'])) {
    header("location: ../index.php");
    exit();
}

// ดึงข้อมูลจาก session
$profile = $_SESSION['profile'];
// var_dump($profile);

// ดึงข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ email จาก profile
$stmt = $conn->prepare("SELECT id,title,firstname,surname, name,username,phone, email, picture, role FROM users WHERE email = :email");
$stmt->bindParam(':email', $profile->email);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    die("User not found in the database.");
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
                    <div class="card-body">
                        <img src="<?php echo htmlspecialchars($userData['picture']); ?>" class="rounded-circle mb-3" alt="Profile Picture">
                        <h5 class="card-title text-primary">ชื่อ: <?php echo htmlspecialchars($userData['name']); ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($userData['email']); ?>
                        </p>
                        <p class="card-text text-muted">
                            <i class="bi bi-people"></i> Role: <?php echo htmlspecialchars($userData['role'] === 'employee' ? 'พนักงาน' : ''); ?>
                        </p>
                        <a href="#" class="btn btn-outline-primary w-100">แก้ไขข้อมูลส่วนตัว</a>
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