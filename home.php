<?php
// Include necessary PHP files and check user authentication
include("module_functions.php");

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch module names from the database
$modules = getModuleNames($db); // Function to fetch module names

// Determine the selected module
$selectedModuleId = isset($_GET['moduleid']) ? $_GET['moduleid'] : 'all';

// Fetch questions based on the selected module
if ($selectedModuleId === 'all') {
    $questions = getAllQuestions(); // Fetch all questions
} else {
    $questions = getAllQuestions($selectedModuleId); // Fetch questions for the specific module
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Q&A</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">Student Q&A</a>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="user-profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container mt-5">
        <h1 class="mb-4">Welcome to Student Q&A</h1>
        
        <!-- Module select -->
        <div class="mb-3">
            <label for="moduleSelect">Select Module:</label>
            <select id="moduleSelect" class="form-select" onchange="updateModule(this.value)">
                <option value="all">All Modules</option>
                <?php foreach ($modules as $moduleid => $modulename): ?>
                    <option value="<?php echo $moduleid; ?>" <?php echo $moduleid == $selectedModuleId ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($modulename); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <!-- Display list of questions -->
                <?php if (empty($questions)): ?>
                    <div class="alert alert-warning">No questions found for the selected module.</div>
                <?php else: ?>
                    <?php foreach ($questions as $question): ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <a href="replies.php?questionid=<?php echo urlencode($question['questionid']); ?>">
                                    <?php echo htmlspecialchars($question['title']); ?>
                                </a>
                            </div>
                            <div class="card-body">
                                <p>Module: <?php echo isset($question['modulename']) ? htmlspecialchars($question['modulename']) : 'No topic'; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar with options -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Options</div>
                    <div class="card-body">
                        <a href="post_question.php" class="btn btn-success btn-block mb-3">Post a Question</a>
                        <a href="contact_admin.php" class="btn btn-info btn-block mb-3">Contact Admin</a>
                        <?php if (isAdmin()): ?>
                            <a href="admin_panel.php" class="btn btn-primary btn-block mb-3">Admin Panel</a>
                            <a href="create_module.php" class="btn btn-secondary btn-block mb-3">Create Module</a>
                            <a href="delete_module.php" class="btn btn-danger btn-block mb-3">Delete Module</a>
                            <a href="edit_module.php" class="btn btn-warning btn-block mb-3">Edit Module</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <p class="text-center">&copy; 2024 Student Q&A</p>
        </div>
    </footer>

    <!-- JavaScript to update module -->
    <script>
        function updateModule(moduleid) {
            window.location.href = 'home.php?moduleid=' + moduleid;
        }
    </script>
    
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
