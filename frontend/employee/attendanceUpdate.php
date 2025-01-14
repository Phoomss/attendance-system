<?php
$id = $userData['id'];

$attendance = new Attendance();

$attendanceUpdate = $attendance->readInfo($id);
// var_dump($attendanceUpdate);
?>

<!-- ปุ่มบันทึกเวลาเข้าออก -->
<div class="col-md-6 mb-2" id="model">
    <a role="button" class="btn btn-outline-danger w-100" type="button" data-bs-toggle="modal" data-bs-target="#departureModel" id="departureButton">บันทึกเวลาออกงาน</a>
</div>
<!-- Modal for Attendance -->
<div class="modal fade" id="departureModel" tabindex="-1" aria-labelledby="departureModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departureModelLabel">บันทึกเวลาเข้าออก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm">
                    <!-- ชื่อ-นามสกุล -->
                    <div class="mb-3">
                        <label for="employee_name" class="form-label">ชื่อ-นามสกุล</label>
                        <input type="hidden" id="id" name="id" value="<?php echo htmlentities($attendanceUpdate['id']); ?>">
                        <input type="hidden" id="employee_id" name="employee_id" value="<?php echo htmlentities($userData['id']); ?>">
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            value="<?php echo htmlentities($userData['title'] . ' ' . $userData['firstname'] . ' ' . $userData['surname']); ?>"
                            readonly>
                    </div>
                    <!-- ช่องเลือกวันที่ -->
                    <div class="mb-3">
                        <label for="attendance_date" class="form-label">วันที่</label>
                        <input type="date" class="form-control" id="attendance_date" name="attendance_date"
                            value="<?php echo htmlentities($attendanceUpdate['attendance_date']) ?>" disabled required>
                    </div>
                    <!-- เวลาเข้า -->
                    <div class="mb-3">
                        <label for="attendance_time" class="form-label">เวลาเข้า</label>
                        <input type="time" class="form-control" id="attendance_time" name="attendance_time"
                            value="<?php echo htmlentities(date('H:i', strtotime($attendanceUpdate['created_at']))); ?>" disabled required>
                    </div>

                    <div class="mb-3">
                        <label for="departure_time" class="form-label">เวลาออก</label>
                        <input type="time" class="form-control" id="departure_time" name="departure_time"
                           required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="saveDepart_time">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชันแปลงเวลาเป็นรูปแบบภาษาไทย
    function getThaiTimeString(date) {
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }; // รูปแบบ 24 ชั่วโมง
        return date.toLocaleTimeString('th-TH', options);
    }

    // ฟังก์ชันตรวจสอบเวลา
    function checkTimeToShowButton() {
        const currentTime = new Date();

        // ถ้าเวลามากกว่าหรือเท่ากับ 12:00 แสดงปุ่ม
        if (currentTime.getHours() >= 12) {
            document.getElementById('departureButton').style.display = 'block';
        } else {
            document.getElementById('departureButton').style.display = 'none';
        }
    }

    window.onload = checkTimeToShowButton;

    // update form
    jQuery(document).ready(function($) {
        $('#saveDepart_time').on('click', function(e) {
            e.preventDefault();

            var formData = {
                action: 'update',
                id:$('#id').val(),
                employee_id: $('#employee_id').val(),
                departure_time: $('#departure_time').val(),
            };
            console.log(formData)
            // ส่งข้อมูลไปยัง API
            $.ajax({
                type: "POST",
                url: "../../api/attendanceApi.php",
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: response.message,
                            confirmButtonText: 'ตกลง',
                        }).then(() => {
                            // Reset form and close modal
                            $('#attendanceForm')[0].reset();
                            $('#departureModel').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ข้อผิดพลาด',
                            text: response.message,
                            confirmButtonText: 'ตกลง',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการส่งข้อมูล',
                        confirmButtonText: 'ตกลง',
                    });
                },
            });
        });
    });
</script>