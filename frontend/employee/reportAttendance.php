<?php
session_start();

require_once '../../server/conn.php';
require_once '../../server/attendance.php';
require_once '../../server/detailWork.php';

// Ensure session variables are set
if (!isset($_SESSION['profile']) && !isset($_SESSION['userInfo'])) {
    die("Session data is missing.");
}

$profile = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$userInfo = isset($_SESSION['userInfo']) ? $_SESSION['userInfo'] : null;

// Determine which email to use
if ($profile && isset($profile->email)) {
    $user_sesstion = $profile->email; // Use email from profile object
} else {
    $user_sesstion = $userInfo['email']; // Use email from userInfo array
}

// Check if user_sesstion contains an email
if (!$user_sesstion) {
    die("No email found in session data.");
}

// Fetch user data from database using email
$stmt = $conn->prepare("SELECT id, title, firstname, surname, name, username, phone, email, picture, role FROM users WHERE email = :email");
$stmt->bindParam(':email', $user_sesstion);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    die("User not found.");
}

// Continue with user data
$employee_id = $userData['id'];

// Create an instance of DetailWork class
$detailWork = new DetailWork();

// Get the employee information
$info = $detailWork->readInfo($employee_id);

// จำนวนรายการที่แสดงต่อหน้า
$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startLimit = ($currentPage - 1) * $itemsPerPage;

// Combine attendance and leave data into a single array for easier pagination
$combinedData = array_merge($info['attendance'], $info['leave']);
$totalItems = count($combinedData);
$totalPages = ceil($totalItems / $itemsPerPage);

// ดึงข้อมูลเฉพาะหน้า
$pageData = array_slice($combinedData, $startLimit, $itemsPerPage);

// Handle date search
$searchDate = isset($_GET['searchDate']) ? $_GET['searchDate'] : '';

// Get the leave type from the GET request
$leaveType = isset($_GET['leave_type']) ? $_GET['leave_type'] : '';

// Filter the combined data based on the search date and leave type
if ($searchDate || $leaveType) {
    $combinedData = array_filter($combinedData, function ($row) use ($searchDate, $leaveType) {
        $matchDate = true;
        $matchLeaveType = true;

        // Check date filter
        if ($searchDate) {
            if (isset($row['leave_date']) && $row['leave_date'] != $searchDate) {
                $matchDate = false;
            } elseif (isset($row['created_at']) && $row['created_at'] != $searchDate) {
                $matchDate = false;
            }
        }

        // Check leave type filter
        if ($leaveType && isset($row['leave_type']) && $row['leave_type'] != $leaveType) {
            $matchLeaveType = false;
        }

        return $matchDate && $matchLeaveType;
    });

    // Update pagination with filtered data
    $totalItems = count($combinedData);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $pageData = array_slice($combinedData, $startLimit, $itemsPerPage);
}

// Function to render rows
function renderRow($index, $row)
{
    if (isset($row['leave_type'])) {  // Leave data
        $attendanceTime = '-';
        $departureTime = '-';
        $leaveType = $row['leave_type'];
        $leaveDate = $row['leave_date'];
        $reason = empty($row['reason']) ? '-' : $row['reason'];
    } else {  // Attendance data
        $attendanceTime = isset($row['created_at']) ? $row['created_at'] : 'ไม่มีข้อมูลการเข้าทำงาน';
        $departureTime = isset($row['departure_time']) ? $row['departure_time'] : 'ไม่มีข้อมูลการออกงาน';
        $leaveType = '-';
        $leaveDate = '-';
        $reason = '-';
    }

    echo "<tr>
            <th scope='row'>{$index}</th>
            <td>{$row['title']} {$row['firstname']} {$row['surname']}</td>
            <td>{$attendanceTime}</td>
            <td>{$departureTime}</td>
            <td>{$leaveType}</td>
            <td>{$leaveDate}</td>
            <td>{$reason}</td>
          </tr>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance and Leave Records</title>
    <?php require_once '../../script/script.js' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require_once 'navbar.php'; ?>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <?php require_once 'reportCard.php' ?>
            </div>
            <div class="col-md-8">
                <!-- Search Form -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="searchDate" value="<?= $searchDate ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="leave_type">
                                <option value="">-- เลือกประเภทการลา --</option>
                                <option value="ลาป่วย" <?= $leaveType == 'ลาป่วย' ? 'selected' : '' ?>>ลาป่วย</option>
                                <option value="ลากิจ" <?= $leaveType == 'ลากิจ' ? 'selected' : '' ?>>ลากิจ</option>
                                <!-- Add more leave types as needed -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ชื่อ</th>
                                <th scope="col">เวลาเข้า</th>
                                <th scope="col">เวลาออก</th>
                                <th scope="col">ประเภทการลา</th>
                                <th scope="col">วันที่ลา</th>
                                <th scope="col">เหตุผล</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($pageData) {
                                $index = $startLimit + 1;
                                foreach ($pageData as $row) {
                                    renderRow($index, $row);
                                    $index++;
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูล</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>&searchDate=<?= $searchDate ?>&leave_type=<?= $leaveType ?>">Previous</a>
                            </li>
                            <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
                                <li class="page-item <?= $page == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page ?>&searchDate=<?= $searchDate ?>&leave_type=<?= $leaveType ?>"><?= $page ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>&searchDate=<?= $searchDate ?>&leave_type=<?= $leaveType ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
