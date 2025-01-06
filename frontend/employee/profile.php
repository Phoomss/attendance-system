<?php
session_start();
require_once '../../server/LineLogin.php';
require_once '../../server/conn.php';

if (!isset($_SESSION['profile'])) {
    header("location: ../index.php");
    exit();
}

// ดึงข้อมูลจาก session
$profile = $_SESSION['profile'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ email จาก profile
$stmt = $conn->prepare("SELECT id, name, email, picture, role FROM users WHERE email = :email");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <?php require_once '../../script/script.js' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require_once 'navbar.php'; ?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <!-- User Profile Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow profile-card text-center">
                    <div class="card-body">
                        <img src="<?php echo htmlspecialchars($userData['picture']); ?>" class="rounded-circle mb-3" alt="Profile Picture" style="width: 120px; height: 120px;">
                        <h5 class="card-title text-primary"><?php echo htmlspecialchars($userData['name']); ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($userData['email']); ?>
                        </p>
                        <p class="card-text text-muted">
                            <i class="bi bi-people"></i> Role: <?php echo htmlspecialchars($userData['role'] === 'employee' ? 'พนักงาน' : ''); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-6 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">ข้อมูลส่วนตัว</h5>
                        <form id="profileForm">
                            <div class="row mb-3">
                                <!-- Title -->
                                <div class="col-12">
                                    <div class="input-group">
                                        <label class="input-group-text" for="title">คำนำหน้า</label>
                                        <select class="form-select" id="title" name="title" required>
                                            <option selected>เลือกคำนำหน้า</option>
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Mrs.">Mrs.</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- First Name -->
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text" id="first-name">ชื่อจริง</span>
                                        <input class="form-control" type="text" placeholder="ชื่อจริง" aria-label="First Name">
                                    </div>
                                </div>
                                <!-- Last Name -->
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text" id="last-name">นามสกุล</span>
                                        <input class="form-control" type="text" placeholder="นามสกุล" aria-label="Last Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- Username -->
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text" id="username">ชื่อผู้ใช้งาน</span>
                                        <input class="form-control" type="text" placeholder="ชื่อผู้ใช้งาน" aria-label="Username">
                                    </div>
                                </div>
                                <!-- phone -->
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text" id="username">เบอร์โทร</span>
                                        <input class="form-control" type="text" placeholder="เบอร์โทร" aria-label="phone">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- Password -->
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text" id="password">รหัสผ่าน</span>
                                        <input class="form-control" type="password" placeholder="รหัสผ่าน" aria-label="Password">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">บันทึกข้อมูล</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        
    </script>

</body>

</html>