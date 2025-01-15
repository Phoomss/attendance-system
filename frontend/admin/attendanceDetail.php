<?php
$user = new User();
$response = $user->getAllUser();

if ($response['success']) {
    $users = $response['data'];
} else {
    $users = []; // Ensure an empty array if no data is retrieved
    error_log($response['message']); // Optional: Log the error message
}
?>
<div class="row justify-content-center">
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user-circle fa-3x text-primary me-3 "></i>
                            <h5 class="card-title mb-0 ml-2">
                                <?= htmlspecialchars($user['title'] ?? 'N/A') ?>
                                <?= htmlspecialchars($user['firstname'] ?? 'Unknown') ?>
                                <?= htmlspecialchars($user['surname'] ?? 'Unknown') ?>
                            </h5>
                        </div>
                        <p class="text-muted mb-1">
                            <strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?>
                        </p>
                        <p class="text-muted mb-0">
                            <strong>Position:</strong> <?= htmlspecialchars($user['role'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-start">
                        <a href="attendanceMasterData.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-primary btn-sm">รายละเอียดการทำงาน</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <?= htmlspecialchars($response['message']) ?>
            </div>
        </div>
    <?php endif; ?>
</div>