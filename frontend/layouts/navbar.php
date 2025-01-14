<?php
// แสดงค่าของตัวแปร $_SESSION['userInfo'] เพื่อการตรวจสอบ
$_SESSION['userInfo'];
// var_dump($_SESSION['userInfo']);
?>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- ปุ่ม Sidebar Toggle (แสดงเมื่อหน้าจอเล็ก) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- รายการเมนู - ข้อมูลผู้ใช้งาน -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-lg-inline text-gray-600 small">
                    <?php echo $_SESSION['userInfo']['username']; // แสดงชื่อผู้ใช้งาน ?>
                </span>
                <img class="img-profile rounded-circle"
                    src="../../src/img/undraw_profile.svg">
            </a>
            <!-- Dropdown - ข้อมูลผู้ใช้งาน -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    โปรไฟล์
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    ออกจากระบบ
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- จบส่วน Topbar -->

<!-- ปุ่ม Scroll to Top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Modal ออกจากระบบ -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">คุณต้องการออกจากระบบหรือไม่?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">เลือก "ออกจากระบบ" ด้านล่างเพื่อสิ้นสุดเซสชันปัจจุบันของคุณ</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                <a class="btn btn-primary" href="../../server/logout.php">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</div>
