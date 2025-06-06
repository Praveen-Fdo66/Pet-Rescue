<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$connection = new mysqli("localhost", "root", "", "pet_rescue");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_name = $_SESSION['user_name'];


$user_sql = "SELECT * FROM users WHERE user_name = ?";
$user_stmt = $connection->prepare($user_sql);
$user_stmt->bind_param("s", $user_name);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    die("User not found.");
}

$user = $user_result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    
    $update_sql = "UPDATE users SET full_name = ?, email = ? WHERE user_name = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("sss", $full_name, $email, $user_name);

    if ($update_stmt->execute()) {
        
        if (!empty($new_password)) {
            $password_sql = "UPDATE users SET password = ? WHERE user_name = ?";
            $password_stmt = $connection->prepare($password_sql);
            $password_stmt->bind_param("ss", $new_password, $user_name);
            $password_stmt->execute();
        }

    
        header("Location: dashboard.php?status=success&message=Profile+was+updated+successfully!");
        exit();
	     
    } else {
        echo "Error updating user details: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Edit Profile</title>
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
    <h1 class="page-title">Edit Profile</h1>
    <form action="edit_user.php" method="POST" class="profile-form">
        <div class="form-group">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required class="form-input">
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required class="form-input">
        </div>
        <div class="form-group">
            <label for="new_password" class="form-label">New Password (leave blank to keep current password)</label>
            <input type="password" id="new_password" name="new_password" class="form-input">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">
                Update Profile
            </button>
        </div>
    </form>
</main>

  <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
$user_stmt->close();
$connection->close();
?>
