<?php
session_start();
require_once '../../server/LineLogin.php';
require_once '../../server/conn.php';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ieT9m+Q4j82j8=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <?php require_once '../../script/script.js' ?>
</head>

<body>
    <?php require_once 'navbar.php'; ?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <!-- User Profile Section -->
            <div class="col-lg-4 col-md-6 mb-4">
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
                        <!-- แสดงภาพ -->
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
                    </div>

                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-6 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">ข้อมูลส่วนตัว</h5>
                        <form id="profileForm">
                            <div class="mb-3">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']); ?>">
                                <label for="title" class="form-label">คำนำหน้า</label>
                                <select class="form-select" id="title" name="title" required>
                                    <option value="นาย" <?php echo ($userData['title'] === 'นาย') ? 'selected' : ''; ?>>นาย</option>
                                    <option value="นาง" <?php echo ($userData['title'] === 'นาง') ? 'selected' : ''; ?>>นาง</option>
                                    <option value="นางสาว" <?php echo ($userData['title'] === 'นางสาว') ? 'selected' : ''; ?>>นางสาว</option>
                                    <option value="Mr." <?php echo ($userData['title'] === 'Mr.') ? 'selected' : ''; ?>>Mr.</option>
                                    <option value="Ms." <?php echo ($userData['title'] === 'Ms.') ? 'selected' : ''; ?>>Ms.</option>
                                    <option value="Mrs." <?php echo ($userData['title'] === 'Mrs.') ? 'selected' : ''; ?>>Mrs.</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="firstname" class="form-label">ชื่อจริง</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="ชื่อจริง" value="<?php echo htmlspecialchars($userData['firstname']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="surname" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="surname" name="surname" placeholder="นามสกุล" value="<?php echo htmlspecialchars($userData['surname']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้งาน" value="<?php echo htmlspecialchars($userData['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="อีเมล" value="<?php echo htmlspecialchars($userData['email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">เบอร์โทร</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="เบอร์โทร" value="<?php echo htmlspecialchars($userData['phone']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="กรุณากรอกรหัสผ่าน (ถ้ามี)">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">บันทึกข้อมูล</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        jQuery(document).ready(function($) {
            $('#profileForm').submit(function(e) {
                e.preventDefault();

                var formData = {
                    action: 'update',
                    id: $("input[name='id']").val(),
                    title: $("#title").val(),
                    firstname: $("#firstname").val(),
                    surname: $("#surname").val(),
                    username: $("#username").val(),
                    phone: $("#phone").val(),
                    email: $("#email").val(),
                    password: $("#password").val() || null,
                };

                $.ajax({
                    type: "POST",
                    url: "../../api/userApi.php",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'สำเร็จ!',
                                text: response.message,
                                icon: 'success',
                            }).then(() => {
                                window.location.reload(); // Reload page after success
                            });
                        } else {
                            Swal.fire({
                                title: 'ข้อผิดพลาด!',
                                text: response.message,
                                icon: 'error',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'ข้อผิดพลาด!',
                            text: 'ไม่สามารถส่งข้อมูลได้',
                            icon: 'error',
                        });
                    }
                });
            });

        });
    </script>
</body>

</html>