<?php
session_start();
require_once '../../server/detailWork.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบเข้าออกงาน</title>
    <?php include_once '../layouts/config/libary.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body id="page-top">
    <div id="wrapper" class="d-flex">
        <?php include_once '../layouts/sidenav.php' ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column w-100">
            <!-- Main Content -->  <?php include_once '../layouts/navbar.php' ?>
            <div id="content" class="container-fluid">

                <!-- Navigation Bar -->
              

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                </div>

                <!-- Content Row: Dashboard Cards & Charts -->
                <div class="row">
                    <!-- Dashboard Cards (Admin) -->
                    <?php include_once './card/admin_card.php' ?>
                </div>

                <!-- Bar Chart Section: Stacked Layout for Two Charts -->
                <div class="row">
                    <!-- First Chart: Bar Chart -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">จํานวนการเข้างานทั้งหมดของพนักงานในแต่ละเดือน</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="attendanceChart" width="100%" height="50"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Second Chart: Attendance Month -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">จํานวนการเข้างานรายเดือนตามพนักงาน</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="attendanceByMonth" width="100%" height="50"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">จํานวนการเข้างานรายเดือนตามพนักงาน</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="leaveChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once '../layouts/footer.php' ?>
        </div>
    </div>

    <!-- Scripts -->
    <?php include_once '../layouts/config/script.php' ?>

    <script>
        // ดึงข้อมูล JSON จาก PHP สำหรับการแสดงกราฟ Attendance Monthly
        fetch('../../api/attendanceMonthly.php')
            .then(response => response.json())
            .then(data => {
                const labels = Object.keys(data); // ใช้เดือนเป็น label
                const attendanceCounts = Object.values(data); // ใช้จำนวนการเข้าทำงานเป็นข้อมูล

                // สร้างกราฟ
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // ประเภทกราฟ
                    data: {
                        labels: labels, // เดือน
                        datasets: [{
                            label: 'จํานวนการเข้างานทั้งหมดของพนักงานในแต่ละเดือน', // ชื่อชุดข้อมูล
                            data: attendanceCounts, // จำนวนการเข้าทำงาน
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // สีพื้นหลัง
                            borderColor: 'rgba(54, 162, 235, 1)', // สีเส้นขอบ
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Monthly Attendance Count'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Attendance Count'
                                },
                                beginAtZero: true // เริ่มต้นที่ 0
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

    <script>
        // ดึงข้อมูล JSON จาก PHP สำหรับการแสดงกราฟ Attendance By Employee
        fetch('../../api/attendanceDailyApi.php')
            .then(response => response.json())
            .then(data => {
                const labels = [];
                const datasets = [];

                // เตรียมข้อมูล
                Object.keys(data).forEach(name => {
                    const attendance = data[name];
                    const months = Object.keys(attendance);
                    const counts = Object.values(attendance);

                    // เพิ่มเดือนใน labels (ถ้ายังไม่มี)
                    months.forEach(month => {
                        if (!labels.includes(month)) {
                            labels.push(month);
                        }
                    });

                    // สร้าง dataset สำหรับแต่ละพนักงาน
                    datasets.push({
                        label: name,
                        data: counts,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        borderWidth: 1
                    });
                });

                // สร้างกราฟด้วย Chart.js
                const ctx = document.getElementById('attendanceByMonth').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels.sort(), // เรียงเดือน
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Monthly Attendance Count by Employee'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Attendance Count'
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        // ฟังก์ชันสำหรับสร้างสีแบบสุ่ม
        function getRandomColor() {
            return `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`;
        }
    </script>

    <script>
        fetch('../../api/leaveCountApi.php')
            .then(response => response.json())
            .then(data => {
                const months = data.months;
                const sickLeaveCounts = data.sickLeaveCounts;
                const personalLeaveCounts = data.personalLeaveCounts;

                // สร้างกราฟ
                const ctx = document.getElementById('leaveChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                                label: 'ลาป่วย',
                                data: sickLeaveCounts,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ลากิจ',
                                data: personalLeaveCounts,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'จำนวนการลา'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'เดือน'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'จำนวนการลา'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

</body>

</html>