<?php
session_start();
include("config.php");
include("admin.php"); // Assuming this file contains the function getUserById

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
    <link rel="stylesheet" href="studentstatus.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Student Profile</title>
    <style>
        /* Include additional styles directly here */
        <?php include 'studentstatus.css'; ?>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
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
                        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="userrole.php">User Role</a></li>
                        <li class="nav-item"><a class="nav-link" href="userinfo.php">User Info</a></li>
                        <li class="nav-item"><a class="nav-link" href="payment.php">Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="approved_payment.php">Approved Payment</a></li>
                        <li class="nav-item"><a class="nav-link active" href="active.php">Student Status</a></li>
                        <li class="nav-item"><a class="nav-link" href="enquiries.php">Enquiries</a></li>
                        <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/notifications/notifications.php">Notification</a></li>
                    </ul>
                    <div class="logout"><a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a></div>
                </div>  
            </div>
        </nav>
    </header>
    
    <div class="container-fluid mt-5">
        <div class="text-center"><h1>STUDENT STATUS</h1></div>
        
        <!-- Search Form -->
        <form action="" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search student records by name or email" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Month</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                    try {
                        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
                        $view = $conn->prepare("SELECT * FROM student_status WHERE name LIKE :search OR email LIKE :search");
                        $view->execute([':search' => "%$searchQuery%"]);

                        if ($view->rowCount() > 0) {
                            while($data = $view->fetch(PDO::FETCH_ASSOC)) {
                                $id = $data['id'];
                                $name = $data['name'];
                                $email = $data['email'];
                                $month = $data['month'];
                                $status = $data['status'];

                                // Highlight the search keyword in the results
                                $highlightedName = preg_replace("/" . preg_quote($searchQuery, "/") . "/i", "<span class='highlight'>\$0</span>", htmlspecialchars($name));
                                $highlightedEmail = preg_replace("/" . preg_quote($searchQuery, "/") . "/i", "<span class='highlight'>\$0</span>", htmlspecialchars($email));
                    ?>
                    <tr>
                        <td><?php echo $highlightedName; ?></td>
                        <td><?php echo $highlightedEmail; ?></td>
                        <td>
                            <form action="update_month.php" method="post">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <select name="month" onchange="this.form.submit()">
                                    <option value="January" <?php if($month == 'January') echo 'selected'; ?>>January</option>
                                    <option value="February" <?php if($month == 'February') echo 'selected'; ?>>February</option>
                                    <option value="March" <?php if($month == 'March') echo 'selected'; ?>>March</option>
                                    <option value="April" <?php if($month == 'April') echo 'selected'; ?>>April</option>
                                    <option value="May" <?php if($month == 'May') echo 'selected'; ?>>May</option>
                                    <option value="June" <?php if($month == 'June') echo 'selected'; ?>>June</option>
                                    <option value="July" <?php if($month == 'July') echo 'selected'; ?>>July</option>
                                    <option value="August" <?php if($month == 'August') echo 'selected'; ?>>August</option>
                                    <option value="September" <?php if($month == 'September') echo 'selected'; ?>>September</option>
                                    <option value="October" <?php if($month == 'October') echo 'selected'; ?>>October</option>
                                    <option value="November" <?php if($month == 'November') echo 'selected'; ?>>November</option>
                                    <option value="December" <?php if($month == 'December') echo 'selected'; ?>>December</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <?php
                            if($status == 1) {
                                echo '<a href="active.php?id='.$id.'&status=0" class="btn btn-success">Active</a>';
                            } else {
                                echo '<a href="active.php?id='.$id.'&status=1" class="btn btn-danger">Deactive</a>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">No student records found</td></tr>';
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-oBqDVmMz4fnFO9GQkk5uNIpH7aP0h2zVX5TI19g6Wb1YQuP43H9k7B0Fsc8N6eH0" crossorigin="anonymous"></script>
</body>
</html>
