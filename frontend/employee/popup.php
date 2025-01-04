<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- ส่วนหัวของ Modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="welcomeModalLabel">ยินดีต้อนรับสู่ระบบของเรา</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- เนื้อหา Modal -->
            <div class="modal-body">
                <p class="text-muted">ขอบคุณที่เข้าสู่ระบบ กรุณากรอกข้อมูลส่วนตัวของคุณให้ครบถ้วน เพื่อให้การใช้งานระบบสมบูรณ์ที่สุด ขอขอบคุณสำหรับความร่วมมือ</p>
            </div>
            <!-- ส่วนท้ายของ Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ปิด</button>
                <a href="profile.php" class="btn btn-primary">ไปที่ข้อมูลส่วนตัว</a>
            </div>
        </div>
    </div>
</div>
<!-- สคริปต์สำหรับเปิด Modal -->
<script>
    // เปิด Modal เมื่อโหลดหน้า
    var modal = new bootstrap.Modal(document.getElementById('welcomeModal'));
    modal.show();
</script>
