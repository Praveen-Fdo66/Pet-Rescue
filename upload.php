<?php
session_start(); // Start session to access logged-in user

require 'db.php';  // Include DB connection

$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $type = $_POST['type'] ?? '';
    $pet_name = trim($_POST['pet_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $contact_info = trim($_POST['contact_info'] ?? '');

    // Get logged-in username
$user_name = $_SESSION['user_name'] ?? null;
if (!$user_name) {
    die("You must be logged in to submit a report.");
}


    // Validation
    if (!$type || !$description || !$location || !$contact_info) {
        die("Please fill all required fields.");
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileType = $_FILES['image']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Only JPG, PNG, and GIF files are allowed.");
        }

        $newFileName = uniqid() . "-" . preg_replace("/[^a-zA-Z0-9\.]/", "-", $fileName);
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            die("Error uploading the image.");
        }
    } else {
        die("Image upload failed.");
    }

    // Insert into database
    $sql = "INSERT INTO lost_found_pets (type, pet_name, description, location, contact_info, image_path, user_name) 
            VALUES (:type, :pet_name, :description, :location, :contact_info, :image_path, :user_name)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':type' => $type,
        ':pet_name' => $pet_name ?: null,
        ':description' => $description,
        ':location' => $location,
        ':contact_info' => $contact_info,
        ':image_path' => $destPath,
        ':user_name' => $user_name
    ]);

    header("Location: lostfound_list.php?success=1");
    exit();
} else {
    die("Invalid request method.");
}
