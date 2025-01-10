<!-- ปุ่มบันทึกเวลาเข้าออก -->
<div class="col-md-6 mb-2">
    <a role="button" class="btn btn-outline-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#attendanceModel">บันทึกเวลาเข้างาน</a>
</div>
<!-- Modal for Attendance -->
<div class="modal fade" id="attendanceModel" tabindex="-1" aria-labelledby="attendanceModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModelLabel">บันทึกเวลาเข้าออก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm">
                    <!-- ชื่อ-นามสกุล -->
                    <div class="mb-3">
                        <label for="employee_name" class="form-label">ชื่อ-นามสกุล</label>
                        <input type="hidden" id="employee_id" name="employee_id" value="<?php echo htmlentities($userData['id']); ?>">
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            value="<?php echo htmlentities($userData['title'] . ' ' . $userData['firstname'] . ' ' . $userData['surname']); ?>"
                            readonly>
                    </div>
                    <!-- ช่องเลือกวันที่ -->
                    <div class="mb-3">
                        <label for="attendance_date" class="form-label">วันที่</label>
                        <input type="date" class="form-control" id="attendance_date" name="attendance_date" required>
                    </div>
                    <!-- เวลาเข้า -->
                    <div class="mb-3">
                        <label for="attendance_time" class="form-label">เวลาเข้า</label>
                        <input type="time" class="form-control" id="attendance_time" name="attendance_time" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="saveAttendanceBtn">บันทึก</button>
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
    function checkTimeToShowDeparture() {
        const currentTime = new Date();

        // ตั้งค่าช่องเวลาเข้าเป็นเวลาไทย
        document.getElementById('attendance_time').value = getThaiTimeString(currentTime);
    }

    // เมื่อ modal ถูกเปิด
    document.getElementById('attendanceModel').addEventListener('shown.bs.modal', function() {
        checkTimeToShowDeparture();

        // ตรวจสอบว่าได้ลงเวลาแล้วหรือยัง
        $.ajax({
            type: "POST",
            url: "../../api/attendanceApi.php",
            data: {
                action: 'checkAttendance',
                employee_id: $('#employee_id').val(),
            },
            dataType: "json",
            success: function(response) {
                // ถ้ามีการลงเวลาแล้วในวันที่ปัจจุบัน
                if (response.exists) {
                    // ปิดการใช้งานฟอร์ม
                    $('#saveAttendanceBtn').prop('disabled', true); // ปิดการใช้งานปุ่ม
                    $('#saveAttendanceBtn').text('บันทึกเวลาเข้างานแล้ว'); // เปลี่ยนข้อความปุ่ม
                    $('#attendance_date').prop('disabled', true); // ปิดช่องกรอกวันที่
                    $('#attendance_time').prop('disabled', true); // ปิดช่องกรอกเวลา
                    // $('#attendanceModel').modal('hide'); // ปิด modal ถ้ามีการบันทึกเวลาแล้ว
                } else {
                    $('#saveAttendanceBtn').prop('disabled', false); // เปิดใช้งานปุ่ม
                    $('#saveAttendanceBtn').text('บันทึก'); // เปลี่ยนข้อความปุ่มกลับ
                    $('#attendance_date').prop('disabled', false); // เปิดช่องกรอกวันที่
                    $('#attendance_time').prop('disabled', false); // เปิดช่องกรอกเวลา
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการตรวจสอบข้อมูล',
                    confirmButtonText: 'ตกลง',
                });
            }
        });
    });

    jQuery(document).ready(function($) {
        $('#saveAttendanceBtn').on('click', function(e) {
            e.preventDefault();

            // Validate form
            if (!$('#attendance_date').val() || !$('#attendance_time').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    confirmButtonText: 'ตกลง',
                });
                return;
            }

            var formData = {
                action: 'create',
                employee_id: $('#employee_id').val(),
                attendance_date: $('#attendance_date').val(),
                attendance_time: $('#attendance_time').val(),
                // departure_time: $('#departure_time').val(),
            };

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
                            $('#attendanceModel').modal('hide');
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