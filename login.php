<?php
session_start();

if (isset($_SESSION['user_name'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PAWS - Login</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
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
                    <a href="register.php" class="btn">Register</a>
            </div>
        </nav>
    </header>
    <main>
    <h1><br>Login to access your rescue profile and 
        <br>help animals, find their forever homes!</h1>  
    <div class="box">
        <img src="images/user.png" class="user">
        <h2>Login Here</h2>

        <form action="login_process.php" method="POST"  >
            <p>Username</p>
            <input type="text" name="user_name" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
           <button type="submit">Login</button>
            <br></br>
            <p>If you don't have an account</p>
            <a href="register.php">Register</a>
        </form>
    </div>
    </main>
 <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>
</body>
</html>
