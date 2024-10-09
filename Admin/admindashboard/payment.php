<?php
// payment.php

// Database connection details
include("config.php");

// Check if the highlightSearchTerm function exists, and if not, declare it
if (!function_exists('highlightSearchTerm')) {
    // Function to highlight the search term in the result
    function highlightSearchTerm($text, $searchTerm) {
        if ($searchTerm == '') {
            return htmlspecialchars($text);
        }
        return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<span class="highlight">$1</span>', htmlspecialchars($text));
    }
}

// Function to approve a record and transfer it to the approved table
function approveRecord($id) {
    global $conn;

    // Begin a transaction
    $conn->beginTransaction();
    try {
        // Fetch the record from cart_items
        $sqlFetch = "SELECT * FROM cart_items WHERE id = :id";
        $stmtFetch = $conn->prepare($sqlFetch);
        $stmtFetch->execute([':id' => $id]);
        $record = $stmtFetch->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            // Calculate ended_at as 1 month from created_at
            $endedAt = date('Y-m-d H:i:s', strtotime($record['created_at'] . ' +1 month'));

            // Insert the record into approved
            $sqlInsert = "INSERT INTO approved (name, email, phone, product_name, price, quantity, total_price, subject_selection, created_at, ended_at, receipt)
                          VALUES (:name, :email, :phone, :product_name, :price, :quantity, :total_price, :subject_selection, :created_at, :ended_at, :receipt)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->execute([
                ':name' => $record['name'],
                ':email' => $record['email'],
                ':phone' => $record['phone'],
                ':product_name' => $record['product_name'],
                ':price' => $record['price'],
                ':quantity' => $record['quantity'],
                ':total_price' => $record['total_price'],
                ':subject_selection' => $record['subject_selection'],
                ':created_at' => $record['created_at'],
                ':ended_at' => $endedAt,
                ':receipt' => $record['receipt'],
            ]);
        }

        // Commit the transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback the transaction if something fails
        $conn->rollBack();
        die("Error: " . $e->getMessage());
    }
}

// Check for approve action (to transfer a single record)
if (isset($_POST['approve'])) {
    $recordId = $_POST['record_id']; // Assume record ID is passed
    approveRecord($recordId);
}

// Fetch search query if available
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL query with search functionality
if ($searchQuery) {
    $sqlCartItems = "SELECT * FROM cart_items WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR product_name LIKE ? OR subject_selection LIKE ?";
    $stmt = $conn->prepare($sqlCartItems);
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->execute([$likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery]);
    $resultCartItems = $stmt;
} else {
    $sqlCartItems = "SELECT * FROM cart_items";
    $resultCartItems = $conn->query($sqlCartItems);
}

// Assuming a variable to determine if the user is admin
$isAdmin = true; // Change this based on your actual admin checking logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="payment.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Payment</title>
    <style>
        /* Include additional styles directly here */
        <?php include 'payment.css'; ?>
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
                    <li class="nav-item"><a class="nav-link active" href="payment.php">Payment</a></li>
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

<main class="container-fluid mt-5">
    <h2>Receipt Payment Files</h2>

    <!-- Search and Filter Form -->
    <form action="" method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search users by name, email, phone, product name, or subject selection..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Package</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Subject Selection</th>
                    <th>Time Paid</th>
                    <th>Receipt</th>
                    <?php if ($isAdmin): ?>
                        <th>Approve</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display the uploaded data from cart_items
                if ($resultCartItems->rowCount() > 0) {
                    while ($row = $resultCartItems->fetch(PDO::FETCH_ASSOC)) {
                        // Define the absolute path where the receipts are stored
                        $uploadDir = 'C:/xampp/htdocs/MyWebsite 2.0/Admin/download/uploads/';
                        $receiptFileName = basename(trim($row['receipt']));
                        $receiptAbsolutePath = $uploadDir . $receiptFileName;

                        // Define the relative path for the browser to access
                        $receiptWebPath = '/MyWebsite 2.0/Admin/download/uploads/' . $receiptFileName;

                        ?>
                        <tr>
                            <td><?php echo highlightSearchTerm($row['name'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($row['email'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($row['phone'], $searchQuery); ?></td>
                            <td><?php echo highlightSearchTerm($row['product_name'], $searchQuery); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                            <td><?php echo highlightSearchTerm($row['subject_selection'], $searchQuery); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if (file_exists($receiptAbsolutePath)): ?>
                                    <img src="<?php echo $receiptWebPath; ?>" alt="Receipt Image" style="width: 100px; height: auto;">
                                    <a href="<?php echo $receiptWebPath; ?>" class="btn btn-primary" download>Download Receipt</a>
                                <?php else: ?>
                                    No receipt found
                                <?php endif; ?>
                            </td>
                            <?php if ($isAdmin): ?>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="11">No data available yet.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn = null;
?>
