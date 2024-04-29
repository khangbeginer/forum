    <?php
    include("function.php");
    if(!isLoggedIn()){header("Location: login.php"); echo "You must log in first";}

    function createmodule(){
        global $db;
        $modulename = $_POST['modulename'];
        $description = $_POST['description'];

        if ($modulename) {
            $query = "INSERT INTO modules (modulename, description) VALUES (:modulename, :description)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':modulename', $modulename);
            $stmt->bindParam(':description', $description);
            if ($stmt->execute()) {
                header("Location: home.php"); // Redirect to the homepage after creating the module
                exit();
            } else {
                echo "Create module failed.";
            }
        } else {
            echo "Please fill in module name field.";
        }
    }

    function editModule($db) {
        global $db; // Reference to the global database connection object
        
        // Retrieve the module ID, name, and description from the POST request
        $module_id = $_POST["moduleid"];
        $module_name = $_POST["modulename"];
        $description = $_POST["description"];
        
        // Ensure that both the module name and module ID are provided
        if ($module_name && $module_id) {
            try {
                // Prepare the SQL query to update the module
                $query = "UPDATE modules SET modulename = :modulename, description = :description WHERE moduleid = :moduleid";
                $stmt = $db->prepare($query); // Prepare the statement
    
                // Bind the parameters to the query
                $stmt->bindParam(':moduleid', $module_id);
                $stmt->bindParam(':modulename', $module_name);
                $stmt->bindParam(':description', $description);
                
                // Execute the query to update the module
                $stmt->execute(); 
                
            } catch (PDOException $e) {
                // Rollback the transaction and output the error message in case of an exception
                $db->rollBack();
                echo "Error: " . $e->getMessage();
            }
        } else {
            // If either the module name or module ID is missing, prompt the user to fill in all required fields
            echo "Please fill in all required fields.";
        }
    }
    
    

    function deleteModule($db, $moduleId) {
        try {
            // Begin database transaction
            $db->beginTransaction();
    
            // Get the questions associated with the module
            $getQuestionsQuery = "SELECT questionid FROM questions WHERE moduleid = :moduleId";
            $stmtQuestions = $db->prepare($getQuestionsQuery);
            $stmtQuestions->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
            $stmtQuestions->execute();
            $questions = $stmtQuestions->fetchAll(PDO::FETCH_COLUMN);
    
            // Delete replies associated with the questions
            foreach ($questions as $questionId) {
                $deleteRepliesQuery = "DELETE FROM replies WHERE questionid = :questionId";
                $stmtReplies = $db->prepare($deleteRepliesQuery);
                $stmtReplies->bindParam(':questionId', $questionId, PDO::PARAM_INT);
                $stmtReplies->execute();
            }
    
            // Delete the module itself
            $deleteModuleQuery = "DELETE FROM modules WHERE moduleid = :moduleId";
            $stmtModule = $db->prepare($deleteModuleQuery);
            $stmtModule->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
            $stmtModule->execute();
    
            // Commit the transaction if no errors occurred
            $db->commit();
            return true;
    
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $db->rollBack();
            echo "Error deleting module: " . $e->getMessage();
            return false;
        }
    }

    //////done////////////////
    function getModuleNames($db)
    {
        // Prepare the SQL query to retrieve all module names
        $query = "SELECT moduleid, modulename FROM modules";

        // Prepare the statement
        $stmt = $db->prepare($query);

        // Execute the query
        if ($stmt->execute()) {
            // Fetch all module names as an associative array
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } else {
            // Error executing the query
            return array();
        }
    }

// Function to save the question along with its picture into the databasefunction saveQuestionWithPicture($db, $userId, $moduleId, $title, $content, $questionPicture) {
    function saveQuestionWithPicture($db, $userId, $moduleId, $title, $content, $questionPicture) {
    try {
        // Save the picture and get the relative path
        $picturePath = null; // Default value if no picture is uploaded
        if (isset($questionPicture['name']) && isset($questionPicture['tmp_name'])) {
            $picturePath = saveImageToQuestionPicture($questionPicture);
        }

        // Insert question data into the database using PDO
        $query = "INSERT INTO questions (userid, moduleid, title, content, picture_path) VALUES (:user_id, :module_id, :title, :content, :picture_path)";
        $stmt = $db->prepare($query);

        // Bind the values using named parameters
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':module_id', $moduleId, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':picture_path', $picturePath, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        return $db->lastInsertId(); // Return the ID of the newly inserted question
    } catch (Exception $e) {
        throw new Exception("Error: " . $e->getMessage());
    }
}

