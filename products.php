<?php
// checking sessions
include_once "includes/sessions.php";

// Define your database parameters
$host = "localhost"; // The name of the host where the database is located
$dbname = "online_auction_kab"; // The name of the database
$username = "root"; // The username for accessing the database
$password = ""; // The password for accessing the database

// Create a PDO object and connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


// Prepare a SQL statement to select all products from the Products table
$sql = "SELECT * FROM Products";
$stmt = $pdo->prepare($sql);

// Execute the SQL statement
$stmt->execute();

// Fetch the data as an associative array
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Dashboard</title>
    <!-- Include Bootstrap 5 CSS file from CDN -->
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
<?php
    include "includes/navbar.php";
    ?>
        <!-- Create a main content area -->
        <div class="row">
            <!-- Create a side navigation bar -->
            <div class="col-md-3">
                <?php
                include_once "includes/sidenav.php"
                ?>
            </div>
            <!-- Create a main content area -->
            <div class="col-md-9">
                <div class="container">
                    
                <!-- Display a link to view more products -->
                <a href="upload_product.php" class="btn btn-success">New Product</a>

                <h1 class="mt-4">All Products</h1>
                
                <div class="row">
                    <?php
                    // Loop through the products array and display each product as a card
                    $counter = 0; // Initialize a counter
                    foreach ($products as $product) {
                        // Extract the product data
                        $product_id = $product['ProductID'];
                        $product_name = $product['ProductName'];
                        $description = $product['Description'];
                        $image_url = $product['ImageURL'];
                        $starting_price = $product['StartingPrice'];
                        $auction_start_date = $product['AuctionStartDate'];
                        $auction_end_date = $product['AuctionEndDate'];
                        $seller_id = $product['SellerID'];

                        // Check if counter is a multiple of 3
                        if ($counter % 3 == 0) {
                            // If it is, start a new row
                            echo '<div class="row">';
                        }
                        // Create a card for each product
                        echo "
                        <div class='col-md-4'>
                            <div class='card mb-4'>
                                <img src='$image_url' class='card-img-top' alt='$product_name'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$product_name</h5>
                                    <p class='card-text'>$description</p>
                                    <p class='card-text'>Starting Price: $starting_price</p>
                                    <p class='card-text'>Auction Start Date: $auction_start_date</p>
                                    <p class='card-text'>Auction End Date: $auction_end_date</p>
                                    <p class='card-text'>Seller ID: $seller_id</p>
                                    <a href='product.php?id=$product_id' class='btn btn-primary'>View Details</a>
                                </div>
                            </div>
                        </div>                        

                        ";
                        // Increment the counter
                        $counter++;                       
                        // Check if counter is a multiple of 3 or it's the last product
                        if ($counter % 3 == 0 || $counter == count($products)) {
                            // If it is, end the row
                            echo '</div>';
                            echo "<hr class='my-4 bg-primary' style='height: 4px;'>";
                        }
                    }
                    ?>
                </div>
            </div>
              
            </div>
        </div>
    </div>
    <!-- Include Bootstrap 5 JS and Popper.js files from CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>

