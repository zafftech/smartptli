<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/MyWebsite 2.0/Login/connect.php");

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../MAIN INTERFACE/index.html");
    exit();
}

// Fetch user details to edit
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM loginn WHERE Id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit();
    }

    $stmt->close();
} else {
    echo "No user ID specified!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="edit_user.css">
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form method="post" action="update_user.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['Id']); ?>">
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo htmlspecialchars($user['firstName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo htmlspecialchars($user['lastName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="student" <?php if ($user['role'] == 'student') echo 'selected'; ?>>Student</option>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="userrole.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO4vX0Pcu6ggA5Y1kIKT3Q2t4nT9XazAsZ2a1rbENmo0MdY9I4f2D" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-mQ93QXXOp2k0t5P0EGoe/U+2HnP8MD6E+jhGeReHf+I+z1ek9d4lKqVd9MZz6jZ2" crossorigin="anonymous"></script>
</body>
</html>
