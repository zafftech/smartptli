<?php
session_start(); // Start the session

include("config.php"); // Include your database connection

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /MyWebsite%202.0/index.html"); // Redirect to homepage if not admin
    exit;
    
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

// Fetch the uploaded files from the cart_items table
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$sqlCartItems = "SELECT name, email, phone, product_name AS package, price, quantity, total_price, subject_selection, created_at, receipt, id FROM cart_items WHERE name LIKE :search OR email LIKE :search";
$stmtCartItems = $conn->prepare($sqlCartItems);
$stmtCartItems->execute([':search' => "%$searchQuery%"]);
$resultCartItems = $stmtCartItems->fetchAll(PDO::FETCH_ASSOC);

// Fetch the approved records from the approved table
$sqlApproved = "SELECT name, email, phone, product_name AS package, price, quantity, total_price, subject_selection, created_at, ended_at, receipt FROM approved WHERE name LIKE :search OR email LIKE :search";
$stmtApproved = $conn->prepare($sqlApproved);
$stmtApproved->execute([':search' => "%$searchQuery%"]);
$resultApproved = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="approved_payment.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Approve Payment</title>
    <style>
        /* Include additional styles directly here */
        <?php include 'approved_payment.css'; ?>
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
                    <li class="nav-item"><a class="nav-link active" href="approved_payment.php">Approved Payment</a></li>
                    <li class="nav-item"><a class="nav-link" href="active.php">Student Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="enquiries.php">Enquiries</a></li>
                    <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/notifications/notifications.php">Notification</a></li>
                </ul>
                <div class="logout">
                    <a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="container-fluid mt-5">
    <h2 class="mb-4 text-center">Approved List</h2>

    <!-- Search Form -->
    <form action="" method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search approved records by name or email" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
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
                    <th>Ended At</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($resultApproved)): ?>
                    <?php foreach ($resultApproved as $row): ?>
                        <?php
                        // Highlight the search keyword in the results
                        $highlightedName = preg_replace("/" . preg_quote($searchQuery, "/") . "/i", "<span class='highlight'>\$0</span>", htmlspecialchars($row['name']));
                        $highlightedEmail = preg_replace("/" . preg_quote($searchQuery, "/") . "/i", "<span class='highlight'>\$0</span>", htmlspecialchars($row['email']));

                        // Define the absolute path where the receipts are stored
                        $uploadDir = 'C:/xampp/htdocs/MyWebsite 2.0/Admin/download/uploads/';
                        $receiptFileName = basename(trim($row['receipt']));
                        $receiptAbsolutePath = $uploadDir . $receiptFileName;

                        // Define the relative path for the browser to access
                        $receiptWebPath = '/MyWebsite 2.0/Admin/download/uploads/' . $receiptFileName;
                        ?>
                        <tr>
                            <td><?php echo $highlightedName; ?></td>
                            <td><?php echo $highlightedEmail; ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['package']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_selection']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($row['ended_at']); ?></td>
                            <td>
                                <?php if (file_exists($receiptAbsolutePath)): ?>
                                    <a href="<?php echo $receiptWebPath; ?>" class="btn btn-primary" download>Download Receipt</a>
                                <?php else: ?>
                                    No receipt found
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No approved data available yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn = null;
?>
