<?php
session_start();

if (isset($_SESSION["user_id"])) {
    // Check the role of the user
    if ($_SESSION["role"] == "admin") {
        // Admin is logged in, proceed as normal
    } else if ($_SESSION["role"] == "user") {
        // Regular user is logged in, proceed as normal
    } else {
        // Unrecognized role, redirect to login
        $message="un recorgnised user";
        header("Location: index.php?message=$message&alertClass=alert-danger");
        exit();
    }
} else {
    // Not logged in, redirect to login
    header("Location: http://localhost/online_auction/index.php?message=You%20must%20login%20first&alertClass=alert-danger");
    exit();
}
?>
