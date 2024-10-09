<?php
session_start();

include("../Admin/admindashboard/config.php");
include("../Login/connect.php");
include("../notifications/connection/DB.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize $count_active
$count_active = 0;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You need to log in first.");
}

$userId = $_SESSION['user_id']; // Retrieve the user ID from the session

// Fetch the logged-in user's details
$query = $conn->prepare("SELECT Id, name, email, month, status FROM student_status WHERE Id = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    die("Error: No data found for the provided User ID.");
}

// Fetch active notifications
$find_notifications = "SELECT * FROM notifications WHERE active = 1";
$result = mysqli_query($connection, $find_notifications);
$count_active = mysqli_num_rows($result);
$notifications_data = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch deactivated notifications
$deactive_notifications = "SELECT * FROM notifications WHERE active = 0 ORDER BY n_id DESC LIMIT 5";
$result = mysqli_query($connection, $deactive_notifications);
$deactive_notifications_dump = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STATUS PAYMENTS</title>
    <link rel="stylesheet" href="SMARTPTLI(MAIN).css">
    <link rel="stylesheet" href="HEADER.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bell.css"/>
    <script src="/MyWebsite 2.0/notifications/notification.js" defer></script>
    <script src="../notifications/assets/js/jquery.min.js"></script>
    <script src="../notifications/assets/js/bootstrap.min.js"></script>
    <script src="bell.js" defer></script>
    <link rel="stylesheet" href="activestatus.css">
</head>

<body style="background-color:#eff0f5 ;">
    <!-- Updated Header -->
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
    <main>
    <div class="container-fluid mt-5" style="max-width: 90%;">
            <div class="text-center"><h1>STATUS PAYMENT</h1></div><br>
            <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered border-primary text-center">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Month</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($userData['name']); ?></td>
                        <td><?php echo htmlspecialchars($userData['email']); ?></td>
                        <td><?php echo htmlspecialchars($userData['month']); ?></td>
                        <td>
                            <?php 
                            if ($userData['status'] == 1) {
                                echo '<span class="badge bg-success">Active</span>';
                            } else {
                                echo '<span class="badge bg-danger">Deactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </main>

<script>
    // Show notifications
    function showNotifications() {
    document.getElementById('notification-popup').style.display = 'block';
    // Ensure the notification count stays visible
    document.getElementById('bell-count').style.display = 'block';
}

function hideNotifications() {
    document.getElementById('notification-popup').style.display = 'none';
    // Ensure the notification count stays visible
    document.getElementById('bell-count').style.display = 'block';
}

</script>
</body>

</html>
