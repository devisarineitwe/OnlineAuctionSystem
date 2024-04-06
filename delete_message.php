<?php
include_once "includes/sessions.php";
include_once "includes/database.php";
// Function to delete a message
function deleteMessage($pdo, $messageId) {
    try {
        // Delete the message from the database
        $deleteSql = "DELETE FROM messages WHERE MessageID = :message_id"; // Change 'Messages' to 'messages' if the table name is lowercase
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Echo a success message or handle the response as needed
        echo "Message deleted successfully!";
    } catch (PDOException $e) {
        // Handle database error, e.g., display an error message
        echo "Error: " . $e->getMessage();
    }
    return "deleted successfully";
}

// Check if the request is for message deletion
if (isset($_POST['message_id'])) {
    // Get the message ID from the POST data
    $messageId = $_POST['message_id'];

    // Call the deleteMessage function
    echo deleteMessage($pdo, $messageId);
}
?>