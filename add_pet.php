<?php
session_start();
if (!isset($_SESSION['user_name'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Add New Pet</title>
    <link rel="stylesheet" type="text/css" href="css/header_footer.css">
    <link rel="stylesheet" type="text/css" href="css/edit.css">
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
                <li><a href="dashboard.php">My Dashboard</a></li>
                <li><a href="edit_user.php">Edit Profile</a></li>
                <li>
                <?php
                
                if ($_SESSION['is_admin'] == 1){
                	echo '<a href="admin_dashboard.php">Admin Dashboard</a>';
                }?>
                </li>
                
              
        </ul>
        <div class="auth-buttons">
             	<a href="logout.php" class="btn">Logout</a>
        </div>
    </nav>
    </header>
    <main class="main-container">
    <h1 class="page-title">Add New Pet</h1>
    <form action="add_pet_process.php" method="POST" enctype="multipart/form-data" class="pet-form">
        <p class="form-label">Name</p>
        <input type="text" name="name" placeholder="Enter Pet Name" required class="form-input">
        <p class="form-label">Type</p>
        <input type="text" name="pet_type" placeholder="Enter Pet Type" required class="form-input">
        <p class="form-label">Age</p>
        <input type="number" name="age" placeholder="Enter Pet Age" required class="form-input">
        <p class="form-label">Gender</p>
        <input type="text" name="gender" placeholder="Enter Pet Gender" required class="form-input">
        <p class="form-label">District</p>
        <input type="text" name="district" placeholder="Enter District" required class="form-input">
        <p class="form-label">City</p>
        <input type="text" name="city" placeholder="Enter City" required class="form-input">
        <p class="form-label">Phone Number</p>
        <input type="text" name="phone_number" placeholder="Enter Phone Number" required class="form-input">
        <p class="form-label">Description</p>
        <textarea name="description" placeholder="Enter Description" required class="form-textarea"></textarea>
        <p class="form-label">Photo</p>
        <input type="file" name="photo" accept="image/*" required class="form-file-input">
        <input type="submit" value="Add Pet" class="form-submit">
    </form>
</main>
    <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>
</body>
</html>
