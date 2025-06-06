<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_name'])) {
    die("You must be logged in to edit listings.");
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid listing ID.");
}

$stmt = $pdo->prepare("SELECT * FROM lost_found_pets WHERE id = :id AND user_name = :user_name");
$stmt->execute([':id' => $id, ':user_name' => $_SESSION['user_name']]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    die("Listing not found or you don't have permission.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $pet_name = trim($_POST['pet_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $contact_info = trim($_POST['contact_info'] ?? '');
    $image_path = $listing['image_path'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['image']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            die("Only JPG, PNG, and GIF images are allowed.");
        }

        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = uniqid() . "-" . basename($_FILES['image']['name']);
        $filePath = $uploadDir . preg_replace("/[^a-zA-Z0-9\.-]/", "-", $fileName);
        move_uploaded_file($_FILES['image']['tmp_name'], $filePath);

        if ($image_path && file_exists($image_path)) unlink($image_path);
        $image_path = $filePath;
    }

    $update = $pdo->prepare("UPDATE lost_found_pets SET type = :type, pet_name = :pet_name, description = :description, location = :location, contact_info = :contact_info, image_path = :image_path WHERE id = :id AND user_name = :user_name");

    $update->execute([
        ':type' => $type,
        ':pet_name' => $pet_name ?: null,
        ':description' => $description,
        ':location' => $location,
        ':contact_info' => $contact_info,
        ':image_path' => $image_path,
        ':id' => $id,
        ':user_name' => $_SESSION['user_name']
    ]);

    header("Location: dashboard.php?message=Listing+updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Lost/Found Listing</title>
    <link rel="stylesheet" href="css/edit_lostfound.css" />
</head>
<body>
<div class="container">
    <h2>Edit Lost/Found Listing</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="type">Type</label>
        <select name="type" id="type" required>
            <option value="lost" <?= $listing['type'] === 'lost' ? 'selected' : '' ?>>Lost</option>
            <option value="found" <?= $listing['type'] === 'found' ? 'selected' : '' ?>>Found</option>
        </select>

        <label for="pet_name">Pet Name</label>
        <input type="text" name="pet_name" id="pet_name" value="<?= htmlspecialchars($listing['pet_name'] ?? '') ?>" placeholder="Enter pet name" />

        <label for="description">Description <span class="required">*</span></label>
        <textarea name="description" id="description" required><?= htmlspecialchars($listing['description']) ?></textarea>

        <label for="location">Location <span class="required">*</span></label>
        <input type="text" name="location" id="location" value="<?= htmlspecialchars($listing['location']) ?>" required />

        <label for="contact_info">Contact Info <span class="required">*</span></label>
        <input type="text" name="contact_info" id="contact_info" value="<?= htmlspecialchars($listing['contact_info']) ?>" required />

        <label for="image">Image</label>
        <?php if (!empty($listing['image_path'])): ?>
            <img src="<?= htmlspecialchars($listing['image_path']) ?>" alt="Current Image" class="image-preview" />
        <?php endif; ?>
        <input type="file" name="image" id="image" accept="image/*" />

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Listing</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
