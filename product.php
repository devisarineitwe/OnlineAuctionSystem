<?php
include 'includes/navbar.php';
include_once 'includes/sessions.php';
include 'includes/database.php';

$product_id = $_GET['id'];

$sql = "SELECT * FROM Products WHERE ProductID = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();

$product = $stmt->fetch(PDO::FETCH_ASSOC);

$product_name = $product['ProductName'];
$description = $product['Description'];
$image_url = $product['ImageURL'];
$starting_price = $product['StartingPrice'];
$current_bid = $product['CurrentBid'];
$owner_user_id = $product['SellerID'];

$room_owner = ($_SESSION['user_id'] === $owner_user_id);

$productMessages = fetchMessages($pdo, $product_id);

function fetchMessages($pdo, $productID)
{
    $sql = "SELECT * FROM Messages WHERE ProductID = :productID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$logged_in_user = "";

try {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT Username FROM users WHERE UserID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $logged_in_user = $result['Username'];
        } else {
            echo "User not found.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (isset($_POST['place-bid'])) {
    $user_id = $_POST['user_id'];
    $bid_amount = $_POST['bid_amount'];

    if ($bid_amount > 0) {
        try {
            $insertSql = "INSERT INTO Bids (ProductID, UserID, BidAmount) VALUES (:product_id, :user_id, :bid_amount)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':product_id', $product_id);
            $insertStmt->bindParam(':user_id', $user_id);
            $insertStmt->bindParam(':bid_amount', $bid_amount);
            $insertStmt->execute();

            $updateSql = "UPDATE Products SET CurrentBid = :bid_amount WHERE ProductID = :product_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':bid_amount', $bid_amount);
            $updateStmt->bindParam(':product_id', $product_id);
            $updateStmt->execute();

            echo "<p>Your bid has been placed successfully.</p>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<p>Please enter a valid bid amount.</p>";
    }
}

try {
    $bidHistorySql = "SELECT u.Username, b.BidAmount, b.BidTime FROM Bids b
                      JOIN users u ON b.UserID = u.UserID
                      WHERE b.ProductID = :product_id
                      ORDER BY b.BidTime DESC";
    $bidHistoryStmt = $pdo->prepare($bidHistorySql);
    $bidHistoryStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $bidHistoryStmt->execute();
    $bidHistory = $bidHistoryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching bid history: " . $e->getMessage();
}


// for saving user messages
if (isset($_SESSION['user_id'])) {
    $logged_in_user_id = $_SESSION['user_id'];

    if (isset($_POST["post_message"])) {
        $product_id = $_POST['product_id'];
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

                
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
} else {
    header("Location: index.php");
}


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
    // Inside deleteMessage function in your PHP file
    echo "Deletion successful!";
    exit; // Stop further execution

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <?php include 'includes/sidenav.php'; ?>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class='product-item'>
                        <div class='row'>
                            <a href="upload_product.php">New product</a>
                            <div class='col-md-6'>
                                <img class='card-img-top' src='<?php echo $image_url; ?>' alt='<?php echo $product_name; ?>'>
                            </div>
                            <div class='col-md-5'>
                                <div class='product-tile-footer'>
                                    <div class='product-title'><?php echo $product_name; ?></div>
                                    <div class='product-price'>Starting Price: <?php echo $starting_price; ?></div>
                                    <div class='product-price'>Current Bid: <?php echo $current_bid; ?></div>
                                    <div class='product-description'><?php echo $description; ?></div>
                                    <div class='product-description'><a class="btn-primary" href="payment.php">Make Payment</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#chatModal">
                                    Open Product Chat
                                </button>
                                <?php
                                if ($room_owner) {
                                    echo "<a href='edit_product.php?product_id={$product['ProductID']}' class='btn btn-primary'>Edit Product</a>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php if (!$room_owner) : ?>
                        <div class="col-md-4">
                            <h5 class="mb-4">Bid Product</h5>
                            <form method='post' action='product.php?id=<?php echo $product_id; ?>' class='border p-3'>
                                <div class='mb-3'>
                                    <label for='user_id' class='form-label'>User: <?php echo $logged_in_user; ?>:</label>
                                    <input type='text' id='user_id' name='user_id' value='<?php echo $_SESSION["user_id"]; ?>' class='form-control' required readonly>
                                </div>
                                <div class='mb-3'>
                                    <label for='bid_amount' class='form-label'>Bid Amount:</label>
                                    <input type='number' id='bid_amount' name='bid_amount' min='0' step='0.01' class='form-control' required>
                                </div>
                                <button type='submit' name='place-bid' class='btn btn-primary'>Place Bid</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-8 border p-3">
                        <h5 class="mb-4">Bid History</h5>

                        <?php if (!empty($bidHistory)) : ?>
                            <ul class="list-group">
                                <?php foreach ($bidHistory as $bid) : ?>
                                    <li class="list-group-item">
                                        <strong><?php echo $bid['Username']; ?></strong> placed a bid of
                                        <span class="badge bg-primary"><?php echo $bid['BidAmount']; ?></span>
                                        at <?php echo $bid['BidTime']; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>No bid history available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Chat for <?php echo $product_name; ?></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="chatMessagesContainer">
                <?php

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
                </div>
                <div class="modal-footer">
                <form method="post" action="" id="message-form" onsubmit="return postMessage();">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <div class="form-group">
                            <label for="newMessage">New Message:</label>
                            <input type="text" class="form-control" id="newMessage" name="newMessage" required>
                        </div>
                        <button type="button" onclick="postMessage()" class="btn btn-primary">Post Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script>
// Function to update chat messages
function updateChatMessages() {
    // Get the product ID
    var productId = <?php echo $product_id; ?>;

    // Send AJAX request
    $.ajax({
        url: 'update_messages.php', // Change the URL to 'update_messages.php'
        method: 'GET',
        data: { product_id: productId },
        success: function (data) {
            // Update the chat messages container
            $('#chatMessagesContainer').html(data);
        }
    });
}

// Refresh chat messages every 5 seconds (adjust the interval as needed)
setInterval(updateChatMessages, 5000);


// javascript to call for deleting the message
function deleteMessage(messageID) {
        console.log('Button clicked with messageID:', messageID);

        // Send AJAX request to delete the message
        $.ajax({
            url: 'delete_message.php',
            method: 'POST',
            data: { message_id: messageID },
            success: function (data) {
                // Handle success, maybe update the chat messages after deletion
                updateChatMessages();
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }


// submitting the messate form
function postMessage() {
        // Get the form data
        var formData = $('#message-form').serialize();

        // Send AJAX request to save the message
        $.ajax({
            url: 'product.php', // Update the URL to the correct file
            method: 'POST',
            data: formData,
            success: function (data) {
                // Handle success, maybe update the chat messages after saving
                console.log('Message saved successfully:', data);
                updateChatMessages();
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error('Error during message saving:', xhr.responseText);
            }
        });

        // Prevent the default form submission
        return false;
    }

    </script>

</body>

</html>
