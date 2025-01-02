<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
    body {
        background: linear-gradient(135deg, #4e73df, #1cc88a);
        font-family: 'Arial', sans-serif;
    }

    .login-container {
        background: #fff;
        border-radius: 15px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .login-container h3 {
        font-weight: 700;
        margin-bottom: 30px;
        color: #2d3436;
    }

    .form-control {
        border-radius: 10px;
        font-size: 14px;
    }

    .btn-primary {
        background-color: #6c5ce7;
        border: none;
        padding: 12px;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background-color: #5a4acb;
    }

    .img-container img {
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 768px) {
        .login-container {
            padding: 20px;
        }
    }
</style>

<body>
    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Illustration -->
                <div class="col-md-6 d-none d-md-block img-container">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" alt="Illustration">
                </div>
                <!-- Registration Form -->
                <div class="col-md-6 col-lg-5">
                    <div class="login-container">
                        <form method="POST" id="registerForm">
                            <h3 class="text-center">สมัครสมาชิก</h3>
                            <div class="mb-3">
                                <div class="input-group">
                                    <label class="input-group-text" for="inputGroupSelect01">คำนำหน้า</label>
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
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input id="firstname" type="text" name="firstname" class="form-control" placeholder="ชื่อ" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input id="surname" type="text" name="surname" class="form-control" placeholder="นามสกุล" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input id="email" type="email" name="email" class="form-control" placeholder="อีเมล" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input id="password" type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#registerForm').submit(function(e) {
                e.preventDefault();

                var formData = {
                    'title': $('#title').val(),
                    'firstname': $('#firstname').val(),
                    'surname': $('#surname').val(),
                    'email': $('#email').val(),
                    'password': $('#password').val()
                };

                console.log(formData); // ตรวจสอบค่าที่จะส่งไป

                $.ajax({
                    type: 'POST',
                    url: './api/registerApi.php',
                    data: formData,
                    dataType: 'json',
                    encode: true
                }).done(function(data) {
                    console.log( "api log:",data); // ตรวจสอบค่าที่ได้รับจาก API
                    if (data.success) {
                        alert(data.message);
                        window.location.href = 'index.php'; // Redirect
                    } else {
                        alert(data.message);
                    }
                }).fail(function(xhr, status, error) {
                    alert("เกิดข้อผิดพลาดในการลงทะเบียน โปรดลองใหม่อีกครั้ง");
                });
            });
        });
    </script>
</body>

</html>