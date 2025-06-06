<?php
session_start();
$username = $_SESSION['user_name'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWS - Home</title>
    <link rel="stylesheet" href="css/index.css">
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
<li>
  <a href="http://localhost:3000/?username=<?= urlencode($username) ?>" target="_blank">
    Pet Chat Feature
  </a>
</li>                <li><a href="help.php">Help</a></li>
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

    
    <section class="section-1">
        <div class="text">
            <h2>Find Your Perfect Pet!</h2>
            <p>Adopt a loving pet and give them a forever home!When you adopt, you're not just bringing a pet into your home; you're giving them a chance to be part of a family. Behind every wagging tail and hopeful purr is a story of resilience, waiting for a happy ending.
            Imagine the joy of coming home to a furry friend who’s been dreaming of a family like yours. Adopt, and make the world a little kinder<br>Your perfect pet is waiting for you to discover them!<br><br>&#128062;one paw at a time&#128062;</p>
        </div>
        <div class="image">
            <img src="images/home_1.jpg" alt="Pet Image">
        </div>
    </section>

    
    <section class="section-2">
        <h2>Adopt Me!</h2>
        <div class="pet-listings">
            <?php include 'fetch_pets.php'; ?>
        </div>
    </section>

    
    <section class="section-3">
        <div class="image">
            <img src="images/home_2.jpg" alt="Pet Image">
        </div>
        <div class="text">
            <h2>Why Adopt?</h2>
            <p>Adopting a pet is a life-changing decision that not only saves the life of a deserving animal but also brings endless joy, companionship, and love to your home. By choosing adoption, you're helping reduce animal homelessness, supporting rescue efforts, and giving a pet a second chance at happiness.When you adopt, you often gain a loyal friend who is eager to show gratitude and affection. Additionally, adopting a pet can have profound emotional benefits, including reducing stress and improving mental health.By choosing to adopt, you're also inspiring others to consider rescue as a viable option, creating a ripple effect of kindness in your community.

<br><br>So, why not open your heart and home? Your perfect furry companion is waiting to fill your life with love and joy!

</p>
        </div>
    </section>


    <footer>
        <p>&copy; 2024 PAWS. All Rights Reserved.</p>
    </footer>

    
    <div id="adopt-form-popup" class="popup-form">
        <div class="form-container">
            <span id="close-popup" class="close-btn">&times;</span>
            <h2>Adoption Form</h2>
            <form id="adoptForm" action="submit_adopt.php" method="POST">
                <input type="hidden" id="pet_id" name="pet_id" />
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required />
                <label for="district">District:</label>
                <input type="text" id="district" name="district" required />
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required />
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required />
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    

    <script src="js/index.js"></script>
</body>
</html>
