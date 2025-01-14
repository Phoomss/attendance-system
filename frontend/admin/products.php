<?php
session_start();

// รวมไฟล์ที่จำเป็น
require_once '../../backend/auth.php';
require_once '../../backend/categories.php';
require_once '../../backend/products.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['userInfo'])) {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบการเข้าถึงของ admin
checkUserRole('admin');

$product = new Products();
$products = $product->getProducts();

$category = new Categories();
$categories = $category->getCategories();
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
                        <h1 class="h3 mb-0 text-gray-800">สินค้า</h1>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModel">
                            <i class="fas fa-plus"></i> เพิ่มสินค้า
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
                                            <th>รูปสินค้า</th>
                                            <th>ชื่อสินค้า</th>
                                            <th>รายการสินค้า</th>
                                            <th>ราคา</th>
                                            <th>ประเภทสินค้า</th>
                                            <th>การกระทำ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $l = 1; ?>
                                        <?php if (is_array($products)): ?>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td><?php echo $l++; ?></td>
                                                    <td>
                                                        <?php if (!empty($product['images'])): ?>
                                                            <div id="carouselExampleIndicators_<?php echo $product['id']; ?>" class="carousel slide" data-ride="carousel">
                                                                <ol class="carousel-indicators">
                                                                    <?php foreach ($product['images'] as $index => $image): ?>
                                                                        <li data-target="#carouselExampleIndicators_<?php echo $product['id']; ?>" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                                                                    <?php endforeach; ?>
                                                                </ol>
                                                                <div class="carousel-inner">
                                                                    <?php foreach ($product['images'] as $index => $image): ?>
                                                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                                            <img src="../../product_image/<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="Product Image">
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                                <a class="carousel-control-prev" href="#carouselExampleIndicators_<?php echo $product['id']; ?>" role="button" data-slide="prev">
                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#carouselExampleIndicators_<?php echo $product['id']; ?>" role="button" data-slide="next">
                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <span>No Image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product['product_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($product['product_description'] ?? ''); ?></td>
                                                    <td><?php echo number_format($product['price'], 2); ?> บาท</td>
                                                    <td>
                                                        <?php
                                                        if (is_array($product['category_name'])) {
                                                            echo htmlspecialchars(implode(', ', $product['category_name']));
                                                        } else {
                                                            echo htmlspecialchars($product['category_name'] ?? '');
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button
                                                            type="button"
                                                            class="btn btn-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#EditModel"
                                                            data-id="<?= $product['id'] ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button
                                                            data-id="<?php echo $product['id']; ?>"
                                                            class="btn btn-sm btn-danger deleteBtn"
                                                            title="ลบ">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Error: <?php echo htmlspecialchars($products); ?></td>
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

            <!-- Updated 'เพิ่มสินค้า Modal' -->
            <div class="modal fade" id="createModel" tabindex="-1" aria-labelledby="createModelLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Adjusted size for better layout -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createModelLabel">เพิ่มสินค้า</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group mb-3"> <!-- Added margin-bottom for spacing -->
                                    <label for="category_id">ประเภทสินค้า:</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">เลือกประเภทสินค้า</option>
                                        <?php if (is_array($categories) && count($categories) > 0): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>">
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product_name">ชื่อสินค้า:</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product_description">รายละเอียดสินค้า:</label>
                                    <textarea class="form-control" id="product_description" name="product_description" rows="4"></textarea> <!-- Set a specific height for better layout -->
                                </div>
                                <div class="form-group mb-3">
                                    <label for="price">ราคา:</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
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
                        <form id="editProductForm" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="category_id">ประเภทสินค้า:</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">เลือกประเภทสินค้า</option>
                                        <?php if (is_array($categories) && count($categories) > 0): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product_name">ชื่อสินค้า:</label>
                                    <input type="hidden" name="editId" id="editId">
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product_description">รายละเอียดสินค้า:</label>
                                    <textarea class="form-control" id="product_description" name="product_description" rows="4"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="price">ราคา:</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
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
        // preview image
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

            $("#uploadForm").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                // Append the action to the FormData object
                formData.append('action', 'create');

                $.ajax({
                    url: '../../apis/products_action.php',
                    type: 'POST',
                    data: formData,
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

            // Edit product - Fil the model with existing data
            $('#EditModel').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // ปุ่มที่เปิดโมดัล
                var productId = button.data('id'); // รหัสสินค้าที่ต้องการแก้ไข
                $.ajax({
                    type: "POST",
                    url: "../../apis/products_action.php",
                    data: {
                        action: 'get',
                        id: productId
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            // เติมข้อมูลในฟอร์ม
                            $('#category_id').val(res.data.category_id); // เติมประเภทสินค้า
                            $('#product_name').val(res.data.product_name); // เติมชื่อสินค้า
                            $('#product_description').val(res.data.product_description); // เติมรายละเอียดสินค้า
                            $('#price').val(res.data.price); // เติมราคา

                            // ลบภาพเก่าออกจาก preview (ถ้ามี) และแสดงภาพใหม่ (ถ้ามี)
                            $('#imagePreview').empty();
                            if (res.data.images.length > 0) {
                                res.data.images.forEach(function(image) {
                                    $('#imagePreview').append(`<img src="../../product_image/${image}" alt="Image" style="width: 100px; height: auto; margin-right: 10px;">`);
                                });
                            }

                            // แสดง modal
                            $('#EditModel').modal('show');
                        } else {
                            alert('ไม่สามารถดึงข้อมูลได้');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    }
                });
            });

            $('#editProductForm').on('submit', function(e) {
                e.preventDefault();

                // สร้าง FormData เพื่อส่งข้อมูลทั้งหมดในฟอร์ม
                var formData = new FormData(this);
                formData.append('action', 'update');

                $.ajax({
                    type: "POST",
                    url: "../../apis/products_action.php",
                    data: formData,
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

            // Delete product functionality
            $(document).on('click', '.deleteBtn', function() {
                const productId = $(this).data('id');
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
                            url: "../../apis/products_action.php",
                            data: {
                                action: 'delete',
                                id: productId
                            },
                            success: function(response) {
                                // console.log(response); // เพิ่มบรรทัดนี้เพื่อตรวจสอบค่าที่ได้
                                var res = JSON.parse(response); // ตรวจสอบว่าค่าที่ได้คือ JSON ที่ถูกต้อง
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
                })
            })
        })
    </script>
</body>

</html>