<?php
session_start();
if(isset($_SESSION["user_id"])){
    // echo "logged in";
}else{
    header("Location: http://localhost/online_auction/index.php?%20message=You%20must%20login%20first&alertClass=alert-danger");
}


?>