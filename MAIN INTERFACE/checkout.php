<?php
    include 'connect.php';

    // Set the timezone
    date_default_timezone_set('Asia/Kuala_Lumpur');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        
        $receiptPath = null;

        // Check for file upload
        if (isset($_FILES['receipt']['name']) && !empty($_FILES['receipt']['name'])) {
            $img_name = $_FILES['receipt']['name'];
            $tmp_name = $_FILES['receipt']['tmp_name'];
            $error = $_FILES['receipt']['error'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB

            if ($error === 0 && $_FILES['receipt']['size'] <= $maxFileSize) {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_to_lc = strtolower($img_ex);

                $allowed_exs = array('jpg', 'jpeg', 'png');
                if (in_array($img_ex_to_lc, $allowed_exs)) {
                    $new_img_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", uniqid($email, true) . '.' . $img_ex_to_lc);
                    $uploadDir = '../Admin/download/uploads/'; 
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true); 
                    }
                    $uploadFile = $uploadDir . $new_img_name;

                    if (move_uploaded_file($tmp_name, $uploadFile)) {
                        $receiptPath = $uploadFile;
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                        exit;
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Only jpg, jpeg, or png allowed.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'File too large or unknown error occurred.']);
                exit;
            }
        }

        $cartItems = isset($_POST['cartItems']) ? $_POST['cartItems'] : [];

        // Prepare the SQL statement for inserting cart items
        $stmt = $conn->prepare("INSERT INTO cart_items (name, email, phone, product_name, price, quantity, total_price, subject_selection, receipt, created_at, ended_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($cartItems as $cartItem) {
            $cartItem = json_decode($cartItem, true);

            $productName = $cartItem['productName'];
            $price = $cartItem['price'];
            $quantity = $cartItem['quantity'];
            $totalPrice = $cartItem['totalPrice'];
            $subjectSelection = implode(',', $cartItem['selectedSubjects']);
            
            // Get current time for created_at
            $createdAt = date('Y-m-d H:i:s'); // Current timestamp
            $endedAt = date('Y-m-d H:i:s', strtotime($createdAt . ' +1 month')); // Calculate ended_at by adding 1 month

            // Bind the parameters, including created_at and ended_at
            $stmt->bind_param("ssissssssss", $name, $email, $phone, $productName, $price, $quantity, $totalPrice, $subjectSelection, $receiptPath, $createdAt, $endedAt);
            
            // Execute and check for errors
            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                exit;
            }
        }

        $stmt->close();
        $conn->close();
        echo json_encode(['status' => 'success']);
    }

    // Error reporting for debugging (you may want to disable this in production)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>