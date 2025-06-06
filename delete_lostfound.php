<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_name'])) {
    die("You must be logged in to delete listings.");
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid ID.");
}

// Fetch the listing to delete (for ownership and image deletion)
$stmt = $pdo->prepare("SELECT image_path FROM lost_found_pets WHERE id = :id AND user_name = :user_name");
$stmt->execute([':id' => $id, ':user_name' => $_SESSION['user_name']]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    die("Listing not found or permission denied.");
}

// Delete image file if exists
if ($listing['image_path'] && file_exists($listing['image_path'])) {
    unlink($listing['image_path']);
}

// Delete the record
$delete = $pdo->prepare("DELETE FROM lost_found_pets WHERE id = :id AND user_name = :user_name");
$delete->execute([':id' => $id, ':user_name' => $_SESSION['user_name']]);

header("Location: dashboard.php?message=Listing+deleted");
exit();
?>
