<?php
include_once "includes/sessions.php";
include_once "includes/database.php";

if (isset($_SESSION['user_id'])) {
    $logged_in_user_id = $_SESSION['user_id'];

    if (isset($_POST["post_message"])) {
        $product_id = $_POST['productId']; // Make sure to match the parameter name sent by AJAX
        $newMessage = $_POST['newMessage'];
        $product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
        $newMessage = strip_tags($newMessage);

        if (empty($newMessage)) {
            echo "Please enter a valid message.";
        } else {
            try {
                $sql = "INSERT INTO messages (ProductID, SenderID, MessageText) VALUES (:product_id, :sender_id, :newMessage)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->bindParam(':sender_id', $logged_in_user_id, PDO::PARAM_INT);
                $stmt->bindParam(':newMessage', $newMessage, PDO::PARAM_STR);
                $stmt->execute();

                // If you want to return a response to the AJAX request
                echo "Message saved successfully";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
} else {
    // If the user is not logged in, redirect to index.php or handle it accordingly
    header("Location: index.php");
}
?>
