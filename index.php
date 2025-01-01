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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <?php require_once './script/script.js'?>
</head>

<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Arial', sans-serif;
    }

    .divider:after,
    .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #ddd;
    }

    .login-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        text-align: center;
    }

    .login-container .form-control {
        border-radius: 8px;
        padding: 15px;
    }

    .btn-custom {
        border-radius: 8px;
        padding: 12px 30px;
        width: 100%;
        font-size: 16px;
    }

    .btn-line {
        background-color: #4cc764;
        color: white;
        font-size: 16px;
    }

    .btn-line:hover {
        background-color: #06c755;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .img-fluid {
        max-width: 90%;
        height: auto;
    }

    .img-container {
        max-width: 100%;
        text-align: center;
    }
</style>

<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6 img-container">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" class="img-fluid" alt="Login image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <div class="login-container">
                        <h2 class="mb-4">เข้าสู่ระบบ</h2>
                        <form>
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" id="form1Example13" class="form-control form-control-lg" required />
                                <label class="form-label" for="form1Example13">อีเมล</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="form1Example23" class="form-control form-control-lg" required />
                                <label class="form-label" for="form1Example23">รหัสผ่าน</label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-custom mb-3">เข้าสู่ระบบ</button>

                            <?php
                                // Check if profile is set in session, then display the Line login link
                                if (empty($_SESSION['profile'])) {
                                    echo '<a href="' . $link . '" class="btn btn-line btn-custom mb-3">';
                                    echo '<i class="fa-brands fa-line"></i> เข้าสู่ระบบด้วย Line';
                                    echo '</a>';
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
