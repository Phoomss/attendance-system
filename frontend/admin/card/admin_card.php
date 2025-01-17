<?php
$detailWork = new DetailWork();

$attendanceDaily = $detailWork->getDailyAttendanceCount();
$attendanceDepartures = $detailWork->getDailyDepartureCount();
$leaveData = $detailWork->getLeaveCountByDate();

?>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        การเข้าทำงาน (ต่อวัน)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= isset($attendanceDaily['total_attendances']) ? $attendanceDaily['total_attendances'] : '0' ?> คน</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        การออกจากงาน (ต่อวัน)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $attendanceDepartures !== false ? $attendanceDepartures : '0' ?> คน</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// แสดงข้อมูลการลาในแต่ละวันที่ดึงมา
if ($leaveData && count($leaveData) > 0) {
    foreach ($leaveData as $data) {
        // รับค่าจำนวนลาป่วยและลากิจจากฐานข้อมูล
        $sickLeaveCount = $data['sick_leave_count'];
        $personalLeaveCount = $data['personal_leave_count'];
        $leaveDate = $data['leave_date'];
        ?>
        <!-- แสดงข้อมูลลาป่วย -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">ลาป่วย (<?= $leaveDate ?>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sickLeaveCount > 0 ? $sickLeaveCount : '0' ?> คน</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-medkit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- แสดงข้อมูลลากิจ -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ลากิจ (<?= $leaveDate ?>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $personalLeaveCount > 0 ? $personalLeaveCount : '0' ?> คน</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    // Display a default card when no leave data is found
    ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">ลาป่วย (ต่อวัน)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">0 คน</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-medkit fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ลากิจ (ต่อวัน)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">0 คน</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
