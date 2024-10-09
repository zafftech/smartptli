<?php
// admin_dashboard.php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/MyWebsite 2.0/Login/connect.php");

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../MAIN INTERFACE/index.html");
    exit();
}

// Fetch search query if available
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL query with search functionality
if ($searchQuery) {
    $sql = "SELECT * FROM loginn WHERE firstName LIKE ? OR lastName LIKE ? OR email LIKE ? OR ic LIKE ? OR role LIKE ? OR schoolName LIKE ? OR telephone LIKE ? OR parentName LIKE ? OR address LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param('sssssssss', $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM loginn";
    $result = $conn->query($sql);
}

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare user data for the HTML
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Function to highlight the search term in the result
function highlightSearchTerm($text, $searchTerm) {
    if ($searchTerm == '') {
        return htmlspecialchars($text);
    }
    return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<span class="highlight">$1</span>', htmlspecialchars($text));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userinfo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>User Info</title>
    <style>
        /* Include additional styles directly here */
        <?php include 'userinfo.css'; ?>
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
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="userrole.php">User Role</a></li>
                        <li class="nav-item"><a class="nav-link active" href="userinfo.php">User Info</a></li>
                        <li class="nav-item"><a class="nav-link" href="payment.php">Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="approved_payment.php">Approved Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="active.php">Student Status</a></li>
                        <li class="nav-item"><a class="nav-link" href="enquiries.php">Enquiries</a></li>
                        <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/notifications/notifications.php">Notification</a></li>
                    </ul>
                    <div class="logout"><a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a></div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container-fluid mt-5">
        <h2 class="mb-4 text-center">User Info</h2>

        <!-- Search and Filter Form -->
        <form action="" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search users by name, email, role, etc." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>IC</th>
                        <th>Age</th>
                        <th>School Name</th>
                        <th>Telephone Number</th>
                        <th>Parent Name</th>
                        <th>Address</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo highlightSearchTerm($user['firstName'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['lastName'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['email'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['ic'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['age'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['schoolName'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['telephone'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['parentName'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['address'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($user['role'], $searchQuery); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-rlpyGTJ3XJs1uxs2rId9tRRFu/l5Tf6cdNhnG+cAEFgwl2npdZ+2m/pV9g7JylXG" crossorigin="anonymous"></script>
</body>
</html>
