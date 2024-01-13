<?php
include 'includes/navbar.php';
session_start();    

// Define your database parameters
$host = "localhost";
$dbname = "online_auction_kab";
$username = "root";
$password = "";

// Create a PDO object and connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Get the product id from the URL
$product_id = $_GET['id'];

// Fetch messages related to each product
function fetchMessages($pdo, $productID)
{
    $sql = "SELECT * FROM Messages WHERE ProductID = :productID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if the user has submitted a bid
if (isset($_POST['submit'])) {
    // Get the user id and the bid amount from the form
    $user_id = $_POST['user_id'];
    $bid_amount = $_POST['bid_amount'];

    // Validate the bid amount
    if ($bid_amount > 0) {
        // Prepare a SQL statement to insert the bid data into the Bids table
        $sql = "INSERT INTO Bids (ProductID, UserID, BidAmount) VALUES (:product_id, :user_id, :bid_amount)";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters and execute the SQL statement
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':bid_amount', $bid_amount);
        $stmt->execute();

        // Prepare a SQL statement to update the CurrentBid column in the Products table
        $sql = "UPDATE Products SET CurrentBid = :bid_amount WHERE ProductID = :product_id";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters and execute the SQL statement
        $stmt->bindParam(':bid_amount', $bid_amount);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        // Display a success message
        echo "<p>Your bid has been placed successfully.</p>";
    } else {
        // Display an error message
        echo "<p>Please enter a valid bid amount.</p>";
    }
}

// posting a product message
if (isset($_SESSION['user_id'])) {
    // Get the logged-in user's ID
    $logged_in_user_id = $_SESSION['user_id'];
    if (isset($_POST["post_message"])){
        echo "attempting to save message";
        $product_id = $_POST['product_id'];
        $newMessage = $_POST['newMessage'];
        // Validate and sanitize the input (you may add more validation)
        $product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
        $newMessage = filter_var($newMessage, FILTER_SANITIZE_STRING);

        // Additional validation if needed
        if (empty($newMessage)) {
            // Handle validation error, e.g., display an error message
            echo "Please enter a valid message.";
        } else {
            echo " let us attempt saving";
            // Process and store the new message in the database
            // Assuming you have a database connection already established ($pdo)
            try {
                $sql = "INSERT INTO messages (ProductID, SenderID, MessageText) VALUES (:product_id, :sender_id, :newMessage)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->bindParam(':sender_id', $logged_in_user_id, PDO::PARAM_INT);
                $stmt->bindParam(':newMessage', $newMessage, PDO::PARAM_STR);
                $stmt->execute();

                
            } catch (PDOException $e) {
                // Handle database error, e.g., display an error message
                echo "Error: " . $e->getMessage();
            }
        }
    }
}else{
    header("Location: index.php");
}

// Prepare a SQL statement to select the product data from the Products table
$sql = "SELECT * FROM Products WHERE ProductID = :product_id";
$stmt = $pdo->prepare($sql);

// Bind the parameter and execute the SQL statement
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();

// Fetch the product data as an associative array
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Extract the product data
$product_name = $product['ProductName'];
$description = $product['Description'];
$image_url = $product['ImageURL'];
$starting_price = $product['StartingPrice'];
$current_bid = $product['CurrentBid'];


// extract product fetchMessages
$productMessages = fetchMessages($pdo, $product_id)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Dashboard</title>
    <!-- Include Bootstrap 5 CSS file from CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <!-- Include your custom style file -->
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4">
            <?php include 'includes/sidenav.php'; ?>
        </div>

        <!-- Main content area -->
        <div class="col-md-6">
            <div class="row">
                <!-- Display product details -->
                <div class='product-item'>
                    <div class='row'>
                        <a href="upload_product.php">New product</a>
                        <!-- Image column -->
                        <div class='col-md-6'>
                            <img class='card-img-top' src='<?php echo $image_url; ?>' alt='<?php echo $product_name; ?>'>
                        </div>
                        <!-- Details column -->
                        <div class='col-md-5'>
                            <div class='product-tile-footer'>
                                <div class='product-title'><?php echo $product_name; ?></div>
                                <div class='product-price'>Starting Price: <?php echo $starting_price; ?></div>
                                <div class='product-price'>Current Bid: <?php echo $current_bid; ?></div>
                                <!-- Description below the image -->
                                <div class='product-description'><?php echo $description; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#chatModal">
                            Open Product Chat
                        </button>
                        <!-- Add this button where you want it, for example, in your product listing page -->
                        <a href="edit_product.php?product_id=<?php echo $product['ProductID']; ?>" class="btn btn-primary">Edit Product</a>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Bid history and bidding form -->
            <div class="row">
                <div class="col-md-4">
                    <h5>Bid Product</h5>
                    <!-- Display bidding form -->
                    <form method='post' action='product.php?id=<?php echo $product_id; ?>'>
                        <div class='bid-form'>
                            <label for='user_id'>User ID:</label>
                            <input type='text' id='user_id' name='user_id' required>
                            <label for='bid_amount'>Bid Amount:</label>
                            <input type='number' id='bid_amount' name='bid_amount' min='0' step='0.01' required>
                            <input type='submit' name='submit' value='Place Bid'>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <h5>Bid history</h5>
                    <!-- Display bid history -->
                    <!-- ... (rest of your bid history display code) -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chat Modal -->
<div class="modal" id="chatModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat for <?php echo $product_name; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display existing messages -->
                <?php foreach ($productMessages as $message) : ?>
                    <p><?php echo $message['MessageText']; ?></p>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <!-- Input for new message -->
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div class="form-group">
                        <label for="newMessage">New Message:</label>
                        <input type="text" class="form-control" id="newMessage" name="newMessage" required>
                    </div>
                    <button type="submit" name="post_message" class="btn btn-primary">Post Message</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap 5 JS and Popper.js files from CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
