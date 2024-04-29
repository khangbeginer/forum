<?php
include("function.php");
if (isset($_POST['loginbutton'])) {login();}
if (isLoggedIn()){header("Location: home.php");}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="text-center mb-4">Login</h1>
        <form action="login.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn btn-primary" name="loginbutton">Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="http://localhost/coursework/register.php" id="register-link">Register</a></p>
    </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-f0jHWVgMexmIGLwEgozCWEXBqA7IqywGtEoCJylvkLRQkJ403zCLJ0MRAyOoT8tT" crossorigin="anonymous"></script>
</body>
</html>
