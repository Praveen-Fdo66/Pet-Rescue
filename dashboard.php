<?php
session_start();

$username = $_SESSION['user_name'] ?? 'Guest';


require 'db.php'; 

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$connection = new mysqli("localhost", "root", "", "pet_rescue");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_name = $_SESSION['user_name'];


$pet_sql = "SELECT pet_id, name, pet_type, age, gender, district, city, description, photo, is_adopted FROM pet WHERE user_name=?";
$pet_stmt = $connection->prepare($pet_sql);
$pet_stmt->bind_param("s", $user_name);
$pet_stmt->execute();
$pet_result = $pet_stmt->get_result();


$adopt_sql = "SELECT adopt.adopt_id, adopt.pet_id, pet.name AS pet_name, adopt.full_name, adopt.district, adopt.city, adopt.phone_number
              FROM adopt
              JOIN pet ON adopt.pet_id = pet.pet_id
              WHERE pet.user_name = ? AND adopt.is_confirmed = 0";
$adopt_stmt = $connection->prepare($adopt_sql);
$adopt_stmt->bind_param("s", $user_name);
$adopt_stmt->execute();
$adopt_result = $adopt_stmt->get_result();


$confirmed_adopt_sql = "SELECT adopt.adopt_id, adopt.pet_id, pet.name AS pet_name, adopt.full_name, adopt.district, adopt.city, adopt.phone_number
                        FROM adopt
                        JOIN pet ON adopt.pet_id = pet.pet_id
                        WHERE adopt.is_confirmed = 1 AND pet.user_name = ?";
$confirmed_adopt_stmt = $connection->prepare($confirmed_adopt_sql);
$confirmed_adopt_stmt->bind_param("s", $user_name);
$confirmed_adopt_stmt->execute();
$confirmed_adopt_result = $confirmed_adopt_stmt->get_result();



$lostfound_sql = "SELECT lf_id, title, description, district, city, status, photo FROM lostfound WHERE user_name=?";
$lostfound_stmt = $connection->prepare($lostfound_sql);
$lostfound_stmt->bind_param("s", $user_name);
$lostfound_stmt->execute();
$lostfound_result = $lostfound_stmt->get_result();

$user_name = $_SESSION['user_name'] ?? null;

if (!$user_name) {
    die("You must be logged in to view your listings.");
}

// Fetch listings for the logged-in user
$sql = "SELECT * FROM lost_found_pets WHERE user_name = :user_name ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_name' => $user_name]);
$lostfound_result = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
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
                <li><a href="lostfound_list.php">Lost/Found</a></li>
<li>
  <a href="http://localhost:3000/?username=<?= urlencode($username) ?>" target="_blank">
    Pet Chat Feature
  </a>
