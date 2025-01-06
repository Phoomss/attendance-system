  <!-- ปุ่มบันทึกเวลาเข้าออก และ บันทึกการลา -->
  <div class="col-md-6 mb-2">
      <a role="button" class="btn btn-outline-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#attendanceModel">บันทึกเวลาเข้าออก</a>
  </div>

  <div class="modal fade" id="attendanceModel" tabindex="-1" aria-labelledby="attendanceModelLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="attendanceModelLabel">บันทึกเวลาเข้าออก</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form id="attendanceForm">
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
                      <!-- เวลาออก -->
                      <div class="mb-3" id="departureTimeWrapper" style="display: none;">
                          <label for="departure_time" class="form-label">เวลาออก</label>
                          <input type="time" class="form-control" id="departure_time" name="departure_time">
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