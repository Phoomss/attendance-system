<?php
session_start();
require_once '../../server/user.php';

$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($employee_id > 0) {
    $user = new User();
    $response = $user->getUserInfo($employee_id);
    if ($response['success']) {
        $userInfo = $response['data'];
    } else {
        $userInfo = [];
        error_log($response['message']);
    }
} else {
    $errorMessage = "Invalid user ID.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลพนักงาน</title>
    <?php include_once '../layouts/config/libary.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include_once '../layouts/sidenav.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once '../layouts/navbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">แก้ไขข้อมูลพนักงาน</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <?php if (!empty($userInfo)): ?>
                                <form action="update_employee.php" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($userInfo['id']); ?>">

                                    <div class="form-group">
                                        <label class="input-group-text" for="title">คำนำหน้า</label>
                                        <select class="form-select" id="title" name="title" required>
                                            <option disabled>เลือกคำนำหน้า</option>
                                            <option value="นาย" <?= $userInfo['title'] === "นาย" ? "selected" : ""; ?>>นาย</option>
                                            <option value="นาง" <?= $userInfo['title'] === "นาง" ? "selected" : ""; ?>>นาง</option>
                                            <option value="นางสาว" <?= $userInfo['title'] === "นางสาว" ? "selected" : ""; ?>>นางสาว</option>
                                            <option value="Mr." <?= $userInfo['title'] === "Mr." ? "selected" : ""; ?>>Mr.</option>
                                            <option value="Ms." <?= $userInfo['title'] === "Ms." ? "selected" : ""; ?>>Ms.</option>
                                            <option value="Mrs." <?= $userInfo['title'] === "Mrs." ? "selected" : ""; ?>>Mrs.</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="firstname">ชื่อ</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($userInfo['firstname']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="surname">นามสกุล</label>
                                        <input type="text" class="form-control" id="surname" name="surname" value="<?= htmlspecialchars($userInfo['surname']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">เบอร์โทร</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($userInfo['phone']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">อีเมล</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userInfo['email']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    ไม่พบข้อมูลพนักงาน
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once '../layouts/footer.php'; ?>
        </div>
    </div>

    <?php include_once '../layouts/config/script.php'; ?>
</body>

</html>