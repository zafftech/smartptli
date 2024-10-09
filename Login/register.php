<?php
// Include your database connection here
include 'connect.php';

if (isset($_POST['signup'])) {
    if (
        !empty($_POST['fName']) && !empty($_POST['lName']) &&
        !empty($_POST['email']) && !empty($_POST['password']) &&
        !empty($_POST['ic']) && !empty($_POST['age']) &&
        !empty($_POST['schoolName']) && !empty($_POST['telephone']) &&
        !empty($_POST['parentName']) && !empty($_POST['address'])
    ) {
        // Retrieve form data
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password securely
        $ic = $_POST['ic'];
        $age = $_POST['age'];
        $schoolName = $_POST['schoolName'];
        $telephone = $_POST['telephone'];
        $parentName = $_POST['parentName'];
        $address = $_POST['address'];
        $role = 'student'; // Default role
        $name = $firstName . ' ' . $lastName; // Combine first and last name

        // Check if email already exists in loginn table
        $checkEmail = $conn->prepare("SELECT * FROM loginn WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email Address Already Exists!'); window.location.href = '!signup.php';</script>";
        } else {
            // Handle image upload if provided
            $new_img_name = '';
            if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_to_lc = strtolower($img_ex);

                    $allowed_exs = array('jpg', 'jpeg', 'png');
                    if (in_array($img_ex_to_lc, $allowed_exs)) {
                        $new_img_name = uniqid($email, true) . '.' . $img_ex_to_lc;
                        $img_upload_path = 'upload/' . $new_img_name;

                        if (!move_uploaded_file($tmp_name, $img_upload_path)) {
                            echo "<script>alert('Failed to upload image.'); window.location.href = '!signup.php';</script>";
                            exit;
                        }
                    } else {
                        echo "<script>alert('You can't upload files of this type.'); window.location.href = '!signup.php';</script>";
                        exit;
                    }
                } else {
                    echo "<script>alert('Unknown error occurred during image upload!'); window.location.href = '!signup.php';</script>";
                    exit;
                }
            }

            // Insert into loginn table
            $insertQuery = $conn->prepare("INSERT INTO loginn (firstName, lastName, email, password, role, pp, ic, age, schoolName, telephone, parentName, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertQuery->bind_param("ssssssssssss", $firstName, $lastName, $email, $password, $role, $new_img_name, $ic, $age, $schoolName, $telephone, $parentName, $address);

            if ($insertQuery->execute()) {
                // Insert into student_status table with default values for month and status
                $insertStudentStatus = $conn->prepare("INSERT INTO student_status (name, email, month, status) VALUES (?, ?, ?, ?)");
                $defaultMonth = 'January'; // Default month value
                $defaultStatus = 'deactive'; // Default status value
                $insertStudentStatus->bind_param("ssss", $name, $email, $defaultMonth, $defaultStatus);

                if ($insertStudentStatus->execute()) {
                    // Redirect to login page with success message
                    echo "<script>alert('Registration successful!'); window.location.href = '!login.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error inserting into student_status: " . $conn->error . "'); window.location.href = '!login.php';</script>";
                }
            } else {
                echo "<script>alert('Error inserting into loginn: " . $conn->error . "'); window.location.href = '!login.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Missing required fields!'); window.location.href = '!login.php';</script>";
    }
}


// Handle Signin
if (isset($_POST['signin'])) {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = $conn->prepare("SELECT * FROM loginn WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['user_id'] = $row['Id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role']; // Store the role in the session

                // Redirect based on role
                switch ($row['role']) {
                    case 'admin':
                        header("Location: ../Admin/admindashboard/home.php");
                        break;
                    case 'staff':
                        header("Location: ../staff/staffdashboard.php");
                        break;
                    case 'student':
                        header("Location: ../student/userprofile.php");
                        break;
                }
                exit();
            } else {
                echo "<script>alert('Incorrect Email or Password'); window.location.href = '!login.php';</script>";
            }
        } else {
            echo "<script>alert('Incorrect Email or Password'); window.location.href = '!login.php';</script>";
        }
    } else {
        echo "<script>alert('Please enter email and password'); window.location.href = '!login.php';</script>";
    }
}
?>
