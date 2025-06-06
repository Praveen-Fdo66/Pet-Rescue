<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - About Us</title>
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
        <h1>About Us</h1>

  
        <div class="section mission">
            <h2>Our Mission</h2>
            <p>At PAWS as a pioneering pet rescue and rehome management system, our mission is to create a compassionate world where every pet finds a loving home. We are dedicated to connecting animals in need with caring adopters through a seamless, user-friendly platform. Our goal is to empower rescuers, foster caregivers, and adopters alike by simplifying the rescue and rehoming process, ensuring that no pet is left behind and that each one receives the care and attention they deserve.</p>
        </div>

    
        <div class="section">
            <h2>Our History</h2>
            <p>PAWS was founded in 2024 with the mission of fulfilling the need for a reliable online platform dedicated to helping pets in need. Since our inception, we've helped rehome hundreds of pets and continue to grow with the support of our amazing community.</p>
        </div>
        </div>



<footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
</footer>  
</body>
</html>
