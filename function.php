<?php

session_start();
try {
    // Connect to the database using PDO
    $db = new PDO('mysql:host=localhost;dbname=coursework', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$username = "";
$email = "";
$success = "";
$succeed = "";


// Function to register a new user

function register()
{
    global $db, $username;

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Get form data
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Check if username is empty
            if (empty($username)) {
                echo "Username cannot be empty!";
                return;
            }

            // Check if the username already exists in the database
            $query = "SELECT * FROM user WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Username already exists, display an error message
                echo("Username already exists");
            }
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement for insertion
            $sql = "INSERT INTO user (username, password) VALUES (:username, :hashed_password)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':hashed_password', $hashed_password);
            // Execute the statement
            $stmt->execute();
            header("Location: home.php");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
        // Close connection
        $db = null;
    }
}

function login(){
    try {
        // Thông tin kết nối database
        global $db, $username;
        // Lấy dữ liệu đăng nhập
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Viết truy vấn SQL
        $sql = "SELECT * FROM user WHERE username = :username";

        // Chuẩn bị câu lệnh SQL
        $stmt = $db->prepare($sql);

        // Bind giá trị cho các tham số
        $stmt->bindParam(':username', $username);
        // Thực thi câu lệnh
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy kết quả dạng associative array

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session and redirect to home page
                $_SESSION['users'] = $user;
                header("Location: home.php");
                exit;
            } else {
                // Password is incorrect
                echo "Tên đăng nhập hoặc mật khẩu không chính xác!";
            }
        } else {
            // Username does not exist
            echo "User not found! ";
        }
        }
         catch(PDOException $e) {
            echo "Kết nối database thất bại: " . $e->getMessage();
        }
}



    function logout(){
        session_start();
        // Xử lý logout (xóa session)
        unset($_SESSION['username']);
        session_destroy();
        // Redirect to login page after successful logout
        header('Location: login.php');
        exit;
    }
    
    function isLoggedIn()
    {
        return (isset($_SESSION['users'])) ;
    }



    function isAdmin()
    {
        return (isset($_SESSION['users']) && $_SESSION['users']['role'] == '1');
    }

    // Function to handle image upload and update the user's profile image
    

    function editProfile() {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            global $db; // Ensure the database connection is available
    
            // Get user ID from session
            $userId = $_SESSION['users']['userid'];
    
            // Retrieve form data
            $email = $_POST['email'] ?? null;
            $about = $_POST['about'] ?? null;
    
            try {
                // File handling for profile picture
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    // Retrieve existing profile picture path from the database
                    $query = "SELECT profile_picture FROM user WHERE userid = :userid";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $existingProfilePicture = $result['profile_picture'];
    
                    // Delete the old profile picture if it exists
                    try {
                    if ($existingProfilePicture) {
                        unlink($existingProfilePicture);
                    }else{}
                    } catch (Exception $e) {}
                        
                    
                    // Process the new profile picture
                    $file = $_FILES['profile_picture'];
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
                    if (in_array($extension, $allowedExtensions) && $file['size'] <= 5000000) {
                        // Generate a unique filename
                        $newFilename = uniqid() . '.' . $extension;
    
                        // Move the uploaded file to the 'img/' directory
                        $destination = 'img/' . $newFilename;
                        move_uploaded_file($file['tmp_name'], $destination);
    
                        // Update the profile_picture in the database
                        $query = "UPDATE user SET profile_picture = :profile_picture WHERE userid = :userid";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':profile_picture', $destination);
                        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
    
                // Update the user's other profile information if provided
                if ($email !== null && $about !== null) {
                    $query = "UPDATE user SET email = :email, about = :about WHERE userid = :userid";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':about', $about);
                    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
    
                    // Execute the statement
                    $stmt->execute();
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
    
    
    
    

?>