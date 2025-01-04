<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ระบบเข้าออกงาน</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- ข้อมูลส่วนตัว -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="profile.php">ข้อมูลส่วนตัว</a>
        </li>
        <!-- การบันทึกเวลา -->
        <li class="nav-item">
          <a class="nav-link" href="time_tracking.php">บันทึกเวลาเข้า-ออก</a>
        </li>
        <!-- รายงาน -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            รายงาน
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="report_daily.php">รายงานประจำวัน</a></li>
            <li><a class="dropdown-item" href="report_monthly.php">รายงานประจำเดือน</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="report_summary.php">รายงานสรุป</a></li>
          </ul>
        </li>
        <!-- การตั้งค่า -->
        <li class="nav-item">
          <a class="nav-link" href="settings.php">การตั้งค่า</a>
        </li>
      </ul>
      <!-- ปุ่ม Logout -->
      <form class="d-flex">
        <a class="btn btn-danger ms-2" href="../../server/logout.php">ออกจากระบบ</a>
      </form>
    </div>
  </div>
</nav>