// Helper function to save the image to 'question_picture/' directory
function saveImageToQuestionPicture($questionPicture) {
    if (isset($questionPicture['name']) && isset($questionPicture['tmp_name'])) {
        $extension = strtolower(pathinfo($questionPicture['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed extensions for image files
        
        if (in_array($extension, $allowedExtensions)) {
            $newFilename = uniqid() . '.' . $extension;
            $destination = 'question_picture/' . $newFilename;

            if (!file_exists('question_picture/')) {
                mkdir('question_picture/', 0777, true);
            }

            if (move_uploaded_file($questionPicture['tmp_name'], $destination)) {
                return $destination;
            } else {
                throw new Exception("Error moving the uploaded file.");
            }
        } else {
            throw new Exception("Invalid file extension.");
        }
    } else {
        throw new Exception("No file was uploaded.");
    }
}
    //////ongoing////////////////
    function getQuestionWithReplies($db, $questionId) {
        $query = "SELECT 
                    q.title, 
                    q.content, 
                    q.picture_path,
                    r.replycontent, 
                    r.timestamp, 
                    u.username AS reply_author, 
                    u.userid AS reply_author_id
                  FROM questions q
                  LEFT JOIN replies r ON q.questionid = r.questionid
                  LEFT JOIN user u ON r.userid = u.userid
                  WHERE q.questionid = :questionid"; 
        $stmt = $db->prepare($query);
        $stmt->bindParam(':questionid', $questionId, PDO::PARAM_INT); 
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getAllQuestions($moduleid = null) {
        global $db; // Assuming $db is your PDO database connection object
        
        try {
            // Prepare the SQL query to retrieve all questions with module names and descriptions
            $query = "SELECT q.title, q.content, q.questionid, m.modulename, m.description
                      FROM questions q
                      INNER JOIN modules m ON q.moduleid = m.moduleid";
            
            // If moduleid is provided, add a WHERE clause
            if ($moduleid !== null) {
                $query .= " WHERE q.moduleid = :moduleid";
            }
            
            // Prepare the statement
            $stmt = $db->prepare($query);
            
            if ($moduleid !== null) {
                $stmt->bindParam(':moduleid', $moduleid, PDO::PARAM_INT);
            }
            
            // Execute the query
            $stmt->execute();
        
            // Fetch all questions as an associative array
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            return $questions;
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array in case of error
        }
        }
    
        function getQuestionsByModule($db, $moduleId) {
            try {
                $query = "SELECT q.title, q.content, q.questionid, m.modulename, m.description
                          FROM questions q
                          INNER JOIN modules m ON q.moduleid = m.moduleid
                          WHERE q.moduleid = :moduleId";
        
                $stmt = $db->prepare($query);
                $stmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
                $stmt->execute();
        
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }
        
    function postReply($db, $questionId, $replyContent) {
        // Ensure session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Retrieve the user ID from the session
        $userId = isset($_SESSION['users']['userid']) ? $_SESSION['users']['userid'] : null;
    
        if ($userId === null) {
            // If user ID is not found in the session, display an error or handle it accordingly
            echo "Error: User ID not found. Please log in first.";
            header("Location: login.php");
        }
    
        try {
            // Prepare the SQL query to insert a new reply
            $query = "INSERT INTO replies (questionid, replycontent, userid) VALUES (:questionid, :replycontent, :userid)";
    
            // Prepare the statement
            $stmt = $db->prepare($query);
    
            // Bind parameters
            $stmt->bindParam(':questionid', $questionId, PDO::PARAM_INT);
            $stmt->bindParam(':replycontent', $replyContent, PDO::PARAM_STR);
            $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
    
            // Execute the query
            $success = $stmt->execute();
    
            if ($success) {
                echo "Reply posted successfully!";
            } else {
                echo "Failed to post reply.";
            }
    
            return $success;
        } catch (PDOException $e) {
            // Handle SQL error
            echo "SQL Error: " . $e->getMessage();
            return false;
        }
    }
    
    // module_functions.php

    function getAllUsers($db) {
        try {
            // Prepare the SQL query to retrieve all users
            $query = "SELECT userid, username, email, profile_picture FROM user";

            // Prepare the statement
            $stmt = $db->prepare($query);

            // Execute the query
            $stmt->execute();

            // Fetch all users as an associative array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    function changeUsername($db, $userId, $newUsername) {
        try {
            // Check if the new username is different from the current one
            $currentUsernameQuery = "SELECT username FROM user WHERE userid = :userId"; // Query to get the current username
            $currentStmt = $db->prepare($currentUsernameQuery); // Prepare the SQL statement
            $currentStmt->bindParam(':userId', $userId, PDO::PARAM_INT); // Bind the user ID parameter
            $currentStmt->execute(); // Execute the query
            $currentUsername = $currentStmt->fetchColumn(); // Fetch the current username
    
            // If the new username is the same as the current one, inform the user
            if ($currentUsername === $newUsername) {
                echo "Username is the same as the current username."; // Inform about identical username
                return false; // Return false to indicate no change
            }
    
            // Prepare the SQL query to update the username
            $query = "UPDATE user SET username = :newUsername WHERE userid = :userId"; // SQL update statement
            $stmt = $db->prepare($query); // Prepare the SQL statement
            
            // Bind the new username and user ID parameters to the query
            $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
            // Execute the query and check for success
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                echo "Username updated successfully."; // Successful update
                return true; // Return true to indicate success
            } else {
                echo "Failed to update username."; // Failed update
                return false; // Return false to indicate failure
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error updating username: " . $e->getMessage(); // Display error message
            return false; // Return false to indicate failure
        }
    }
    
    

    function deleteUser($db, $userId) {
        try {
            // Start a database transaction
            $db->beginTransaction();
            
            // Delete all replies associated with the user
            $deleteRepliesQuery = "DELETE FROM replies WHERE userid = :userid";
            $stmtReplies = $db->prepare($deleteRepliesQuery); // Prepare the SQL query
            $stmtReplies->bindParam(':userid', $userId, PDO::PARAM_INT); // Bind the user ID parameter
            $stmtReplies->execute(); // Execute the query to delete replies
            
            // Delete all questions posted by the user
            $deleteQuestionsQuery = "DELETE FROM questions WHERE userid = :userid";
            $stmtQuestions = $db->prepare($deleteQuestionsQuery); // Prepare the query for deleting questions
            $stmtQuestions->bindParam(':userid', $userId, PDO::PARAM_INT); // Bind the user ID parameter
            $stmtQuestions->execute(); // Execute the query to delete questions
            
            // Delete the user from the 'user' table
            $deleteUserQuery = "DELETE FROM user WHERE userid = :userid";
            $stmtUser = $db->prepare($deleteUserQuery); // Prepare the query for deleting the user
            $stmtUser->bindParam(':userid', $userId, PDO::PARAM_INT); // Bind the user ID parameter
            $stmtUser->execute(); // Execute the query to delete the user
            
            // If everything is successful, commit the transaction
            $db->commit(); // Confirm the transaction
            echo "User deleted successfully along with related records."; // Inform the successful user deletion
            
            return true; // Indicate successful user deletion
        } catch (PDOException $e) {
            // If there's an error, roll back the transaction
            $db->rollBack(); // Undo any changes made during the transaction
            echo "Error deleting user: " . $e->getMessage(); // Display the error message
            
            return false; // Indicate that user deletion failed
        }
    }
    
    

    function deleteProfilePicture($db, $userId)
    {
        // Retrieve the user's profile picture path from the database
        $query = "SELECT profile_picture FROM user WHERE userid = :userid";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $profilePicture = $result['profile_picture'];
    
        // Delete the profile picture from the file system if it exists
        try {
        if ($profilePicture) {
            unlink($profilePicture);
        }} catch (Exception $e) {}
    
        // Remove the profile picture from the database
        $query = "UPDATE user SET profile_picture = NULL WHERE userid = :userid";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    function getUserProfilePicture($db, $userId)
    {
        // Define the SQL query to retrieve the profile picture path
        $query = "SELECT profile_picture FROM user WHERE userid = :userid";
    
        // Prepare the statement using the database connection
        $stmt = $db->prepare($query);
    
        // Bind the user ID parameter to the query
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
    
        // Execute the query
        $stmt->execute();
    
        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Check if a result was found
        if ($result) {
            // Return the profile picture path if found
            return $result['profile_picture'];
        } else {
            // Return null if no result was found (i.e., no profile picture set)
            return null;
        }
    }
    
    function displayProfilePicture($db, $userId) {
        // Retrieve the user's profile picture path
        $profilePicturePath = getUserProfilePicture($db, $userId);
    
        if ($profilePicturePath) {
            echo '<img src="' . $profilePicturePath . '" alt="Profile Picture" class="rounded-circle" style="width: 250px; height: 250px;">';
        } else {
            // If no profile picture is set, display a default placeholder image
            echo '<img src="img/default_profile.jpg" alt="Default Profile Picture" class="rounded-circle" style="width: 250px; height: 250px;">';
        }
    }



?>