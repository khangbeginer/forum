<?php
// Include the file that contains the editProfile function
include("module_functions.php");
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['users']['userid'];
// Call the editProfile function when the form is submitted
editProfile();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">Student Q&A</a> <!-- Liên kết trang chủ -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="post_question.php">Post A Question</a> 
                    <li class="nav-item">
                        <a class="nav-link" href="contact_admin.php">Contact Admin</a> 
                    <li class="nav-item">
                        <a class="nav-link" href="user-profile.php">Profile</a>
                    </li>
                    <?php if (isAdmin()): ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_module.php">Create Module</a> 
                    <li class="nav-item">
                        <a class="nav-link" href="delete_module.php">Delete Module</a> 
                    <li class="nav-item">
                        <a class="nav-link" href="edit_module.php">Edit Module</a> 
                    <li class="nav-item">
                        <a class="nav-link" href="admin_panel.php">Admin Panel</a> 
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container mt-5">
        <h1>Edit Profile</h1>

        <!-- Form to edit user profile -->
        <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <?php if (isset($user['email'])): ?>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control">
                <?php else: ?>
                    <input type="email" id="email" name="email" value="" class="form-control">
                <?php endif; ?>
            </div>

            <!-- About input -->
            <div class="mb-3">
                <label for="about" class="form-label">About:</label>
                <?php if (isset($user['about'])): ?>
                    <textarea id="about" name="about" class="form-control"><?php echo htmlspecialchars($user['about']); ?></textarea>
                <?php else: ?>
                    <textarea id="about" name="about" class="form-control"></textarea>
                <?php endif; ?>
            </div>
            <!-- Profile picture input -->
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png" class="form-control">
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>