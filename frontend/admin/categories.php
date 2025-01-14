<?php
session_start();

// รวมไฟล์ที่จำเป็น
require_once '../../backend/auth.php';
require_once '../../backend/categories.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['userInfo'])) {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบการเข้าถึงของ admin
checkUserRole('admin');

$category = new Categories();
$categories = $category->getCategories();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nitiya Shop - ประเภทสินค้า</title>
    <?php include_once '../layouts/config/libary.php' ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include_once '../layouts/sidenav.php' ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- เนื้อหาหลัก -->
            <div id="content">
                <?php include_once '../layouts/navbar.php' ?>

                <!-- เริ่มต้นเนื้อหาหน้าเพจ -->
                <div class="container-fluid">
                    <!-- หัวข้อหน้า -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">ประเภทสินค้า</h1>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> เพิ่มประเภทสินค้า
                        </button>
                    </div>

                    <!-- ตัวอย่าง DataTable -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">รายการประเภทสินค้า</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>ชื่อประเภทสินค้า</th>
                                            <th>การกระทำ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $l = 1;
                                        foreach ($categories as $category) { ?>
                                            <tr>
                                                <td><?php echo $l++; ?></td>
                                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                                <td class="text-center">
                                                    <button data-id="<?php echo $category['id'] ?>" class="btn btn-sm btn-warning editBtn" title="แก้ไข" data-bs-toggle="modal" data-bs-target="#EditModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button data-id="<?php echo $category['id'] ?>" class="btn btn-sm btn-danger deleteBtn" title="ลบ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- สิ้นสุดเนื้อหาหลัก -->

            <!-- Updated 'เพิ่มประเภทสินค้า Modal' -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มประเภทสินค้า</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addCategoryForm">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="addName" class="col-form-label">ชื่อประเภทสินค้า:</label>
                                    <input type="text" class="form-control" id="addName" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Updated 'แก้ไขประเภทสินค้า Modal' -->
            <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="EditModalLabel">แก้ไขประเภทสินค้า</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editCategoryForm">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editName" class="col-form-label">ชื่อประเภทสินค้า:</label>
                                    <input type="hidden" id="editId" name="id">
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php include_once '../layouts/footer.php' ?>
        </div>
    </div>

    <!-- Modal ออกจากระบบ -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ต้องการออกจากระบบ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">กด "ออกจากระบบ" ด้านล่างหากคุณต้องการออกจากระบบในตอนนี้</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                    <a class="btn btn-primary" href="login.html">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../layouts/config/script.php' ?>

    <script>
        $(document).ready(function() {
            // Add Category
            $('#addCategoryForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "../../apis/categories_action.php",
                    data: {
                        action: 'create',
                        name: $('#addName').val()
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire(
                                'สำเร็จ',
                                res.message,
                                'success'
                            ).then(() => {
                                location.reload(); // Refresh page after adding data
                            });
                        } else {
                            Swal.fire(
                                'ผิดพลาด',
                                res.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'ผิดพลาด',
                            'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                            'error'
                        );
                    }
                });
            });

            // Edit Category - Fill the modal with existing data
            $('.editBtn').click(function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "../../apis/categories_action.php",
                    data: {
                        action: 'get',
                        id: id
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#editId').val(res.data.id);
                            $('#editName').val(res.data.name);
                        } else {
                            Swal.fire(
                                'ผิดพลาด',
                                res.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'ผิดพลาด',
                            'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                            'error'
                        );
                    }
                });
            });

            // Update Category
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "../../apis/categories_action.php",
                    data: {
                        action: 'update',
                        id: $('#editId').val(),
                        name: $('#editName').val()
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire(
                                'สำเร็จ',
                                res.message,
                                'success'
                            ).then(() => {
                                location.reload(); // Refresh page after updating data
                            });
                        } else {
                            Swal.fire(
                                'ผิดพลาด',
                                res.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'ผิดพลาด',
                            'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                            'error'
                        );
                    }
                });
            });

            // Delete Category
            $('.deleteBtn').click(function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "ข้อมูลนี้จะไม่สามารถกู้คืนได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ลบเลย!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "../../apis/categories_action.php",
                            data: {
                                action: 'delete',
                                id: id
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status === 'success') {
                                    Swal.fire(
                                        'ลบแล้ว!',
                                        res.message,
                                        'success'
                                    ).then(() => {
                                        location.reload(); // Refresh page after deleting data
                                    });
                                } else {
                                    Swal.fire(
                                        'ผิดพลาด',
                                        res.message,
                                        'error'
                                    );
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'ผิดพลาด',
                                    'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>