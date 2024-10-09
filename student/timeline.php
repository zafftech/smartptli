<?php 
// Enable error reporting to catch any issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Fetch all approved packages for the logged-in user based on their email
$packages_query = $conn->prepare("SELECT * FROM approved WHERE email = :email ORDER BY created_at DESC");
$packages_query->bindParam(':email', $_SESSION['email']);
$packages_query->execute();
$packages = $packages_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART PTLI - Approved Packages</title>
    <link rel="stylesheet" href="SMARTPTLI(MAIN).css">
    <link rel="stylesheet" href="timeline.css">
    <link rel="stylesheet" href="HEADER.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="bell.css"/>
    <script src="bell.js" defer></script>
    <style>
        .package-item {
            background-color: #fff; 
            border: 1px solid #ddd; 
            padding: 15px; 
            border-radius: 5px; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
            text-align: center; 
            width: 100%;
        }
        .countdown {
            font-size: 24px; 
            font-weight: bold;
            color: black;
            margin-top: 10px;
        }
        .countdown.red {
            color: red; 
        }
        .countdown div {
            margin: 0 10px; 
        }
        .countdown-label {
            font-size: 12px; 
        }
        table {
            width: 100%;
            border-spacing: 20px; 
        }
        td {
            width: 33%; 
            vertical-align: top; 
        }
    </style>
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

    <div id="notification-popup" class="notification-popup">
        <div class="notification-header">
            <span>Notifications</span>
            <i class="fa fa-times close-popup" onclick="hideNotifications()"></i>
        </div>
        <div id="list" class="notification-dropdown">
            <?php foreach ($notifications_data as $list_rows) { ?>
                <div class="notification-item">
                    <div class="notification-content">
                        <span class="notification-title"><?php echo htmlspecialchars($list_rows['notifications_name']); ?></span>
                        <p class="notification-message"><?php echo htmlspecialchars($list_rows['message']); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="packages-container">
    <?php
    foreach ($packages as $package) {
    ?>
        <div class="package-item">
            <div class="slider-title">Approved Package: <?php echo htmlspecialchars($package['product_name']); ?></div>
            <div class="subjects-list">
                <strong>Subjects:</strong>
                <ul>
                    <?php 
                    $subjects = explode(',', $package['subject_selection']);
                    foreach ($subjects as $subject) {
                        echo "<li>" . htmlspecialchars(trim($subject)) . "</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="countdown" id="countdown-<?php echo $package['id']; ?>">
                <div>
                    <span id="months-<?php echo $package['id']; ?>">00</span>
                    <span class="countdown-label">Months</span>
                </div>
                <div>
                    <span id="days-<?php echo $package['id']; ?>">00</span>
                    <span class="countdown-label">Days</span>
                </div>
                <div>
                    <span id="hours-<?php echo $package['id']; ?>">00</span>
                    <span class="countdown-label">Hours</span>
                </div>
                <div>
                    <span id="minutes-<?php echo $package['id']; ?>">00</span>
                    <span class="countdown-label">Minutes</span>
                </div>
                <div>
                    <span id="seconds-<?php echo $package['id']; ?>">00</span>
                    <span class="countdown-label">Seconds</span>
                </div>
            </div>
        </div>

        <script>
            (function() {
                let endTime = new Date("<?php echo $package['ended_at']; ?>");
                let interval;

                function updateCountdown() {
                    let now = new Date();
                    let timeLeft = endTime - now;

                    if (timeLeft < 0) {
                        timeLeft = 0;
                    }

                    let months = Math.max(0, Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30)));
                    let days = Math.max(0, Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24)));
                    let hours = Math.max(0, Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
                    let minutes = Math.max(0, Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60)));
                    let seconds = Math.max(0, Math.floor((timeLeft % (1000 * 60)) / 1000));

                    document.getElementById("months-<?php echo $package['id']; ?>").textContent = months.toString().padStart(2, '0');
                    document.getElementById("days-<?php echo $package['id']; ?>").textContent = days.toString().padStart(2, '0');
                    document.getElementById("hours-<?php echo $package['id']; ?>").textContent = hours.toString().padStart(2, '0');
                    document.getElementById("minutes-<?php echo $package['id']; ?>").textContent = minutes.toString().padStart(2, '0');
                    document.getElementById("seconds-<?php echo $package['id']; ?>").textContent = seconds.toString().padStart(2, '0');

                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        document.getElementById("countdown-<?php echo $package['id']; ?>").classList.add("red");
                    }
                }

                updateCountdown();
                interval = setInterval(updateCountdown, 1000);
            })();
        </script>
    <?php
    }
    ?>
</div>


    <script>
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
