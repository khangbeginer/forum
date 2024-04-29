<?php
include("module_functions.php");
if (isset($_POST['submit'])) {
    $filename = $_FILES['img']['name'];
    $filesize = $_FILES['img']['size'];
    $tempname = $_FILES['img']['tmp_name'];

    // Allowed image file extensions
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
    $fileExtension = strtolower($fileExtension);

    if (in_array($fileExtension, $validExtensions)) {
        if ($filesize <= 5000000) {
            // Generate a unique filename for the uploaded image
            $newFilename = uniqid() . '.' . $fileExtension;
            
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($tempname, 'img/' . $newFilename)) {
                // Get the user ID from the POST data
                $userId = $_POST['userid'];
                
                // Update the user's profile picture in the database
                $query = "UPDATE users SET profile_picture = :profile_picture WHERE userid = :userid";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':profile_picture', $newFilename);
                $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
                
                // Execute the update query
                if ($stmt->execute()) {
                    echo "Profile picture updated successfully.";
                } else {
                    echo "Failed to update profile picture.";
                }
            } else {
                echo "Failed to upload the image.";
            }
        } else {
            echo "File too large. Please upload a file smaller than 5MB.";
        }
    } else {
        echo "Invalid file extension. Please upload a .jpg, .jpeg, .png, or .gif image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Image</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">Student Q&A</a>
            <a href="logout.php" class="btn btn-danger ml-auto">Logout</a>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container mt-5">
        <h1>Upload Profile Image</h1>

        <!-- Form to upload profile image -->
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- File input field for uploading profile image -->
            <div class="mb-3">
                <label for="profile_image" class="form-label">Choose Profile Image:</label>
                <input type="file" id="img" name="img" accept=".jpg, .jpeg, .png" class="form-control" required>
            </div>
            <!-- Hidden input to store user ID -->
            <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userId); ?>">
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
