<?php
session_start();

if (isset($_SESSION['user_name'])) {
    header("Location: dashboard.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Register</title>
    <link rel="stylesheet" type="text/css" href="css/register.css">
    <link rel="stylesheet" type="text/css" href="css/header_footer.css">
</head>
<body>
     <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <script>
            alert("<?php echo $_GET['message']; ?>");
        </script>
    <?php endif; ?>
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
            </ul>
            <div class="auth-buttons">
                    <a href="login.php" class="btn">Log In</a>     
            </div>
        </nav>
    </header>
    
    <main>
    <div class="registration-form">
        <h1>Registration Form</h1>
        <form action="register_process.php" method="post">
            <p>Full Name:</p>
            <input type="text" name="full_name" placeholder="Full Name" required>
            
            <p>User Name:</p>
            <input type="text" name="user_name" placeholder="User Name" required>
            
            <p>Email:</p>
            <input type="email" name="email" placeholder="Email" required>
            
            <p>Password:</p>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit">Register</button>
        </form>
    </div>
   </main>
       <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>
</body>
</html>
