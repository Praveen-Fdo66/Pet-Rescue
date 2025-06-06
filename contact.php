<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Contact Us</title>
    <link rel="stylesheet" type="text/css" href="css/header_footer.css">
    <link rel="stylesheet" type="text/css" href="css/info_pages.css">
    <style>
      

    </style>
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
    <main>
    <div class="container">
        <h1>Contact Us</h1>

        <div class="section">
            <h2>Get in Touch</h2>
            <p>If you have any questions, concerns, or need further assistance regarding pet rescue or rehoming, feel free to reach out to us. We're here to help!</p>
            <p><strong>Email:</strong> support@paws.com</p>
            <p><strong>Phone:</strong> +94 112345678</p>
            <p><strong>Address:</strong> WV26+Q53, Thurstan Road, Colombo 03.</p>
        </div>
    </div>
    </main>
<footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
</footer>        
</body>
</html>
