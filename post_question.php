<?php
// Include required functions and check if the user is logged in
include("module_functions.php");
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$errorMessage = null;

if (isset($_POST["submit_question"])) {
    // Retrieve form data
    $module_id = $_POST['module'] ?? null; // Ensure it has a value or is null
    $title = $_POST['title'] ?? null;
    $content = $_POST['description'] ?? null;
    $userid = $_SESSION['users']['userid'];

    // Get the question picture from the uploaded file
    $questionPicture = isset($_FILES['question_picture']) ? $_FILES['question_picture'] : null;
    
    if ($module_id && $title && $content) { // Ensure essential inputs are provided
        try {
            $question_id = saveQuestionWithPicture($db, $userid, $module_id, $title, $content, $questionPicture);

            // Redirect to home.php if the question was successfully saved
            if ($question_id) {
                header("Location: home.php");
                exit();
            }
        } catch (Exception $e) {
            // Handle the error gracefully
            $errorMessage = "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $errorMessage = "Please fill in all required fields."; // Error message if any field is missing
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Question</title>
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
            <h1>Create New Question</h1>
        </div>
        <div class="content-box container">
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div> <!-- Display error message -->
            <?php endif; ?>
            
            <form method="post" action="" enctype="multipart/form-data">
                <!-- Module selection dropdown -->
                <div class="form-group mb-3">
                    <label for="module" class="form-label">Module</label>
                    <select name="module" id="module" class="form-select" required>
                        <option value="">Select Module</option>
                        <?php
                        $moduleNames = getModuleNames($db);
                        foreach ($moduleNames as $moduleId => $moduleName) {
                            echo "<option value='" . $moduleId . "'>" . $moduleName . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Title input -->
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <!-- Description input -->
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Content</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>

                <!-- File input for question picture -->
                <div class="form-group mb-3">
                    <label for="question_picture" class="form-label">Question Picture</label>
                    <input type="file" id="question_picture" name="question_picture" accept=".jpg, .jpeg, .png, .gif" class="form-control">
                </div>

                <!-- Submit and cancel buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" name="submit_question" class="btn btn-primary">Submit Question</button>
                    <a href="home.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-f0jHWVgMexmIGLwEgozCWEXBqA7IqywGtEoCJylvkLRQkJ403zCLJ0MRAyOoT8tT" crossorigin="anonymous"></script>
</body>

</html>
