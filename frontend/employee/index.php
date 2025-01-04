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
// var_dump($profile);

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Login</title>
    <?php require_once '../../script/script.js' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .profile-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 0 auto;
        }
    </style>
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
                            <!-- ปุ่มบันทึกเวลาเข้าออก และ บันทึกการลา -->
                            <div class="col-md-6 mb-2">
                                <a role="button" class="btn btn-outline-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#attendanceModel">บันทึกเวลาเข้าออก</a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <a role="button" class="btn btn-outline-info w-100" type="button" data-bs-toggle="modal" data-bs-target="#leaveModel">บันทึกการลา</a>
                            </div>
                        </div>

                        <div class="my-4">
                            <?php require_once 'attendanceDetail.php'; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Modal for Attendance -->
    <div class="modal fade" id="attendanceModel" tabindex="-1" aria-labelledby="attendanceModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModelLabel">บันทึกเวลาเข้าออก</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="attendanceForm">
                        <!-- ช่องเลือกวันที่ -->
                        <div class="mb-3">
                            <label for="attendance_date" class="form-label">วันที่</label>
                            <input type="date" class="form-control" id="attendance_date" name="attendance_date" required>
                        </div>
                        <!-- เวลาเข้า -->
                        <div class="mb-3">
                            <label for="attendance_time" class="form-label">เวลาเข้า</label>
                            <input type="time" class="form-control" id="attendance_time" name="attendance_time" required>
                        </div>
                        <!-- เวลาออก -->
                        <div class="mb-3" id="departureTimeWrapper" style="display: none;">
                            <label for="departure_time" class="form-label">เวลาออก</label>
                            <input type="time" class="form-control" id="departure_time" name="departure_time">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="saveAttendanceBtn">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Leave -->
    <div class="modal fade" id="leaveModel" tabindex="-1" aria-labelledby="leaveModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveModelLabel">บันทึกการลา</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm">
                        <!-- ประเภทการลา -->
                        <div class="mb-3">
                            <label for="leave_type" class="form-label">ประเภทการลา</label>
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="ลาป่วย">ลาป่วย</option>
                                <option value="ลากิจ">ลากิจ</option>
                            </select>
                        </div>
                        <!-- วันที่ลา -->
                        <div class="mb-3">
                            <label for="leave_date" class="form-label">วันที่ลา</label>
                            <input type="date" class="form-control" id="leave_date" name="leave_date" required>
                        </div>
                        <!-- เหตุผลการลา -->
                        <div class="mb-3">
                            <label for="leave_reason" class="form-label">เหตุผลการลา</label>
                            <textarea class="form-control" id="leave_reason" name="leave_reason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="saveLeaveBtn">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ฟังก์ชันที่ตรวจสอบเวลา
        function checkTimeToShowDeparture() {
            var currentTime = new Date();
            var currentHour = currentTime.getHours(); // ชั่วโมงปัจจุบัน
            var currentMinute = currentTime.getMinutes(); // นาทีปัจจุบัน

            // ถ้าเวลาปัจจุบันถึง 17:00 แสดงช่องเวลาออก
            if (currentHour >= 20 && (currentHour > 20 || currentMinute >= 0)) {
                document.getElementById('departureTimeWrapper').style.display = 'block';
            }
        }

        // เมื่อ modal ถูกเปิด
        document.getElementById('attendanceModel').addEventListener('shown.bs.modal', function() {
            // เรียกฟังก์ชันตรวจสอบเวลาเมื่อ modal เปิด
            checkTimeToShowDeparture();
        });
    </script>
</body>

</html>