<?php
include_once 'includes/sessions.php';
include_once 'includes/database.php'; // Include your database connection code



function fetchMessages($pdo, $productID)
{
    $sql = "SELECT * FROM Messages WHERE ProductID = :productID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch user information by ID
function fetchUserById($pdo, $userId)
{
    $sql = "SELECT * FROM users WHERE UserID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch messages related to the specified product
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    echo "Error product not sent";
}

$productMessages = fetchMessages($pdo, $product_id);

// Display the chat messages
foreach ($productMessages as $message) {
    $messageOwner = fetchUserById($pdo, $message['SenderID']);

    // Check if the logged-in user is the owner of the message
    $isMessageOwner = ($_SESSION['user_id'] === $message['SenderID']);

    // Display the user and message
    echo "<p>";

    // Display heart emoji if the user is the owner of the room
    if ($isMessageOwner) {
        echo "❤️ ";
    }

    echo "<strong>{$messageOwner['Username']}</strong>: {$message['MessageText']}";

    // Show delete button if the user is the owner of the message
    if ($isMessageOwner) {
        echo "<button class='btn btn-danger btn-sm' onclick=\"deleteMessage('{$message['MessageID']}')\">Delete</button>";
    }

    echo "</p>";
}
?>
