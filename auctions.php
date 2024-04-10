
<?php    
        // Include database connection
        include "includes/database.php";

// Query to fetch auctions
        $sql = "SELECT * FROM products WHERE CURDATE() BETWEEN AuctionStartDate AND AuctionEndDate";
        $stmt = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Auctions</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles */
        .auction-item {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 20px;
            margin-bottom: 20px;
        }
        .auction-item h2 {
            margin-top: 0;
        }
        .auction-item p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<?php
include_once "includes/navbar.php";
?>

<div class="row">
<div class="col-3">
<?php
include_once "includes/sidenav.php";
?>
</div>
<div class="container mt-4 col-9">
    <h1>All Auctions</h1>
    <div class="row" id="auctions-list">
        <?php
 // Check if any auctions found
 if ($stmt->rowCount() > 0) {
    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product_id = $product['ProductID'];
        $product_name = $product['ProductName'];
        $description = $product['Description'];
        $image_url = $product['ImageURL'];
        $starting_price = $product['StartingPrice'];
        $auction_start_date = $product['AuctionStartDate'];
        $auction_end_date = $product['AuctionEndDate'];
        $seller_id = $product['SellerID'];

        // Output card for each product
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
} else {
    echo "No auctions found.";
}        ?>
    </div>    
</div>
    
</div>
<?php
    include_once "includes/footer.php";
?>
<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
