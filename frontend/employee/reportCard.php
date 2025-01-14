<?php

$employee_id ;

$detailWork = new DetailWork();

$attendanceCount = $detailWork->countAttendance($employee_id);
$sickLeaveCount = $detailWork->countSickLeave($employee_id);
$personalLeaveCount = $detailWork->countPersonalLeave($employee_id);

?>
<div class="row">
    <!-- Attendance Card -->
    <div class="col-12 mb-4">
        <div class="card card-custom">
            <div class="card-header card-header-custom bg-info">
                <h5 class="card-title">การเข้าทำงาน</h5>
            </div>
            <div class="card-body card-body-custom">
                <p class="card-text">คุณเข้าทำงานแล้ว <?= $attendanceCount ?> ครั้ง</p>
            </div>
        </div>
    </div>

    <!-- Sick Leave Card (ลาป่วย) -->
    <div class="col-12 mb-4">
        <div class="card card-custom">
            <div class="card-header card-header-custom bg-danger">
                <h5 class="card-title">ลาป่วย</h5>
            </div>
            <div class="card-body card-body-custom">
                <p class="card-text">คุณลาป่วยไปแล้ว <?= $sickLeaveCount ?> วัน</p> 
            </div>
        </div>
    </div>

    <!-- Personal Leave Card (ลากิจ) -->
    <div class="col-12 mb-4">
        <div class="card card-custom">
            <div class="card-header card-header-custom bg-warning">
                <h5 class="card-title">ลากิจ</h5>
            </div>
            <div class="card-body card-body-custom">
                <p class="card-text">คุณลากิจไปแล้ว <?= $personalLeaveCount ?> วัน</p>
            </div>
        </div>
    </div>
</div>
