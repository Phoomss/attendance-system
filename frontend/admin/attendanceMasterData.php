<?php
session_start();
require_once '../../server/user.php';
require_once '../../server/conn.php';
require_once '../../server/attendance.php';
require_once '../../server/detailWork.php';

$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($employee_id > 0) {
    $detailWork = new DetailWork();
    $info = $detailWork->readInfo($employee_id);

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">รายงานเข้าออกงาน</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">ข้อมูลการเข้าออกงานของ <?php echo htmlentities($userInfo['title']) ?><?php echo htmlentities($userInfo['firstname']) ?> <?php echo htmlentities($userInfo['surname']) ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>เวลาเข้า</th>
                                            <th>เวลาออก</th>
                                            <th>ประเภทการลา</th>
                                            <th>วันที่ลา</th>
                                            <th>เหตุผล</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($info) {
                                            $index = 1;

                                            // Display attendance data
                                            foreach ($info['attendance'] as $attendanceRow) {
                                                $attendance_time = isset($attendanceRow['created_at']) ? $attendanceRow['created_at'] : 'ไม่มีข้อมูลการเข้าทำงาน';
                                                $departure_time = isset($attendanceRow['departure_time']) ? $attendanceRow['departure_time'] : 'ไม่มีข้อมูลการออกงาน';
                                                echo "<tr>
                                                        <th scope='row'>{$index}</th>
                                                        <td>{$attendance_time}</td>
                                                        <td>{$departure_time}</td>
                                                        <td>-</td> <!-- No leave data for this row -->
                                                        <td>-</td> <!-- No leave date for this row -->
                                                        <td>-</td> <!-- No reason for this row -->
                                                      </tr>";
                                                $index++;
                                            }

                                            // Display leave data
                                            foreach ($info['leave'] as $leaveRow) {
                                                echo "<tr>
                                                        <th scope='row'>{$index}</th>
                                                        <td>-</td> <!-- No attendance data for this row -->
                                                        <td>-</td> <!-- No departure time for this row -->
                                                        <td>{$leaveRow['leave_type']}</td>
                                                        <td>{$leaveRow['leave_date']}</td>
                                                        <td>" . (empty($leaveRow['reason']) ? '-' : $leaveRow['reason']) . "</td> <!-- Use '-' if reason is empty -->
                                                      </tr>";
                                                $index++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>ไม่พบข้อมูล</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <?php include_once '../layouts/footer.php' ?>
    </div>
    </div>

    <?php include_once '../layouts/config/script.php' ?>
</body>

</html>