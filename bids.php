<?php
// Include database connection
include "includes/database.php";

try {
    // Initialize search variables
    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
    $search_product = isset($_GET['search_product']) ? $_GET['search_product'] : '';
    $search_amount = isset($_GET['search_amount']) ? $_GET['search_amount'] : '';

    // Construct the SQL query based on search criteria
    $sql = "SELECT bids.BidID, products.ProductName, users.Username, bids.BidAmount, bids.BidTime 
            FROM bids 
            INNER JOIN products ON bids.ProductID = products.ProductID
            INNER JOIN users ON bids.UserID = users.UserID
            WHERE 1 ";

    if (!empty($search_date)) {
        $sql .= " AND DATE(bids.BidTime) = :search_date ";
    }

    if (!empty($search_product)) {
        $sql .= " AND products.ProductName LIKE :search_product ";
    }

    if (!empty($search_amount)) {
        $sql .= " AND bids.BidAmount = :search_amount ";
    }

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters if needed
    if (!empty($search_date)) {
        $stmt->bindParam(':search_date', $search_date);
    }

    if (!empty($search_product)) {
        $search_product = "%$search_product%";
        $stmt->bindParam(':search_product', $search_product);
    }

    if (!empty($search_amount)) {
        $stmt->bindParam(':search_amount', $search_amount);
    }

    // Execute the query
    $stmt->execute();

    // Fetch all bids
    $bids = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bids</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles */
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mt-4">All Bids</h1>
    
    <!-- Search Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <form action="" method="GET">
                <div class="form-row">
                    <div class="col-md-4">
                        <input type="date" name="search_date" class="form-control" placeholder="Search by Date">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search_product" class="form-control" placeholder="Search by Product">
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="search_amount" class="form-control" placeholder="Search by Amount">
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Display Bids Table -->
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bid ID</th>
                    <th>Product Name</th>
                    <th>Username</th>
                    <th>Bid Amount</th>
                    <th>Bid Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bids as $bid): ?>
                    <tr>
                        <td><?php echo $bid['BidID']; ?></td>
                        <td><?php echo $bid['ProductName']; ?></td>
                        <td><?php echo $bid['Username']; ?></td>
                        <td>$<?php echo $bid['BidAmount']; ?></td>
                        <td><?php echo $bid['BidTime']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
