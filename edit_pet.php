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

$pet_id = isset($_GET['pet_id']) ? $_GET['pet_id'] : null;
$user_name = $_SESSION['user_name'];

if (!$pet_id) {
    die("No pet ID provided.");
}

$pet_sql = "SELECT * FROM pet WHERE pet_id = ? AND user_name = ?";
$pet_stmt = $connection->prepare($pet_sql);
$pet_stmt->bind_param("is", $pet_id, $user_name);
$pet_stmt->execute();
$pet_result = $pet_stmt->get_result();

if ($pet_result->num_rows === 0) {
    die("Pet not found or you don't have permission to edit this pet.");
}

$pet = $pet_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $pet_type = $_POST['pet_type'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $phone_number = $_POST['phone_number'];
    $description = $_POST['description'];
    $is_adopted = isset($_POST['is_adopted']) ? 1 : 0;

    $update_sql = "UPDATE pet SET name = ?, pet_type = ?, age = ?, gender = ?, district = ?, city = ?, phone_number = ?, description = ?, is_adopted = ? WHERE pet_id = ? AND user_name = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("ssisssisiis", $name, $pet_type, $age, $gender, $district, $city, $phone_number, $description, $is_adopted, $pet_id, $user_name);

    if ($update_stmt->execute()) {
        header("Location: dashboard.php?status=success&message=Pet+was+updated+successfully!");
        exit();
    } else {
        echo "Error updating pet details: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Edit Pet</title>
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
    <main class="container">
   <h1 class="title">Edit Pet: <?php echo $pet['name']; ?></h1>

    <form action="edit_pet.php?pet_id=<?php echo $pet_id; ?>" method="POST">
        <div class="form-group">
            <label for="name" class="label">Name</label>
            <input type="text" id="name" name="name" value="<?php echo $pet['name']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="pet_type" class="label">Type</label>
            <input type="text" id="pet_type" name="pet_type" value="<?php echo $pet['pet_type']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="age" class="label">Age</label>
            <input type="number" id="age" name="age" value="<?php echo $pet['age']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="gender" class="label">Gender</label>
            <input type="text" id="gender" name="gender" value="<?php echo $pet['gender']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="district" class="label">District</label>
            <input type="text" id="district" name="district" value="<?php echo $pet['district']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="city" class="label">City</label>
            <input type="text" id="city" name="city" value="<?php echo $pet['city']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="phone_number" class="label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo $pet['phone_number']; ?>" required class="input">
        </div>

        <div class="form-group">
            <label for="description" class="label">Description</label>
            <textarea id="description" name="description" required class="textarea"><?php echo $pet['description']; ?></textarea>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_adopted" <?php echo $pet['is_adopted'] ? 'checked' : ''; ?> class="checkbox">
                Is Adopted
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="button">
                Update Pet
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
$pet_stmt->close();
$connection->close();
?>
