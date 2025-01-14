<?php
session_start();

// รวมไฟล์ที่จำเป็น
require_once '../../backend/auth.php';
require_once '../../backend/branders.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['userInfo'])) {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบการเข้าถึงของ admin
checkUserRole('admin');

$brander = new Branders();
$branders = $brander->getBranders();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nitiya Shop - สินค้า</title>
    <?php include_once '../layouts/config/libary.php' ?>
</head>

<style>
    .image-preview img {
        width: 100px;
        /* Set a fixed width for images */
        height: 100px;
        /* Set a fixed height for images */
        object-fit: cover;
        /* Maintain aspect ratio */
        border: 2px solid #007bff;
        /* Add a border for better visibility */
        border-radius: 5px;
        /* Rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Add a subtle shadow */
    }
</style>

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
                        <h1 class="h3 mb-0 text-gray-800">แบรน์เนอร์</h1>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModel">
                            <i class="fas fa-plus"></i> เพิ่มแบรน์เนอร์
                        </button>
                    </div>

                    <!-- ตัวอย่าง DataTable -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">รายการสินค้า</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>รูปภาพ</th>
                                            <th>หัวข้อ</th>
                                            <th>รายละเอียด</th>
                                            <th>การกระทำ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $l = 1; ?>
                                        <?php if (is_array($branders)): ?>
                                            <?php foreach ($branders as $brander): ?>
                                                <tr>
                                                    <td><?php echo $l++; ?></td>
                                                    <td>
                                                        <?php if (!empty($brander['images'])): ?>
                                                            <div id="carouselExampleIndicators_<?php echo $brander['id']; ?>" class="carousel slide" data-ride="carousel">
                                                                <ol class="carousel-indicators">
                                                                    <?php foreach ($brander['images'] as $index => $image): ?>
                                                                        <li data-target="#carouselExampleIndicators_<?php echo $brander['id']; ?>" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                                                                    <?php endforeach; ?>
                                                                </ol>
                                                                <div class="carousel-inner">
                                                                    <?php foreach ($brander['images'] as $index => $image): ?>
                                                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                                            <img src="../../brander_image/<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="brander Image">
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                                <a class="carousel-control-prev" href="#carouselExampleIndicators_<?php echo $brander['id']; ?>" role="button" data-slide="prev">
                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#carouselExampleIndicators_<?php echo $brander['id']; ?>" role="button" data-slide="next">
                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <span>No Image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($brander['header'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($brander['detail'] ?? ''); ?></td>
                                                    <td><?php echo number_format($brander['price'], 2); ?> บาท</td>
                                                    <td class="text-center">
                                                        <button
                                                            type="button"
                                                            class="btn btn-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#EditModel"
                                                            data-id="<?= $brander['id'] ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button
                                                            data-id="<?php echo $brander['id']; ?>"
                                                            class="btn btn-sm btn-danger deleteBtn"
                                                            title="ลบ">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Error: <?php echo htmlspecialchars($branders); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- สิ้นสุดเนื้อหาหลัก -->

            <!-- Updated 'เพิ่มแบรน์เนอร์ Modal' -->
            <div class="modal fade" id="createModel" tabindex="-1" aria-labelledby="createModelLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Adjusted size for better layout -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createModelLabel">เพิ่มแบรน์เนอร์</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="brander_name">หัวข้อ:</label>
                                    <input type="text" class="form-control" id="header" name="header" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="detail">รายละเอียด:</label>
                                    <textarea class="form-control" id="detail" name="detail" rows="4"></textarea> <!-- Set a specific height for better layout -->
                                </div>
                                <div class="form-group mb-3">
                                    <label for="images" class="form-label">อัปโหลดภาพ:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="images" name="images[]" multiple accept="image/*" required>
                                        <label class="custom-file-label" for="images">เลือกไฟล์</label>
                                    </div>
                                    <div class="image-preview mt-3" id="imagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
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

            <!-- Updated 'แก้ไขสินค้า Modal' -->
            <div class="modal fade" id="EditModel" tabindex="-1" aria-labelledby="EditModelLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="EditModelLabel">แก้ไขสินค้า</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="brander_name">หัวข้อ:</label>
                                    <input type="text" class="form-control" id="header" name="header" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="detail">รายละเอียด:</label>
                                    <textarea class="form-control" id="detail" name="detail" rows="4"></textarea> <!-- Set a specific height for better layout -->
                                </div>
                                <div class="form-group mb-3">
                                    <label for="images" class="form-label">อัปโหลดภาพ:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="images" name="images[]" multiple accept="image/*" required>
                                        <label class="custom-file-label" for="images">เลือกไฟล์</label>
                                    </div>
                                    <div class="image-preview mt-3" id="imagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
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

    <?php include_once '../layouts/config/script.php' ?>

    <script>
        $(document).ready(function() {
            $('#images').change(function() {
                const files = $(this)[0].files;
                const imagePreview = $("#imagePreview");
                imagePreview.empty(); // Clear previous previews

                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            // Create an image element with styling
                            const img = $("<img>")
                                .attr("src", event.target.result)
                                .css({
                                    width: "100px", // Set width for image
                                    height: "100px", // Set height for image
                                    objectFit: "cover", // Maintain aspect ratio
                                    margin: "5px", // Space between images
                                    border: "2px solid #007bff", // Optional: add border
                                    borderRadius: "5px", // Optional: rounded corners
                                    boxShadow: "0 2px 5px rgba(0, 0, 0, 0.1)" // Optional: shadow effect
                                });
                            imagePreview.append(img); // Append image to preview
                        };

                        reader.readAsDataURL(file); // Read file as data URL
                    }
                }
            });

            // Form submission handling for creating branders
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    type: "POST",
                    url: "../../apis/branders_action.php",
                    data: new FormData(this), // Send form data
                    contentType: false,
                    processData: false,
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
        });
    </script>

</body>

</html>