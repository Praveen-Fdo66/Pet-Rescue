<?php
session_start();
if (!isset($_SESSION['user_name']) || $_SESSION['is_admin'] != 1){
    header("Location: login.php");
    exit();
}

$connection = new mysqli("localhost", "root", "", "pet_rescue");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_name = $_GET['user_name'];


$sql = "DELETE FROM users WHERE user_name=?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $user_name);
$stmt->execute();

header("Location: admin_dashboard.php");
exit();

$stmt->close();
$connection->close();
?>
