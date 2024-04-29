<?php
include 'module_functions.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID from the session
$loggedInUserId = $_SESSION['users']['userid'];

// Determine the `userid` parameter, defaulting to the current user if not provided
$userid = isset($_GET['userid']) ? intval($_GET['userid']) : $loggedInUserId;

// Check if the provided `userid` is valid
if (!$userid || !is_numeric($userid)) {
    echo "Invalid user ID.";
    exit();
}

// Fetch user information
$query = "SELECT * FROM user WHERE userid = :userid";
$stmt = $db->prepare($query);
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();

$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle case where user is not found
if (!$userInfo) {
    echo "User not found.";
    exit();
}

// Fetch user's questions
$queryQuestions = "SELECT questionid, title FROM questions WHERE userid = :userid";
$stmtQuestions = $db->prepare($queryQuestions);
$stmtQuestions->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmtQuestions->execute();

$userQuestions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
    <!-- Main content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8"> <!-- Main content on the left -->
                <div class="card"> <!-- Wrap all content in a card -->
                    <div class="card-header">
                        <h1>User Profile</h1> <!-- Profile heading -->
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <?php displayProfilePicture($db, $userid); ?> <!-- Profile picture -->
                        </div>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($userInfo['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>
                        <p><strong>About:</strong> <?php echo htmlspecialchars($userInfo['about']); ?></p>

                        <!-- Conditionally show the 'Edit Profile' button -->
                        <?php if ($loggedInUserId === $userid): ?>
                            <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4"> <!-- Sidebar on the right -->
                <div class="card"> <!-- Wrap in a card -->
                    <div class="card-header">
                        <h2>Questions asked by <?php echo htmlspecialchars($userInfo['username']); ?>:</h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($userQuestions as $question): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <!-- Question title on the left -->
                                    <a href="replies.php?questionid=<?php echo $question['questionid']; ?>">
                                        <?php echo htmlspecialchars($question['title']); ?>
                                    </a>
                                    <!-- Edit button on the right -->
                                    <a href="edit_question.php?questionid=<?php echo $question['questionid']; ?>" class="btn btn-sm btn-primary">Edit Question</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