</li> 

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
    <div class="header">
         <h1 class="page-title">Welcome back, <?php echo $user_name; ?>! &#128075</h1>
        
        <a href="add_pet.php" class="btn-primary">
            Add New Pet
        </a>
        
    </div>
    <div><h1 class="page-title">Your Pets</h1></div>
    
    <div class="pet-grid">
    <?php if ($pet_result->num_rows > 0) {?>
        <?php while ($row = $pet_result->fetch_assoc()) { ?>
            <div class="pet-card">
                <?php if ($row['photo']) { ?>
                    <img src="<?php echo $row['photo']; ?>" alt="Pet Photo" class="pet-image">
                <?php } else { ?>
                    <img src="default.jpg" alt="Pet Photo" class="pet-image">
                <?php } ?>
                <div class="pet-details">
                    <p><strong>Name:</strong> <?php echo $row['name']; ?></p>
                    <p><strong>Type:</strong> <?php echo $row['pet_type']; ?></p>
                    <p><strong>Age:</strong> <?php echo $row['age']; ?></p>
                    <p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
                    <p><strong>District:</strong> <?php echo $row['district']; ?></p>
                    <p><strong>City:</strong> <?php echo $row['city']; ?></p>
                    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                    <p><strong>Status:</strong> 
                        <?php echo ($row['is_adopted'] == 1) ? 'Adopted' : 'Not Adopted'; ?>
                    </p>
                    <div class="pet-actions">
                        <a href="edit_pet.php?pet_id=<?php echo $row['pet_id']; ?>" class="btn-secondary">Edit</a>
                        <a href="delete_pet.php?pet_id=<?php echo $row['pet_id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this pet?');">Delete</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { echo "<p>No pets added.</p>";} ?>
    </div>

    <h2 class="section-title">Adoption Requests for Your Pets</h2>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Pet Name</th>
                <th>Full Name</th>
                <th>District</th>
                <th>City</th>
                <th>Phone Number</th>
                <th>Confirm Request</th>
                <th>Delete Request</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($adopt_row = $adopt_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $adopt_row['pet_name']; ?></td>
                    <td><?php echo $adopt_row['full_name']; ?></td>
                    <td><?php echo $adopt_row['district']; ?></td>
                    <td><?php echo $adopt_row['city']; ?></td>
                    <td><?php echo $adopt_row['phone_number']; ?></td>
                    <td>
                         <a href="confirm_request.php?confirm_adoption=1&pet_id=<?php echo $adopt_row['pet_id'];?>&adopt_id= <?php echo $adopt_row['adopt_id'];?>" class="btn-confirm"> Confirm</a> 
                    </td>
                    <td>
                         <a href="delete_request.php?delete_request=1&adopt_id= <?php echo $adopt_row['adopt_id'];?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this request?');"> Delete</a> 
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <h2 class="section-title">Confirmed Adoption Requests</h2>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Pet Name</th>
                <th>Full Name</th>
                <th>District</th>
                <th>City</th>
                <th>Phone Number</th>
                <th>Delete Request</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($confirmed_row = $confirmed_adopt_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $confirmed_row['pet_name']; ?></td>
                    <td><?php echo $confirmed_row['full_name']; ?></td>
                    <td><?php echo $confirmed_row['district']; ?></td>
                    <td><?php echo $confirmed_row['city']; ?></td>
                    <td><?php echo $confirmed_row['phone_number']; ?></td>
                    <td>
                         <a href="delete_request.php?delete_request=1&adopt_id= <?php echo $confirmed_row['adopt_id'];?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this request?');"> Delete</a> 
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h2 class="section-title">Your Lost & Found Listings</h2>

<!-- Add Lost/Found Button -->
<div style="margin-bottom: 15px;">
    <a href="lostfound_form.php" class="btn-primary">Add Lost/Found Post</a>
</div>



<!-- Lost & Found Listings Table -->
<table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="padding: 10px;">Image</th>
            <th style="padding: 10px;">Type</th>
            <th style="padding: 10px;">Pet Name</th>
            <th style="padding: 10px;">Description</th>
            <th style="padding: 10px;">Location</th>
            <th style="padding: 10px;">Contact Info</th>
            <th style="padding: 10px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($lostfound_result)): ?>
            <?php foreach ($lostfound_result as $lf): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;">
                        <?php if (!empty($lf['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($lf['image_path']); ?>" alt="Image" style="width: 100px; height: auto; border-radius: 8px;">
                        <?php else: ?>
                            <img src="images/default.jpg" alt="Default" style="width: 100px; height: auto; border-radius: 8px;">
                        <?php endif; ?>
                    </td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars(ucfirst($lf['type'])); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($lf['pet_name'] ?? 'N/A'); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($lf['description']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($lf['location']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($lf['contact_info']); ?></td>
                    <td style="padding: 10px;">
                        <a href="edit_lostfound.php?id=<?php echo $lf['id']; ?>" class="btn-secondary" style="margin-right: 5px;">Edit</a>
                        <a href="delete_lostfound.php?id=<?php echo $lf['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this listing?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" style="padding: 15px; text-align: center;">No Lost & Found listings yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>


    
</main>
    <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php 
$pet_stmt->close();
$adopt_stmt->close();
$confirmed_adopt_stmt->close();
$connection->close(); 
?>
