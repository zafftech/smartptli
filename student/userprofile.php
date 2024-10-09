<?php 
session_start();

include "db_conn.php"; 
include "user.php"; 

if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    $user = getUserById($_SESSION['user_id'], $conn); 

    if (!$user) {
        echo "Redirecting to login because user is not found.";
        header("Location: /MyWebsite 2.0/Login/!login.php");
        exit;
    }
} else {
    echo "Redirecting to login because session is not set.";
    header("Location: /MyWebsite 2.0/Login/!login.php");
    exit;
}

// Initialize $count_active
$count_active = 0;

// Fetch active notifications (using PDO)
$find_notifications = $conn->prepare("SELECT * FROM notifications WHERE active = 1");
$find_notifications->execute();
$notifications_data = $find_notifications->fetchAll(PDO::FETCH_ASSOC);
$count_active = count($notifications_data);

// Fetch deactivated notifications (if needed, using PDO)
$deactive_notifications = $conn->prepare("SELECT * FROM notifications WHERE active = 0 ORDER BY n_id DESC LIMIT 5");
$deactive_notifications->execute();
$deactive_notifications_dump = $deactive_notifications->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART PTLI-Home</title>
    <link rel="stylesheet" href="SMARTPTLI(MAIN).css">
    <link rel="stylesheet" href="HEADER.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="bell.css"/>
    <script src="bell.js" defer></script>
</head>
<body style="background-color:#eff0f5;">
<header>
        <nav class="navbar">
            <div class="navdiv">
            <div class="logo"><img src="ICON.png" alt="Logo"></div>
                <ul>
                <li><a href="userprofile.php">Profile</a></li>
                    <li><a href="activestatus.php">Status</a></li>
                    <li><a href="timeline.php">Time</a></li>
                    <li><a href="SMARTPTLI(Shop).php">Shop</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <!-- Bell Notification Icon -->
                    <li>
                        <i class="fa fa-bell bell-icon" id="over" onclick="showNotifications()" data-value="<?php echo $count_active; ?>"></i>
                    </li>
                    <?php if ($count_active > 0) { ?>
                        <div class="round" id="bell-count" data-value="<?php echo $count_active; ?>">
                            <span><?php echo $count_active; ?></span>
                        </div>
                    <?php } ?>
                </ul>
            </div>
            <div class="logout">
                <a href="/MyWebsite 2.0/Login/logout.php" class="custom-btn">Log out</a>
            </div>
        </nav>
    </header>
    <!-- Notification Pop-up -->
    <div id="notification-popup" class="notification-popup">
        <div class="notification-header">
            <span>Notifications</span>
            <i class="fa fa-times close-popup" onclick="hideNotifications()"></i>
        </div>
        <div id="list" class="notification-dropdown">
            <?php foreach ($notifications_data as $list_rows) { ?>
                <div class="notification-item">
                    <div class="notification-content">
                        <span class="notification-title"><?php echo $list_rows['notifications_name']; ?></span>
                        <p class="notification-message"><?php echo $list_rows['message']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="d-flex justify-content-center align-items-center vh-50">
    <div class="profile-container shadow w-350 p-3 text-center">
        <img src="/MyWebsite 2.0/Login/upload/<?= htmlspecialchars($user['pp']) ?>" class="img-fluid rounded-circle profile-pic" alt="Profile Picture">
        <h3 class="display-4"><?= htmlspecialchars($user['firstName']) ?> <?= htmlspecialchars($user['lastName']) ?></h3>
        <a href="editui.php" class="btn btn-primary">Edit Profile</a>
        <a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a>
    </div>
</div>

<script>
    // Show notifications
    function showNotifications() {
        document.getElementById('notification-popup').style.display = 'block';
        document.getElementById('bell-count').style.display = 'block';
    }

    function hideNotifications() {
        document.getElementById('notification-popup').style.display = 'none';
        document.getElementById('bell-count').style.display = 'block';
    }
</script>
</body>
</html>
