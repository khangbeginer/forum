<?php
// Kết nối cơ sở dữ liệu
include 'module_functions.php'; // Assume you have this file to connect to the database
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: home.php");
    exit();
}
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validate module ID
    $moduleId = intval($_POST['module_id']);
    if ($moduleId <= 0) {
        echo "Invalid module ID.";
        exit; // Stop execution if module ID is invalid
    }

    // Attempt to delete the module
    $success = deleteModule($db, $moduleId);
    if ($success) {
        echo "Module deleted successfully.";
    } else {
        echo "Failed to delete module.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Module</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Optional navigation bar -->
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
    <div class="container mt-4">
        <h2>Delete Module</h2>
        <form action="delete_module.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="module_id">Module ID</label>
                <select name="module_id" id="module_id" class="form-select" required>
                    <option value="">Select Module</option>
                    <?php
                    $moduleNames = getModuleNames($db);
                    foreach ($moduleNames as $moduleId => $moduleName) {
                        echo "<option value='" . $moduleId . "'>" . $moduleName . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-danger">Delete Module</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
