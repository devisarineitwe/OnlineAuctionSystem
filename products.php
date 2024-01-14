<?php

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
    <div class="container-fluid">
        <!-- Create a top navigation bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Auction Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Bids</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Transactions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Users</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Create a main content area -->
        <div class="row">
            <!-- Create a side navigation bar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="#" class="list-group-item list-group-item-action">Products</a>
                    <a href="#" class="list-group-item list-group-item-action">Bids</a>
                    <a href="#" class="list-group-item list-group-item-action">Transactions</a>
                    <a href="#" class="list-group-item list-group-item-action">Users</a>
                </div>
            </div>
            <!-- Create a main content area -->
            <div class="col-md-9">
                <h1>Dashboard</h1>
                <!-- Display the data from the Products table using Bootstrap cards -->
                <div class="row">
                    <?php
                    // Loop through the products array and display each product as a card
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

                        // Create a card for each product
                        echo "
                        <div class='col-md-3'>
                            <div class='card'>
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
                        
                    }
                    ?>
                </div>
                <!-- Display a link to view more products -->
                <a href="upload_product.php" class="btn btn-secondary">New Product</a>                
            </div>
        </div>
    </div>
    <!-- Include Bootstrap 5 JS and Popper.js files from CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>

