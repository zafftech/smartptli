<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="HEADER.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css"rel="stylesheet"/>
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
    <div class="container" id="signup" style="display:block;">
      <h1 class="form-title">Register</h1>
      <form method="post" action="register.php" enctype="multipart/form-data">
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="fName" id="fName" placeholder="First Name" required>
           <label for="fname">First Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" required>
            <label for="lName">Last Name</label>
        </div>
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
        <div class="input-group">
            <i class="fas fa-id-card"></i>
            <input type="text" name="ic" id="ic" placeholder="IC Number" required>
            <label for="ic">IC Number</label>
        </div>
        <div class="input-group">
            <i class="fas fa-calendar"></i>
            <input type="number" name="age" id="age" placeholder="Age" required>
            <label for="age">Age</label>
        </div>
        <div class="input-group">
            <i class="fas fa-school"></i>
            <input type="text" name="schoolName" id="schoolName" placeholder="School Name" required>
            <label for="schoolName">School Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-phone"></i>
            <input type="tel" name="telephone" id="telephone" placeholder="Telephone Number" required>
            <label for="telephone">Telephone Number</label>
        </div>
        <div class="input-group">
            <i class="fas fa-user-friends"></i>
            <input type="text" name="parentName" id="parentName" placeholder="Parent's Name" required>
            <label for="parentName">Parent's Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-home"></i>
            <input type="text" name="address" id="address" placeholder="Address" required>
            <label for="address">Address</label>
        </div>
        <div class="input-group">
            <i class="fas fa-image"></i>
            <input type="file" name="pp" id="pp">
            <label for="pp">Profile Picture</label>
        </div>
        <input type="submit" class="btn" value="Sign Up" name="signup">
      </form>
      <p class="or">
      
      <div class="links">
        <p>Already Have Account ?</p>
        <a href="!login.php" id="signInButton">
        <button>Sign In</button>
        </a>
    </div>


</body>
</html>