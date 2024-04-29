<?php
// Include necessary PHP files and check user authentication
include("module_functions.php");
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Retrieve question ID from the URL parameter and validate it
$questionId = isset($_GET['questionid']) ? intval($_GET['questionid']) : null;
if (!$questionId) {
    echo "Invalid question ID.";
    exit();
}

// Retrieve the question and its replies from the database
$questionData = getQuestionWithReplies($db, $questionId);
if (empty($questionData)) {
    echo "Question not found.";
    exit();
}

// Handle form submission for posting a reply
if (isset($_POST['submit_reply'])) {
    postReply($db, $questionId, $_POST['replycontent']);
    // After submission, redirect the user to avoid form resubmission
    header("Location: replies.php?questionid=$questionId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Replies</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
    <!-- Main content -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Replies</h1>
                <a href="home.php" class="btn btn-secondary btn-sm">Back</a>
            </div>
            <div class="card-body">
                <!-- Display question details -->
                <h2 class="card-title mb-3"><?php echo htmlspecialchars($questionData[0]['title']); ?></h2>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($questionData[0]['content'])); ?></p>
                <!-- Display the question's picture -->
                <div class="mb-3">
                    <img src="<?php echo htmlspecialchars($questionData[0]['picture_path']); ?>" alt="Question Image" style="width: 500px; height: 400px;">
                </div>
                <!-- Display replies -->
                <h3>Replies:</h3>
                <?php foreach ($questionData as $row): ?>
                    <?php if (!empty($row['replycontent'])): ?>
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <!-- Display author name with a link to their profile -->
                                <strong>
                                    <a href="user-profile.php?userid=<?php echo htmlspecialchars($row['reply_author_id']); ?>" class="btn btn-link">
                                        <?php echo htmlspecialchars($row['reply_author'] ?? 'Unknown User'); ?>
                                    </a>
                                </strong>
                                <span><?php echo date('Y-m-d H:i', strtotime($row['timestamp'])); ?></span>
                            </div>
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($row['replycontent'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>


                <!-- Post a reply form -->
                <h3>Post a Reply:</h3>
                <form method="POST" action="">
                    <!-- Hidden field for question ID -->
                    <input type="hidden" name="questionid" value="<?php echo htmlspecialchars($questionId); ?>">

                    <!-- Textarea for reply content -->
                    <div class="mb-3">
                        <label for="replycontent" class="form-label">Your Reply:</label>
                        <textarea name="replycontent" id="replycontent" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" name="submit_reply" class="btn btn-primary">Submit Reply</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
