<?php
include('module_functions.php');

// Redirect if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get logged-in user's ID
$loggedInUserId = $_SESSION['users']['userid'];

// Determine if the user is an admin
$is_admin = isAdmin();

// Fetch feedback messages if the user is an admin or the user
$feedback_messages = [];
try {
    // Admin fetches all messages; non-admins fetch only their messages
    $query = $is_admin ? "SELECT messageid, userid, messagesubject, message, response FROM messages" : "SELECT messageid, userid, messagesubject, message, response FROM messages WHERE userid = :userid";
    $stmt = $db->prepare($query);

    // Bind the logged-in user ID if not admin
    if (!$is_admin) {
        $stmt->bindParam(':userid', $loggedInUserId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $feedback_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submission for admin responses
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_admin) {
    // Get the submitted data
    $messageid = $_POST['messageid'];
    $response = $_POST['response'];

    try {
        // Update the response in the database
        $query = "UPDATE messages SET response = ? WHERE messageid = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$response, $messageid]);

        header("Location: contact_admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle form submission for user messages
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$is_admin) {
    $messagesubject = $_POST['messagesubject'];
    $message = $_POST['message'];

    try {
        // Insert the message into the database
        $query = "INSERT INTO messages (userid, messagesubject, message) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$_SESSION['users']['userid'], $messagesubject, $message]);

        header("Location: contact_admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .content-box {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>

<body>
    <header>
        <!-- Navigation bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="home.php">Student Q&A</a> <!-- Home link -->
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
        </nav>
    </header>
    <main>
        <div class="content-box container">
            <?php if ($is_admin): ?>
                <!-- Admin view: Show all feedback messages -->
                <h2>User Feedback Messages</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Message Subject</th>
                            <th>Message</th>
                            <th>Response</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedback_messages as $message): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($message['userid']); ?></td>
                                <td><?php echo htmlspecialchars($message['messagesubject']); ?></td>
                                <td><?php echo htmlspecialchars($message['message']); ?></td>
                                <td><?php echo htmlspecialchars($message['response']); ?></td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="messageid" value="<?php echo $message['messageid']; ?>">
                                        <textarea name="response" class="form-control" rows="2"><?php echo htmlspecialchars($message['response']); ?></textarea>
                                        <button type="submit" class="btn btn-primary mt-2">Reply</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- User view: Show messages with admin replies -->
                <h2>Your Feedback Messages</h2>
                <ul>
                    <?php foreach ($feedback_messages as $message): ?>
                        <li>
                            <strong>Subject:</strong> <?php echo htmlspecialchars($message['messagesubject']); ?>
                            <br>
                            <strong>Message:</strong> <?php echo htmlspecialchars($message['message']); ?>
                            <br>
                            <strong>Admin's Response:</strong> <?php echo htmlspecialchars($message['response']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- User message submission form -->
                <h2>Contact Admin</h2>
                <form method="post" action="">
                    <div class="form-group mb-3">
                        <label for="messagesubject">Subject</label>
                        <input type="text" name="messagesubject" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="message">Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
