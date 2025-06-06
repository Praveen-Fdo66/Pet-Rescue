<?php
session_start();

$username = $_SESSION['user_name'] ?? 'Guest';

if (!isset($_SESSION['user_name']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$connection = new mysqli("localhost", "root", "", "pet_rescue");

$user_name = $_SESSION['user_name'];

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


$result = $connection->query("SELECT * FROM lost_found_pets ORDER BY created_at DESC");
$user_result = $connection->query("SELECT user_name, full_name, email, is_admin FROM users");
$pet_result = $connection->query("SELECT pet_id, user_name, name, pet_type, age, gender, district, city, phone_number, description, photo, is_adopted FROM pet");
$adopt_result = $connection->query("
    SELECT a.adopt_id, a.pet_id, a.full_name, a.district, a.city, a.phone_number, p.name AS pet_name
    FROM adopt a
    JOIN pet p ON a.pet_id = p.pet_id
    WHERE a.is_confirmed = 0
");
$confirmed_adopt_result = $connection->query("
    SELECT a.adopt_id, a.pet_id, a.full_name, a.district, a.city, a.phone_number, p.name AS pet_name
    FROM adopt a
    JOIN pet p ON a.pet_id = p.pet_id
    WHERE a.is_confirmed = 1  
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/admin_dashboard.css">
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
                <li><a href="lostfound_list.php">Lost/Found</a></li>
<li>
  <a href="http://localhost:3000/?username=<?= urlencode($username) ?>" target="_blank">
    Pet Chat Feature
  </a>
</li> 
                <li><a href="dashboard.php">My Dashboard</a></li>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            </ul>
             <div class="auth-buttons">
             	<a href="logout.php" class="btn">Logout</a>
             </div>
    
    </nav>
    </header>
    <main class="main-container">
    <h1 class="page-title">Welcome back, <?php echo $user_name; ?>! &#128075</h1>
    <h1 class="page-title">Admin Dashboard</h1>

 
    <h2 class="section-title">Manage Users</h2>
    <form action="register_process.php" method="POST">
        <input type="text" name="user_name" placeholder="Username" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="password" placeholder="Password" required>
        <button type="submit" name="add_user" class="btn-primary">Add User</button>
    </form>

    <h3 class="subsection-title">Existing Users:</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Edit User</th>
                <th>Delete User</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $user_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['is_admin'] ? 'Admin' : 'User'; ?></td>
                <td>
                     <a href="edit_user_admin.php?user_name=<?php echo $row['user_name']; ?>" class="link-edit">Edit</a>   
                </td>
                <td>
                     <a href="delete_user.php?user_name=<?php echo $row['user_name']; ?>" class="link-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

   
    <h2 class="section-title">Manage Pets</h2>
    <form action="add_pet_process_admin.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Pet Name" required>
        <input type="text" name="pet_type" placeholder="Pet Type" required>
        <input type="number" name="age" placeholder="Age" required>
        <input type="text" name="gender" placeholder="Gender" required>
        <input type="text" name="district" placeholder="District" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="phone_number" placeholder="Phone Number" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="file" name="photo" required>
        <button type="submit" name="add_pet" class="btn-primary">Add Pet</button>
    </form>

    <h3 class="subsection-title">Existing Pets:</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Pet ID</th>
                <th>Pet Name</th>
                <th>Type</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Location</th>
                <th>Phone</th>
                <th>Description</th>
                <th>Photo</th>
                <th>Status</th>
                <th>Edit Pet</th>
                <th>Delete Pet</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $pet_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['pet_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['pet_type']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['district'] . ', ' . $row['city']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <?php if ($row['photo']) { ?>
                        <img src="<?php echo $row['photo']; ?>" alt="Pet Photo" class="pet-photo">
                    <?php } ?>
                </td>
                <td><?php echo $row['is_adopted'] ? 'Adopted' : 'Not Adopted'; ?></td>
                <td>
                    <a href="edit_pet_admin.php?pet_id=<?php echo $row['pet_id']; ?>" class="link-edit">Edit</a>
                </td>
                <td>
                <a href="delete_pet_admin.php?pet_id=<?php echo $row['pet_id']; ?>" class="link-delete" onclick="return confirm('Are you sure you want to delete this pet?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

  
    <h2 class="section-title">Manage Adoptions</h2>
    <h3 class="subsection-title">Adoption Requests:</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Adoption ID</th>
                <th>Pet ID</th>
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
        <?php while ($row = $adopt_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['adopt_id']; ?></td>
                <td><?php echo $row['pet_id']; ?></td>
                <td><?php echo $row['pet_name']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['district']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td>
                    <a href="confirm_request_admin.php?confirm_adoption=1&pet_id=<?php echo $row['pet_id'];?>&adopt_id= <?php echo $row['adopt_id'];?>" class="link-confirm">Confirm</a>      
                </td>
                <td>
                    <a href="delete_request_admin.php?delete_request=1&adopt_id= <?php echo $row['adopt_id'];?>" class="link-delete" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a> 
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <h3 class="subsection-title">Confirmed Adoption Requests:</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Adoption ID</th>
                <th>Pet ID</th>
                <th>Pet Name</th>
                <th>Full Name</th>
                <th>District</th>
                <th>City</th>
                <th>Phone Number</th>
                <th>Delete Request</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $confirmed_adopt_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['adopt_id']; ?></td>
                <td><?php echo $row['pet_id']; ?></td>
                <td><?php echo $row['pet_name']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['district']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td>
                     <a href="delete_request_admin.php?delete_request=1&adopt_id= <?php echo $row['adopt_id'];?>" class="link-delete" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a> 
                 </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>


    <h2>Lost & Found Pet Reports - Admin Panel</h2>
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Pet Name</th>
          <th>Description</th>
          <th>Location</th>
          <th>Contact Info</th>
          <th>User</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['pet_name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['contact_info']) ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td>
              <?php if (!empty($row['image_path'])): ?>
                <img src="<?= htmlspecialchars($row['image_path']) ?>" class="pet-img" alt="Pet">
              <?php else: ?>
                No image
              <?php endif; ?>
            </td>
            <td>
              <a href="delete_listing.php?id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this listing?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    
</main>
        <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php 
$connection->close();
?>
