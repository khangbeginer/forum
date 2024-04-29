<?php
include 'module_functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get the question ID from the URL
$questionId = isset($_GET['questionid']) ? intval($_GET['questionid']) : null;

if (!$questionId) {
    echo "Invalid question ID.";
    exit();
}

// Fetch question information
$query = "SELECT title, content FROM questions WHERE questionid = :questionid";
$stmt = $db->prepare($query);
$stmt->bindParam(':questionid', $questionId, PDO::PARAM_INT);
$stmt->execute();

$questionInfo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$questionInfo) {
    echo "Question not found.";
    exit();
}

// Handle form submission to update the question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTitle = $_POST['title'];
    $newContent = $_POST['content'];

    $updateQuery = "UPDATE questions SET title = :title, content = :content WHERE questionid = :questionid";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':title', $newTitle, PDO::PARAM_STR);
    $updateStmt->bindParam(':content', $newContent, PDO::PARAM_STR);
    $updateStmt->bindParam(':questionid', $questionId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo "Question updated successfully.";
    } else {
        echo "Failed to update question.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">Student Q&A</a> <!-- Home link -->
            <ul class="navbar-nav ms-auto">
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="post_question.php">Post A Question</a> 
                <li class="nav-item">
                    <a class="nav-link" href="contact_admin.php">Contact Admin</a> 
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
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger">Logout</a> <!-- Red logout button -->
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Edit Question</h1>
        
        <!-- Form to edit the question -->
        <form action="" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($questionInfo['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($questionInfo['content']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Question</button>
            <a href="user-profile.php?userid=<?php echo $loggedInUserId; ?>" class="btn btn-secondary">Back to Profile</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
