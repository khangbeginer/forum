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
if (isset($_POST["submit_module"])) {createmodule();}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Create New Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Additional CSS styles */
        .page-name {
            text-align: center;
            margin-bottom: 20px;
        }

        .content-box {
            max-width: 600px;
            margin: auto;
        }

        .user-profile-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <header>
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
    </header>

    <main>
        <div class="page-name">
            <h1>Create New Module</h1>
        </div>
        <div class="content-box container">
            <form method="post" action="">

                <!-- Module name input -->
                <div class="form-group mb-3">
                    <label for="modulename" class="form-label">Module Name</label>
                    <input type="text" name="modulename" class="form-control" required>
                </div>

                <!-- Description input -->
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

                <!-- Submit and Cancel buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" name="submit_module" class="btn btn-primary">Create Module</button>
                    <a href="home.php" class="btn btn-secondary">Cancel Create Module</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
