<?php
// Include the function file
include("module_functions.php");
// Ensure the admin is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    echo "You must log in first.";
}
if (!isAdmin()) {
    header("Location: home.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submissions based on the action type
    if (isset($_POST['action'])) {
        echo "Action value: " . $_POST['action'] . "\n";
        $userId = $_POST['userId'];
        if ($_POST['action'] == 'change_username') {
            $newUsername = $_POST['newUsername'];
            changeUsername($db, $userId, $newUsername);
        } elseif ($_POST['action'] == 'delete_user') {
            deleteUser($db, $userId);
        } elseif ($_POST['action'] == 'delete_picture') {
            // Check the current value of the profile picture in the database
            $currentProfilePicture = getUserProfilePicture($db, $userId);
            echo "Current profile picture: " . $currentProfilePicture;
            
            // Delete the profile picture
            deleteProfilePicture($db, $userId);
            
            // Check the profile picture in the database after deletion
            $updatedProfilePicture = getUserProfilePicture($db, $userId);
            echo "Updated profile picture: " . $updatedProfilePicture;
            
            // Compare the values and confirm the deletion
            if ($updatedProfilePicture == null) {
                echo "Profile picture deletion successful.";
            } else {
                echo "Profile picture deletion failed.";
            }
        }
    }
}


// Fetch all users to display in the admin panel
$users = getAllUsers($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
    <div class="container mt-5">
        <h1>Admin Panel</h1>
         <!-- Add the button to go back to home.php -->
        <a href="home.php" class="btn btn-secondary mb-3">Go Back to Home</a>
        <!-- Form to change username -->
        <h2>Change Username</h2>
        <form action="" method="POST">
            <input type="hidden" name="action" value="change_username">
            <div class="mb-3">
                <label for="userId" class="form-label">User ID:</label>
                <input type="text" id="userId" name="userId" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="newUsername" class="form-label">New Username:</label>
                <input type="text" id="newUsername" name="newUsername" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Username</button>
        </form>

        <!-- Form to delete user -->
        <h2>Delete User</h2>
        <form action="" method="POST">
            <input type="hidden" name="action" value="delete_user">
            <div class="mb-3">
                <label for="userId" class="form-label">User ID:</label>
                <input type="text" id="userId" name="userId" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete User</button>
        </form>
        <!-- Display all users -->
        <h2>All Users</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Iterate through the users and print them in table rows
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['userid']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                    
                    // Display profile picture
                    $profilePicture = htmlspecialchars($user['profile_picture']);
                    if ($profilePicture) {
                        echo "<td><img src='{$profilePicture}' alt='Profile Picture' style='width: 100px; height: 100px;'></td>";
                    } else {
                        echo "<td>No picture</td>";
                    }
                    
                    // Add delete button for the profile picture
                    echo "<td>";
                    if ($profilePicture) {
                        echo "<form action='admin_panel.php' method='POST'>";
                        echo "<input type='hidden' name='userId' value='" . htmlspecialchars($user['userid']) . "'>";
                        echo "<input type='hidden' name='action' value='delete_picture'>"; // Include hidden input for action type
                        echo "<button type='submit' class='btn btn-danger'>Delete Picture</button>";
                        echo "</form>";
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
