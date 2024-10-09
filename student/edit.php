<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    include "db_conn.php";

    if (isset($_POST['fName'], $_POST['lName'], $_POST['old_password'], $_POST['new_password'], $_POST['ic'], $_POST['age'], $_POST['schoolName'], $_POST['telephone'], $_POST['parentName'], $_POST['address'])) {
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $ic = $_POST['ic'];
        $age = $_POST['age'];
        $schoolName = $_POST['schoolName'];
        $telephone = $_POST['telephone'];
        $parentName = $_POST['parentName'];
        $address = $_POST['address'];
        $old_pp = $_POST['old_pp'];
        $id = $_SESSION['user_id'];

        if (empty($firstName) || empty($lastName)) {
            header("Location: editui.php?error=" . urlencode("First and Last name are required"));
            exit;
        }

        $sql = "SELECT * FROM loginn WHERE Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['old_password'], $user['password'])) {
            $newPassword = !empty($_POST['new_password']) ? password_hash($_POST['new_password'], PASSWORD_DEFAULT) : $user['password'];

            if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
                $img_name = $_FILES['pp']['name'];
                $img_ex = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
                $allowed_exs = array('jpg', 'jpeg', 'png');
                
                if (in_array($img_ex, $allowed_exs)) {
                    $new_img_name = uniqid($id, true) . '.' . $img_ex;
                    $img_upload_path = "C:/xampp/htdocs/MyWebsite 2.0/Login/upload/" . $new_img_name;

                    if (file_exists("C:/xampp/htdocs/MyWebsite 2.0/Login/upload/$old_pp")) {
                        unlink("C:/xampp/htdocs/MyWebsite 2.0/Login/upload/$old_pp");
                    }

                    if (move_uploaded_file($_FILES["pp"]["tmp_name"], $img_upload_path)) {
                        $sql = "UPDATE loginn SET firstName=?, lastName=?, pp=?, password=?, ic=?, age=?, schoolName=?, telephone=?, parentName=?, address=? WHERE Id=?";
                        $stmt = $conn->prepare($sql);
                        if ($stmt->execute([$firstName, $lastName, $new_img_name, $newPassword, $ic, $age, $schoolName, $telephone, $parentName, $address, $id])) {
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
                $sql = "UPDATE loginn SET firstName=?, lastName=?, password=?, ic=?, age=?, schoolName=?, telephone=?, parentName=?, address=? WHERE Id=?";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$firstName, $lastName, $newPassword, $ic, $age, $schoolName, $telephone, $parentName, $address, $id])) {
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
