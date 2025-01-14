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
            // Example employee ID
            $employee_id = $userData['id'];

            // Create an instance of DetailWork class
            $detailWork = new DetailWork();

            // Get the employee information
            $info = $detailWork->readInfo($employee_id);

            // Check if data was retrieved
            if ($info) {
                $index = 1;

                // Display attendance data first
                foreach ($info['attendance'] as $row) {
                    $attendance_time = isset($row['created_at']) ? $row['created_at'] : 'ไม่มีข้อมูลการเข้าทำงาน';
                    $departure_time = isset($row['departure_time']) ? $row['departure_time'] : 'ไม่มีข้อมูลการออกงาน';
                    echo "<tr>
                            <th scope='row'>{$index}</th>
                            <td>{$row['title']}{$row['firstname']} {$row['surname']}</td>
                            <td>{$attendance_time}</td>
                            <td>{$departure_time}</td>
                            <td>-</td> <!-- No leave data for this row -->
                            <td>-</td> <!-- No leave date for this row -->
                            <td>-</td> <!-- No reason for this row -->
                          </tr>";
                    $index++;
                }

                // Display leave data
                foreach ($info['leave'] as $leaveRow) {
                    echo "<tr>
                            <th scope='row'>{$index}</th>
                            <td>{$leaveRow['title']} {$leaveRow['firstname']} {$leaveRow['surname']}</td>
                            <td>-</td> <!-- No attendance data for this row -->
                            <td>-</td> <!-- No departure time for this row -->
                            <td>{$leaveRow['leave_type']}</td>
                            <td>{$leaveRow['leave_date']}</td>
                            <td>" . (empty($leaveRow['reason']) ? '-' : $leaveRow['reason']) . "</td> <!-- Use '-' if reason is empty -->
                          </tr>";
                    $index++;
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
