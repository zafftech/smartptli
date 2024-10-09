<?php
session_start();
ini_set('display_errors', 1); // Show errors
error_reporting(E_ALL); // Report all PHP errors
include("../Login/connect.php");
include("../notifications/connection/DB.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART PTLI-Shop</title>
    <link rel="stylesheet" href="SMARTPTLI(Shop).css">
    <link rel="stylesheet" href="HEADER.css">
    <link rel="stylesheet" href="bell.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background-color:#eff0f5;">
    <header>
        <nav class="navbar">
            <div class="navdiv">
               <div class="container">
                    <div class="logo"><img src="ICON.png" alt="Logo"></div>
                    <ul>
                        <li><a href="userprofile.php">Profile</a></li>
                        <li><a href="activestatus.php">Status</a></li>
                        <li><a href="timeline.php">Time</a></li>
                        <li><a href="SMARTPTLI(Shop).php">Shop</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <div class="iconCart">
                            <li><a href="#"><i class='bx bx-cart'></i></a></li>
                        <div class="totalQuantity">0</div>
                    </div>
                </div>
                </ul>
            </div>
            <div class="logout">
                <a href="/MyWebsite 2.0/Login/logout.php" class="custom-btn">Log out</a>
            </div>

        </nav>
    </header>

    <div class="listProduct">
        <div class="item">
            <img src="GAMBAR DUMMY/1 SK OFFLINE.png" alt="" srcset="">
            <h2>1 SUBJEK</h2>
            <div class="price">RM75</div>
            <button>Add To Cart</button>
        </div>
    </div>

    <div class="cart">
        <h2>CART</h2>
        <div class="listCart">
            <div class="item">
                <img src="GAMBAR DUMMY/1 SK OFFLINE.png">
                <div class="content">
                    <div class="name">Product Name</div>
                    <div class="price">RM75/1 product</div>
                    <div class="quantity">
                        <button>-</button>
                        <span class="value">3</span>
                        <button>+</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons">
            <div class="close">CLOSE</div>
            <div class="checkout">
                <a href="checkout.html"></a>
                CHECKOUT
            </div>
        </div>
    </div>

    <script src="cart.js"></script>

</body>

</html>