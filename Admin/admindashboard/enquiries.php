<?php include("config.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="enquiries.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>User Enquiry</title>
    <style>
        /* Include additional styles directly here */
        <?php include 'enquiries.css'; ?>
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
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="userrole.php">User Role</a></li>
                        <li class="nav-item"><a class="nav-link" href="userinfo.php">User Info</a></li>
                        <li class="nav-item"><a class="nav-link" href="payment.php">Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="approved_payment.php">Approved Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="active.php">Student Status</a></li>
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="enquiries.php">Enquiries</a></li>
                        <li class="nav-item"><a class="nav-link" href="/MyWebsite 2.0/notifications/notifications.php">Notification</a></li>
                    </ul>
                    <div class="logout"><a href="/MyWebsite 2.0/Login/logout.php" class="btn btn-warning">Logout</a></div>
                </div>
            </div>
        </nav>
    </header>
    
    <div class="container-fluid mt-5">
        <div class="text-center"><h1>USER ENQUIRIES</h1></div>
        
        <!-- Search Form -->
        <form action="" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search enquiries by name or email" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile No</th>
                        <th scope="col">Email</th>
                        <th scope="col">Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                    try {
                        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
                        $stmt = $conn->prepare("SELECT * FROM enquiries WHERE full_name LIKE :search OR email LIKE :search");
                        $stmt->execute([':search' => "%$searchQuery%"]);

                        if ($stmt->rowCount() > 0) {
                            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $id = $data['id'];
                                $fullName = $data['full_name'];
                                $mobileNo = $data['mobile_no'];
                                $email = $data['email'];
                                $message = $data['message'];

                                // Highlight the search keyword in the results
                                $highlightedName = preg_replace("/" . preg_quote($searchQuery, "/") . "/i", "<span class='highlight'>\$0</span>", htmlspecialchars($fullName));
                                $highlightedEmail = preg_replace("/" . preg_quote($searchQuery, "/") . "/i", "<span class='highlight'>\$0</span>", htmlspecialchars($email));
                    ?>
                    <tr>
                        <td><?php echo $highlightedName; ?></td>
                        <td><?php echo htmlspecialchars($mobileNo); ?></td>
                        <td><?php echo $highlightedEmail; ?></td>
                        <td><?php echo htmlspecialchars($message); ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">No enquiries found</td></tr>';
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