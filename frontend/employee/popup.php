<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="welcomeModalLabel">Welcome to the System</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You've successfully logged in! Please complete your profile information.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="profile.php" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <main class="container">
        <div class="bg-light p-5 rounded">
            <h1>Welcome, <?php echo htmlspecialchars($userData['name']); ?></h1>
            <p class="lead">Your email: <?php echo htmlspecialchars($userData['email']); ?></p>
            <p>Your role: <?php echo htmlspecialchars($userData['role']); ?></p>
            <img src="<?php echo htmlspecialchars($userData['picture']); ?>" class="rounded" alt="profile img">
        </div>
    </main>

    <!-- Script to trigger the modal -->
    <script>
        // Trigger modal when page loads
        var modal = new bootstrap.Modal(document.getElementById('welcomeModal'));
        modal.show();
    </script>