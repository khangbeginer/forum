<?php
    session_start();
    // Xử lý logout (xóa session)
    unset($_SESSION['username']);
    session_destroy();
    logout();
    // Redirect to login page after successful logout
    header('Location: login.php');
    exit;
?>
