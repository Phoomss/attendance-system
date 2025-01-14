    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Nitiya Shop</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="../admin/admin_dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            จัดการเนื้อหาคอนเทนต์
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>รายารคอนเทนต์</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">รายารคอนเทนต์:</h6>
                    <a class="collapse-item" href="buttons.html">จัดการข้อมูลนำเสนอรูปสินค้า</a>
                    <a class="collapse-item" href="../admin/brander.php">จัดการข้อมูลแบรน์เนอร์</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading" style="font-size: 13.6px;">
            จัดการระบบ
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsProduct"
                aria-expanded="true" aria-controls="collapsProduct">
                <i class="fas fa-fw fa-folder"></i>
                <span>รายการสินค้า</span>
            </a>
            <div id="collapsProduct" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">รายการสินค้า:</h6>
                    <a class="collapse-item" href="../admin/products.php">จัดการข้อมูลสินค้า</a>
                    <a class="collapse-item" href="../admin/categories.php">จัดการข้อมูลประเภเทสินค้า</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsUser"
                aria-expanded="true" aria-controls="collapsUser">
                <i class="fas fa-fw fa-folder"></i>
                <span>รายการผู้ใช้งาน</span>
            </a>
            <div id="collapsUser" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">รายการผู้ใช้งาน:</h6>
                    <a class="collapse-item" href="login.html">จัดการข้อมูลลูกค้า</a>
                    <a class="collapse-item" href="register.html">จัดการข้อมูลเจ้าของร้าน</a>
                    <a class="collapse-item" href="register.html">จัดการข้อมูลเมือง</a>
                    <a class="collapse-item" href="register.html">จัดการข้อมูลส่วนตัว</a>
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