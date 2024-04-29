<?php
include("module_functions.php");

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: home.php");
    exit();
}
if (isset($_POST['submit'])) {
    editModule($db); // Ensure this function operates as expected    
    // Redirect to the same page with GET parameters
    header("Location: edit_module.php");
    exit(); // Ensure that no more code executes after redirection
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Module</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
    <!-- Header -->
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
    <!-- Container for layout -->
    <div class="container mt-4">
        <h1>Edit Module</h1>

        <!-- Form with Bootstrap styling -->
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="moduleid" class="form-label">Select Module</label>
                <select name="moduleid" class="form-select" required>
                    <option value="">Choose...</option>
                    <?php
                    $modules = getModuleNames($db);
                    foreach ($modules as $id => $name) {
                        echo "<option value='$id'>$name</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Please select a module.
                </div>
            </div>

            <div class="mb-3">
                <label for="modulename" class="form-label">Change Module Name</label>
                <input type="text" name="modulename" class="form-control" placeholder="Enter new module name" required>
                <div class="invalid-feedback">
                    Module name is required.
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Enter description"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary">Edit Module</button>
            </div>
        </form>

        <!-- Display GET parameters after redirection -->
        <?php if (isset($_GET['moduleid'])): ?>
            <div class="alert alert-info mt-4">
                <h3>Module Information</h3>
                <p>Module ID: <?php echo $_GET['moduleid']; ?></p>
                <p>Module Name: <?php echo $_GET['modulename']; ?></p>
                <p>Description: <?php echo $_GET['description']; ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS for interactive components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

