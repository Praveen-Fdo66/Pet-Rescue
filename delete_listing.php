<?php
$conn = new mysqli("localhost", "root", "", "pet_rescue");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM lost_found_pets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_dashboard.php"); // Adjust filename if different
    exit();
}
?>
