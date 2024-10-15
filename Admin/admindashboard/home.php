<?php
session_start();
include("config.php");
include("admin.php");

// Check if user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    // Retrieve user details
    $user = getUserById($_SESSION['user_id'], $conn); // Use $conn for PDO

    if (!$user) {
        // If no user found, redirect to login
        header("Location: /MyWebsite 2.0/Login/!login.php");
        exit;
    }

    // Check if the user has the admin role
    if ($user['role'] !== 'admin') {
        // If the user is not an admin, redirect to a different page, like a user dashboard or error page
        header("Location: /MyWebsite%202.0/index.html");
        exit;
    }
} else {
    // If user is not logged in, redirect to login
    header("Location: /MyWebsite 2.0/Login/!login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Home</title>
    <style>
        <?php include 'home.css'; ?>
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="logo"><img src="ICON.png" alt="Logo"></div>
            <div class="container-fluid">
                <a class="navbar-brand">ADMIN DASHBOARD</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="userrole.php">User Role</a></li>
                        <li class="nav-item"><a class="nav-link" href="userinfo.php">User Info</a></li>
                        <li class="nav-item"><a class="nav-link" href="payment.php">Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="approved_payment.php">Approved Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="active.php">Student Status</a></li>
                        <li class="nav-item"><a class="nav-link" href="enquiries.php">Enquiries</a></li>
                        <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/notifications/notifications.php">Notification</a></li>
                    </ul>
                    <div class="logout"><a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a>
                </div>
            </div>
            </div>
        </nav>
    </header>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow w-350 p-3 text-center">
            <img src="/MyWebsite 2.0/Login/upload/<?= htmlspecialchars($user['pp']) ?>" class="img-fluid rounded-circle" alt="Profile Picture">
            <h3 class="display-4"><?= htmlspecialchars($user['firstName']) ?> <?= htmlspecialchars($user['lastName']) ?></h3>
            <a href="editui.php" class="btn btn-primary">Edit Profile</a>
            <a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a>
        </div>
    </div>
</body>
</html>
