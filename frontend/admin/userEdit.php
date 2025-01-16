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
    <?php require_once '../../script/script.js' ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include_once '../layouts/sidenav.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once '../layouts/navbar.php'; ?>

                <div class="container mt-5">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="m-0">แก้ไขข้อมูลพนักงาน</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($userInfo)): ?>
                                <form id="userForm" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($userInfo['id']); ?>">

                                    <fieldset class="border p-3 mb-3">
                                        <legend class="w-auto px-2">ข้อมูลส่วนตัว</legend>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="title">คำนำหน้า</label>
                                                    <select class="form-control" id="title" name="title" required>
                                                        <option disabled>เลือกคำนำหน้า</option>
                                                        <option value="นาย" <?= $userInfo['title'] === "นาย" ? "selected" : ""; ?>>นาย</option>
                                                        <option value="นาง" <?= $userInfo['title'] === "นาง" ? "selected" : ""; ?>>นาง</option>
                                                        <option value="นางสาว" <?= $userInfo['title'] === "นางสาว" ? "selected" : ""; ?>>นางสาว</option>
                                                        <option value="Mr." <?= $userInfo['title'] === "Mr." ? "selected" : ""; ?>>Mr.</option>
                                                        <option value="Ms." <?= $userInfo['title'] === "Ms." ? "selected" : ""; ?>>Ms.</option>
                                                        <option value="Mrs." <?= $userInfo['title'] === "Mrs." ? "selected" : ""; ?>>Mrs.</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="firstname">ชื่อ</label>
                                                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($userInfo['firstname']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="surname">นามสกุล</label>
                                                    <input type="text" class="form-control" id="surname" name="surname" value="<?= htmlspecialchars($userInfo['surname']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="role">ตำแหน่ง</label>
                                                    <input type="text" class="form-control bg-light" id="role" name="role" value="<?= htmlspecialchars($userInfo['role']); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="border p-3 mb-3">
                                        <legend class="w-auto px-2">ข้อมูลติดต่อ</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">เบอร์โทร</label>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($userInfo['phone']); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">อีเมล</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userInfo['email']); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="border p-3 mb-3">
                                        <legend class="w-auto px-2">ข้อมูลการใช้งาน</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="username">ชื่อผู้ใช้งาน</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userInfo['username']); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">รหัสผ่าน (ใส่เฉพาะถ้าต้องการเปลี่ยน)</label>
                                                    <input type="password" class="form-control" id="password" name="password" required>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <button type="submit" class="btn btn-success btn-block">บันทึก</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger">ไม่พบข้อมูลพนักงาน</div>
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

<script>
  jQuery(document).ready(function($) {
    $("#userForm").submit(function(e) {
        e.preventDefault();

        const formData = {
            action: "update",
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

</html>