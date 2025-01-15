<?php
session_start();
require_once './server/LineLogin.php';

$line = new LineLogin();
$link = ''; // Initialize the $link variable in case there's an issue

if (!isset($_SESSION['profile'])) {
    // If session profile is not set, generate the Line login link
    $link = $line->getLink();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
    body {
        background: linear-gradient(135deg, #4e73df, #1cc88a);
        font-family: 'Arial', sans-serif;
    }

    .login-container {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
    }

    .login-container .form-control {
        border-radius: 10px;
        padding: 12px;
        font-size: 14px;
        border: 1px solid #ddd;
    }

    .btn-custom {
        border-radius: 10px;
        padding: 12px;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
    }

    .btn-line {
        background-color: #4cc764;
        color: white;
        border: none;
    }

    .btn-line:hover {
        background-color: #3cb055;
    }

    .btn-primary {
        background-color: #0984e3;
        border: none;
    }

    .btn-primary:hover {
        background-color: #065bb0;
    }

    .img-container img {
        max-width: 100%;
        height: auto;
    }

    .form-text {
        font-size: 14px;
        color: #555;
    }

    @media (max-width: 768px) {
        .login-container {
            padding: 20px;
        }

        .btn-custom {
            font-size: 14px;
        }
    }
</style>

<body>
    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-container">
                        <h2 class="text-center">เข้าสู่ระบบ</h2>
                        <form id="loginForm">
                            <!-- Username or Email input -->
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" id="identifier" name="identifier" class="form-control" placeholder="อีเมล หรือ ชื่อผู้ใช้" aria-label="Email or Username" required>
                            </div>

                            <!-- Password input -->
                            <div class="input-group mb-4">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="รหัสผ่าน" aria-label="Password" required>
                            </div>

                            <div class="mb-4 text-center">
                                <a href="register.php" class="form-text">ยังไม่มีบัญชี? สมัครก่อนเข้าใช้งาน</a>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-custom mb-3">เข้าสู่ระบบ</button>

                            <?php
                            if (empty($_SESSION['profile'])) {
                                echo '<a href="' . $link . '" class="btn btn-line btn-custom">';
                                echo '<i class="fa-brands fa-line me-2"></i> เข้าสู่ระบบด้วย Line';
                                echo '</a>';
                            }
                            ?>
                        </form>
                    </div>
                </div>

                <!-- Image container for larger screens -->
                <div class="col-md-6 d-none d-md-block img-container">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" alt="Login">
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").on('submit', function(e) {
                e.preventDefault();
                var identifier = $('#identifier').val();
                var password = $('#password').val();
                $.ajax({
                    type: 'POST',
                    url: './api/loginApi.php',
                    data: {
                        identifier: identifier,
                        password: password
                    },
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.status_code === 200) {
                                if (res.role === 'admin') {
                                    window.location.href = './frontend/admin/admin_dashboard.php';
                                } else if (res.role === 'employee') {
                                    window.location.href = './frontend/employee/index.php';
                                } else {
                                    window.location.reload();
                                }
                            } else {
                                alert(res.message || 'เข้าสู่ระบบไม่สำเร็จ');
                            }
                        } catch (e) {
                            alert('ข้อมูลที่ได้รับจากเซิร์ฟเวอร์ไม่ถูกต้อง');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาด');
                    }
                });
            });
        });
    </script>
</body>

</html>