<?php
session_start();
require_once '../../server/detailWork.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบเข้าออกงาน</title>
    <?php include_once '../layouts/config/libary.php' ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include_once '../layouts/sidenav.php' ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include_once '../layouts/navbar.php' ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                    <?php include_once './card/admin_card.php'?>

              </div>

            
              <?php include_once './card/admin_bar_chart.php'?>
              
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include_once '../layouts/footer.php' ?>
        </div>
    </div>

 
    <?php include_once '../layouts/config/script.php' ?>
</body>

</html>