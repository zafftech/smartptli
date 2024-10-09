<?php
session_start();

// Ensure UPLOAD_DIR is defined
define('UPLOAD_DIR', 'C:/xampp/htdocs/MyWebsite 2.0/Login/upload/');

if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    include "config.php";

    if (isset($_POST['fName'], $_POST['lName'], $_POST['old_password'])) {
        $firstName = htmlspecialchars(trim($_POST['fName']));
        $lastName = htmlspecialchars(trim($_POST['lName']));
        $old_pp = $_POST['old_pp'];
        $id = $_SESSION['user_id'];
        $oldPassword = htmlspecialchars(trim($_POST['old_password']));

        // Validate first and last name
        if (empty($firstName) || empty($lastName)) {
            header("Location: editui.php?error=" . urlencode("First and Last name are required"));
            exit;
        }

        // Fetch user data from the database
        $sql = "SELECT * FROM loginn WHERE Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        // Debugging outputs
        if ($user) {
            echo "User found: " . json_encode($user); // Show user data
        } else {
            header("Location: editui.php?error=" . urlencode("User not found!"));
            exit;
        }

        // Validate old password
        if (password_verify($oldPassword, $user['password'])) {
            // Handle new password and profile picture upload
            $newPassword = !empty($_POST['new_password']) ? password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT) : $user['password'];

            // Handle profile picture upload
            if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
                $img_name = $_FILES['pp']['name'];
                $img_ex = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
                $allowed_exs = array('jpg', 'jpeg', 'png');

                if (in_array($img_ex, $allowed_exs)) {
                    $new_img_name = uniqid($id, true) . '.' . $img_ex;
                    $img_upload_path = UPLOAD_DIR . $new_img_name;

                    // Delete old profile picture
                    if (file_exists(UPLOAD_DIR . $old_pp)) {
                        unlink(UPLOAD_DIR . $old_pp);
                    }

                    if (move_uploaded_file($_FILES["pp"]["tmp_name"], $img_upload_path)) {
                        // Update the database
                        $sql = "UPDATE loginn SET firstName=?, lastName=?, pp=?, password=? WHERE Id=?";
                        $stmt = $conn->prepare($sql);
                        if ($stmt->execute([$firstName, $lastName, $new_img_name, $newPassword, $id])) {
                            $_SESSION['fName'] = $firstName;
                            $_SESSION['pp'] = $new_img_name;
                            header("Location: editui.php?success=" . urlencode("Your account has been updated successfully"));
                            exit;
                        } else {
                            header("Location: editui.php?error=" . urlencode("Failed to update the database."));
                            exit;
                        }
                    }
                } else {
                    header("Location: editui.php?error=" . urlencode("You can't upload files of this type!"));
                    exit;
                }
            } else {
                // Update the database without changing the profile picture
                $sql = "UPDATE loginn SET firstName=?, lastName=?, password=? WHERE Id=?";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$firstName, $lastName, $newPassword, $id])) {
                    $_SESSION['fName'] = $firstName;
                    header("Location: editui.php?success=" . urlencode("Your account has been updated successfully"));
                    exit;
                } else {
                    header("Location: editui.php?error=" . urlencode("Failed to update the database."));
                    exit;
                }
            }
        } else {
            header("Location: editui.php?error=" . urlencode("Incorrect old password!"));
            exit;
        }
    } else {
        header("Location: editui.php?error=" . urlencode("All fields are required!"));
        exit;
    }
} else {
    header("Location: /MyWebsite 2.0/Login/!login.php");
    exit;
}
?>
