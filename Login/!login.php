
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="HEADER.css">
</head>
<body>
<header>
        <nav class="navbar">
            <div class="navdiv">
                <div class="logo"><img src="ICON.png" alt="Logo"></div>
                <ul>
                <li><a href="/MyWebsite%202.0/index.html">Home</a></li>
                    <li><a href="/MyWebsite 2.0/MAIN INTERFACE/SMARTPTLI(EduLevel).html">Education Level</a></li>
                    <li><a href="/MyWebsite 2.0/MAIN INTERFACE/SMARTPTLI(ClassSchedule).html">Class Schedule</a></li>
                    <li><a href="/MyWebsite 2.0/MAIN INTERFACE/SMARTPTLI(ContactUs).html">Contact Us</a></li>
                    <li><a href="/MyWebsite 2.0/MAIN INTERFACE/SMARTPTLI(Shop).html">Shop</a></li>        
                </ul>

                <!-- Cart Icon Section -->
                <div class="iconCart">
                    <a href="#"><i class='bx bx-cart'></i></a>
                    <div class="totalQuantity">0</div> <!-- Cart Quantity Indicator -->
                </div>

                <!-- Auth Buttons Section -->
                <div class="auth-buttons">
                    <button class="border-button"><a href="/MyWebsite 2.0/Login/!signup.php">Sign Up</a></button>
                    <button class="border-button"><a href="/MyWebsite 2.0/Login/!login.php">Log In</a></button>
                </div>
            </div>
        </nav>
</header> 

    <div class="container" id="signIn" style="display:block;">
        <h1 class="form-title">Log In</h1>
        <form method="post" action="register.php" enctype="multipart/form-data">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="Email" required>
              <label for="email">Email</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
          </div>
          <p class="recover">
            <a href="/MyWebsite 2.0/Login/recover/forgot-password.php">Recover Password</a>
          </p>
         <input type="submit" class="btn" value="Log In" name="signin">
        </form>
        
        <div class="links">
        <p>Don't have an account yet?</p>
        <a href="!signup.php" id="signUpButton">
        <button>Register</button>
        </a>
        </div>


</body>
</html>
