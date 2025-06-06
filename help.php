<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Help </title>
    <link rel="stylesheet" type="text/css" href="css/header_footer.css">
    <link rel="stylesheet" type="text/css" href="css/info_pages.css">
</head>
<body>
    <header>
       <div class="logo">
            <a href="index.php">
            	<img src="images/petlogo.png" alt="PAWS Logo">
            </a>
        </div>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="help.php">Help</a></li>
                <li>
                 <?php if (isset($_SESSION['user_name'])){ 
                 	echo '<a href="dashboard.php">My Dashboard</a>';
                 }?>	
                </li>
                <li>
                 <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
                    echo '<a href="admin_dashboard.php">Admin Dashboard</a>';
                 } ?>
                </li>
               
            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_name'])): ?>
                    <a href="logout.php" class="btn">Logout</a>
                <?php else: ?>
                    <a href="register.php" class="btn">Register</a>
                    <a href="login.php" class="btn">Log In</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <div class="container">
        <h1>PAWS Pet Rescue and Rehome Management System - Help Guide</h1>

        <div class="section">
            <h2>Overview</h2>
            <p>Welcome to the PAWS Pet Rescue and Rehome Management System! Our system is designed to help you manage and coordinate the rescue, care, and rehoming of pets. Below is a guide on how to use each feature of the system effectively.</p>
        </div>

        <div class="section">
            <h2>1. User Registration and Login</h2>
            <h4><strong>New User Registration:</strong></h4>
            <ul>
                <li>Click on the <strong>Register</strong> button on the top right corner of the homepage.</li>
                <li>Fill in the required fields. (i.e., Full Name, Username, Email, and Password)</li>
                <li>Make sure to use a secure password.</li>
                <li>Click <strong>Register</strong> to complete the registration process.</li>
                <li>If you are prompted with a message that the username or email is already taken, please redo the registration with an unique username and email. </li>
                <li>Now you'll be redirected to the login page.So you can use the previously created username and password to login.</li> 
            </ul>

            <h4><strong>Login:</strong></h4>
            <ul>
                <li>Click on the <strong>Login</strong> button the top right corner of the homepage.</li>
                <li>Enter your registered username and password.</li>
                <li>Click <strong>Login</strong>.</li>
            </ul>
        </div><hr>

       
        <div class="section">
            <h2>2. Dashboard</h2>
            <p>The dashboard provides an overview of key activities and actions you can take, such as managing pets and tracking adoptions</p>
            <p>At the top of the dashboard, you'll find each pet you've added displayed in individual boxes, showcasing their details.<p><br>
            <h4><strong>Adding a new pet:</strong></h4>
            <p>To add a new pet to the system.</p>
            <ul>
                <li>From the dashboard, click on <strong>Add New Pet</strong>located at the top right corner.</li>
                <li>Fill in the pet's details such as Name, Age, location, and description.</li>
                <li>Upload a picture of the pet.</li>
                <li>Click <strong>Add Pet</strong> to save the new entry.</li>
            </ul>
            <h4><strong>Edit the pet's information and Delete the pet:</strong></h4>
            <ul>
                <li> To edit pets details click the Edit button located in the bottom right corner of the pet's profile.</li>
                <li> To remove a pet from the dashboard, you can click the Delete button, also found in the bottom right corner of the pet's profile box.</li>
            </ul>
            <h4><strong>Adoption requests and confirmed adoption requests:</strong></h4>
                <ul>
                
                <li>When someone request to adopt a pet the requested person's details is shown in the bottom of the page. </li>
                <li>You can <strong>Confirm or Delete</strong> the the requested person's detais clicking the Confirm and Delete buttons.</li>
                <li>Confirmed adoption requests can also be seen in the bottom of the page and you can delete the confirmed adoption request after the pet is successfully adopted.</li>
             </ul>
        </div><hr>


        <div class="section">
            <h2>3. Home</h2>
            <p>This is the place you can see the pets information that other users uploaded.</p>
            <h4><strong>Adopt</strong></h4>
            <ul>
                <li>If you want to find a pet to adopt you can click the <strong>Adopt</strong> button under each pet.</li>
                <li>You can fill the Full name, city, District and the phone number and click Submit to send the adoption request.</li>
            </ul>
        </div>
        </div>
<footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
</footer>   
</body>
</html>
