<?php
require 'db.php';

$typeFilter = $_GET['type'] ?? 'all';

$sql = "SELECT * FROM lost_found_pets";
if ($typeFilter === 'lost' || $typeFilter === 'found') {
    $sql .= " WHERE type = :type";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
if ($typeFilter === 'lost' || $typeFilter === 'found') {
    $stmt->execute(['type' => $typeFilter]);
} else {
    $stmt->execute();
}
$entries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lost & Found Pets List</title>
    <link rel="stylesheet" href="css/lostfound_list.css" />
</head>
<body>

<a href="dashboard.php" class="back-dashboard-btn">← Back to Dashboard</a>


<div class="container">
    <h1>Lost & Found Pets</h1>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-msg">Your report was submitted successfully!</p>
    <?php endif; ?>

    <!-- Filter Dropdown -->
    <form method="GET" class="filter-form">
        <label for="type">Filter by Type:</label>
        <select name="type" id="type" onchange="this.form.submit()">
            <option value="all" <?= $typeFilter === 'all' ? 'selected' : '' ?>>All</option>
            <option value="lost" <?= $typeFilter === 'lost' ? 'selected' : '' ?>>Lost</option>
            <option value="found" <?= $typeFilter === 'found' ? 'selected' : '' ?>>Found</option>
        </select>
    </form>

    <?php if (count($entries) === 0): ?>
        <p>No <?= htmlspecialchars($typeFilter) ?> pets reported.</p>
    <?php else: ?>

        <?php if ($typeFilter === 'lost' || $typeFilter === 'all'): ?>
            <h2 class="section-title">Lost Pets</h2>
            <div class="pet-list">
                <?php foreach ($entries as $entry): ?>
                    <?php if ($entry['type'] === 'lost'): ?>
                        <div class="pet-card lost">
                            <img src="<?= htmlspecialchars($entry['image_path']) ?>" alt="Pet Image" />
                            <div class="pet-info">
                                <h2><?= htmlspecialchars($entry['pet_name'] ?: "Unnamed Pet") ?> (Lost)</h2>
                                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($entry['description'])) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($entry['location']) ?></p>
                                <p><strong>Contact:</strong> <?= htmlspecialchars($entry['contact_info']) ?></p>
                                <p class="date">Reported on: <?= htmlspecialchars($entry['created_at']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($typeFilter === 'found' || $typeFilter === 'all'): ?>
            <h2 class="section-title">Found Pets</h2>
            <div class="pet-list">
                <?php foreach ($entries as $entry): ?>
                    <?php if ($entry['type'] === 'found'): ?>
                        <div class="pet-card found">
                            <img src="<?= htmlspecialchars($entry['image_path']) ?>" alt="Pet Image" />
                            <div class="pet-info">
                                <h2><?= htmlspecialchars($entry['pet_name'] ?: "Unnamed Pet") ?> (Found)</h2>
                                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($entry['description'])) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($entry['location']) ?></p>
                                <p><strong>Contact:</strong> <?= htmlspecialchars($entry['contact_info']) ?></p>
                                <p class="date">Reported on: <?= htmlspecialchars($entry['created_at']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <p><a href="lostfound_form.php">Report a Lost or Found Pet</a></p>
</div>
</body>
</html>
