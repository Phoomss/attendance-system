<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">ระบบเข้าออกงาน</a>
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
          <a class="nav-link" href="reportAttendance.php">รายงานการทำงาน</a>
        </li>
      </ul>
      <!-- ปุ่ม Logout -->
      <form class="d-flex">
        <a class="btn btn-danger ms-2" href="../../server/logout.php">ออกจากระบบ</a>
      </form>
    </div>
  </div>
</nav>
