<?php 
session_start();

// Check if the user is logged in using the same session variables as the second snippet
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    include 'db_conn.php';
    include 'user.php';

    // Retrieve user details using the same session variables
    $user = getUserById($_SESSION['user_id'], $conn);

    if ($user) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<style>
.w-450 {
    width: 450px;
}
.vh-100 {
    min-height: 100vh;
}
.w-350 {
    width: 350px;
}
</style>
<body>
    <div class="d-flex justify-content-center align-items-center vh-200">
        
    <form class="shadow w-450 p-3"  
      action="edit.php" 
      method="post"
      enctype="multipart/form-data">

    <h4 class="display-4 fs-1">Edit Profile</h4><br>
    <!-- error -->
    <?php if(isset($_GET['error'])){ ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $_GET['error']; ?>
    </div>
    <?php } ?>
    
    <!-- success -->
    <?php if(isset($_GET['success'])){ ?>
    <div class="alert alert-success" role="alert">
        <?php echo $_GET['success']; ?>
    </div>
    <?php } ?>
    
    <div class="mb-3">
        <label class="form-label">First Name</label>
        <input type="text" class="form-control" name="fName" value="<?php echo htmlspecialchars($user['firstName']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" name="lName" value="<?php echo htmlspecialchars($user['lastName']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Old Password</label>
        <input type="password" class="form-control" name="old_password">
    </div>

    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" class="form-control" name="new_password">
    </div>

    <div class="mb-3">
        <label class="form-label">IC</label>
        <input type="text" class="form-control" name="ic" value="<?php echo htmlspecialchars($user['ic']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Age</label>
        <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($user['age']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">School Name</label>
        <input type="text" class="form-control" name="schoolName" value="<?php echo htmlspecialchars($user['schoolName']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Telephone</label>
        <input type="text" class="form-control" name="telephone" value="<?php echo htmlspecialchars($user['telephone']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Parent Name</label>
        <input type="text" class="form-control" name="parentName" value="<?php echo htmlspecialchars($user['parentName']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea class="form-control" name="address"><?php echo htmlspecialchars($user['address']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" class="form-control" name="pp"><br>
        <img src="/MyWebsite 2.0/Login/upload/<?= htmlspecialchars($user['pp']) ?>" class="rounded-circle" style="width: 70px" alt="Profile Picture">
        <input type="text" hidden="hidden" name="old_pp" value="<?= htmlspecialchars($user['pp']) ?>">
    </div>
    
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="userprofile.php" class="btn btn-secondary">Home</a>
    </form>

    </div>
</body>
</html>

<?php 
    } else {
        header("Location: userprofile.php");
        exit;
    }
} else {
    // Redirect to login if not logged in
    header("Location: /MyWebsite 2.0/Login/!login.php");
    exit;
} 
?>
