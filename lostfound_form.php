<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lost & Found Pet Submission</title>
    <link rel="stylesheet" href="css/lostfound_form.css" />

</head>
<body>

<a href="dashboard.php" class="back-dashboard-btn">← Back to Dashboard</a>



    <div class="form-container">
        <h1>🐾 Report a Lost or Found Pet</h1>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type" required>
                    <option value="">Select</option>
                    <option value="lost">Lost</option>
                    <option value="found">Found</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pet_name">Pet Name (optional)</label>
                <input type="text" name="pet_name" id="pet_name" placeholder="Pet's name">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Describe the pet..." required></textarea>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" placeholder="Where was the pet lost/found?" required>
            </div>

            <div class="form-group">
                <label for="contact_info">Your Contact Info</label>
                <input type="text" name="contact_info" id="contact_info" placeholder="Email or phone number" required>
            </div>

            <div class="form-group">
                <label for="image">Upload an Image</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>

            <button type="submit" class="submit-btn">📤 Submit Report</button>
        </form>
    </div>
</body>
</html>


