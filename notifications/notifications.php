<?php
include("./connection/DB.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/MyWebsite 2.0/Admin/admindashboard/pusher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="notification.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="notification.css"/>

    <title>Notification</title>
</head>
<body>
<header>
    <?php
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

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="logo"><img src="/MyWebsite 2.0/Admin/admindashboard/ICON.png" alt="Logo"></div>
            <a class="navbar-brand">ADMIN DASHBOARD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/userrole.php">User Role</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/userinfo.php">User Info</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/payment.php">Payment</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/approved_payment.php">Approved Payment</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/active.php">Student Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/Admin/admindashboard/enquiries.php">Enquiries</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/notifications/notifications.php">Notification</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><i class="fa fa-bell bell-icon" id="over" data-value="<?php echo $count_active; ?>"></i></li>
                    <?php if ($count_active > 0) { ?>
                    <div class="round" id="bell-count" data-value="<?php echo $count_active; ?>"><span><?php echo $count_active; ?></span></div>
                    <?php } ?>
                </ul>
                <div class="logout">
                    <a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<br><br><br>
<div class="container">
    <h3>Notifications System</h3><br>
    <form class="form-horizontal" id="frm_data">
        <div class="form-group row">
            <label class="control-label col-md-4" for="notification">Name</label>
            <div class="col-md-6">
                <input type="text" name="notifications_name" id="notifications_name" class="form-control" placeholder="Notification name" required />
            </div>
        </div>
        <br>
        <div class="form-group row">
            <label class="control-label col-md-4" for="notification">Message</label>
            <div class="col-md-6">
                <textarea style="resize:none;" name="message" id="message" rows="4" cols="10" class="form-control"></textarea>
            </div>
        </div>
        <br>
        <div class="form-group row">
            <div class="col-md-10 col-offset-2" style="text-align:center;">
                <input type="submit" id="notify" name="submit" class="btn btn-danger" value="NOTIFY"/>
            </div>
        </div>
    </form>
</div>

<!-- Notification Popup -->
<div id="notification-popup" class="notification-popup" style="display:none;">
    <div class="notification-header">
        <span>Notifications</span>
        <i class="fa fa-times close-popup" onclick="hideNotifications()"></i>
    </div>
    <div id="list" class="notification-dropdown">
        <?php if ($count_active > 0) { ?>
            <?php foreach ($notifications_data as $list_rows) { ?>
                <div class="notification-item">
                    <div class="notification-content">
                        <span class="notification-title"><?php echo htmlspecialchars($list_rows['notifications_name']); ?></span>
                        <div class="msg">
                            <p><?php echo htmlspecialchars($list_rows['message']); ?></p>
                        </div>
                        <!-- Delete Button -->
                        <button class="btn btn-danger delete-notification" data-id="<?php echo $list_rows['n_id']; ?>">Delete</button>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No active notifications</p>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleNotifications() {
    var popup = document.getElementById('notification-popup');
    
    // Toggle between 'block' and 'none'
    if (popup.style.display === 'none' || popup.style.display === '') {
        popup.style.display = 'block';  // Show popup
    } else {
        popup.style.display = 'none';  // Hide popup
    }
}

// Add event listener for the bell icon to toggle the notifications popup
document.getElementById('over').addEventListener('click', function() {
    toggleNotifications();
});

// Optional: Close the popup when clicking the 'x' icon (if you want)
function hideNotifications() {
    document.getElementById('notification-popup').style.display = 'none';
}

</script>
</body>
</html>
