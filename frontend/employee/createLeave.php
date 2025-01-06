<div class="col-md-6 mb-2">
    <a role="button" class="btn btn-outline-info w-100" type="button" data-bs-toggle="modal" data-bs-target="#leaveModel">บันทึกการลา</a>
</div>

<!-- Modal for Leave -->
<div class="modal fade" id="leaveModel" tabindex="-1" aria-labelledby="leaveModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveModelLabel">บันทึกการลา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leaveForm">
                    <!-- ประเภทการลา -->
                    <div class="mb-3">
                        <label for="leave_type" class="form-label">ประเภทการลา</label>
                        <select class="form-select" id="leave_type" name="leave_type" required>
                            <option value="ลาป่วย">ลาป่วย</option>
                            <option value="ลากิจ">ลากิจ</option>
                        </select>
                    </div>
                    <!-- วันที่ลา -->
                    <div class="mb-3">
                        <label for="leave_date" class="form-label">วันที่ลา</label>
                        <input type="date" class="form-control" id="leave_date" name="leave_date" required>
                    </div>
                    <!-- เหตุผลการลา -->
                    <div class="mb-3">
                        <label for="leave_reason" class="form-label">เหตุผลการลา</label>
                        <textarea class="form-control" id="leave_reason" name="leave_reason" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="saveLeaveBtn">บันทึก</button>
            </div>
        </div>
    </div>
</div>