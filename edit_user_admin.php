<?php
session_start();
if (!isset($_SESSION['user_name']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$connection = new mysqli("localhost", "root", "", "pet_rescue");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : null;

if (!$user_name) {
    die("No user name provided.");
}

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
    $is_admin=$_POST['is_admin'];

    $update_sql = "UPDATE users SET full_name = ?, email = ?, is_admin = ? WHERE user_name = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("ssis", $full_name, $email, $is_admin, $user_name);

    if ($update_stmt->execute()) {
        
        if (!empty($new_password)) {
            $password_sql = "UPDATE users SET password = ? WHERE user_name = ?";
            $password_stmt = $connection->prepare($password_sql);
            $password_stmt->bind_param("ss", $new_password, $user_name);
            $password_stmt->execute();
        }

        header("Location: admin_dashboard.php?status=success&message=User+was+updated+succesfully!");
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
    <title>PAWS - Admin Edit User</title>
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
                <li><a href="dashboard.php">My Dashboard</a></li>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            </ul>
             <div class="auth-buttons">
             	<a href="logout.php" class="btn">Logout</a>
             </div>
    
    </nav>
    </header>

<main class="container">
    <h1 class="title">Edit User: <?php echo $user_name; ?></h1>
    <form action="edit_user_admin.php?user_name=<?php echo $user_name; ?>" method="POST">
        <div class="form-group">
            <label for="full_name" class="label">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required class="input">
        </div>
        <div class="form-group">
            <label for="email" class="label">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required class="input">
        </div>
        <div class="form-group">
            <label for="new_password" class="label">New Password (leave blank to keep current password)</label>
            <input type="password" id="new_password" name="new_password" class="input">
        </div>
        <div class="form-group">
          <label class="label">   
            Role: 
            <select name="is_admin" class="select">
               <option value="0"<?php echo $user['is_admin'] ==0 ? 'selected' : ''; ?>>User</option>
               <option value="1"<?php echo $user['is_admin'] ==1 ? 'selected' : ''; ?>>Admin</option>
            </select>
         </label>
         
        </div>
        <div class="button-container">
            <button type="submit" class="submit-button">
                Update User
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
