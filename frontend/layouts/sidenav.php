<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-text mx-3">ระบบเข้าออกงาน</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $current_page == 'admin_dashboard.php' ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/admin_dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Attendance Report -->
    <li class="nav-item <?= $current_page == 'attendanceReport.php' ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/attendanceReport.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>รายงานเข้าออกงาน</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - User List -->
    <li class="nav-item <?= ($current_page == 'login.html' || $current_page == 'register.html') ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsUser"
           aria-expanded="true" aria-controls="collapsUser">
            <i class="fas fa-fw fa-folder"></i>
            <span>รายการผู้ใช้งาน</span>
        </a>
        <div id="collapsUser" class="collapse <?= ($current_page == 'login.html' || $current_page == 'register.html') ? 'show' : '' ?>"
             aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">รายการพนักงาน:</h6>
                <a class="collapse-item <?= $current_page == 'login.html' ? 'active' : '' ?>" href="login.html">จัดการข้อมูลพนักงาน</a>
                <a class="collapse-item <?= $current_page == 'register.html' ? 'active' : '' ?>" href="register.html">จัดการข้อมูลส่วนตัว</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the current page from the URL
        const currentPage = window.location.pathname.split('/').pop();
        
        // Get all nav items in the sidebar
        const navLinks = document.querySelectorAll('.nav-item');
        
        // Loop through links and set 'active' class for the matching one
        navLinks.forEach(link => {
            const anchor = link.querySelector('a');
            if (anchor && anchor.getAttribute('href').includes(currentPage)) {
                link.classList.add('active');
                // Expand collapsed parent menu if needed
                const parentCollapse = link.closest('.collapse');
                if (parentCollapse) {
                    parentCollapse.classList.add('show');
                }
            }
        });
    });
</script>
