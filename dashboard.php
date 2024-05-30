<?php
include_once "includes/sessions.php";
if (!isset($_SESSION["role"])) {
    // Redirect to login if the session does not exist
    header("Location: index.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "online_auction_kab");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Common counts
$auctions_count = $mysqli->query("SELECT COUNT(*) AS count FROM auctions")->fetch_assoc()["count"];
$products_count = $mysqli->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()["count"];

// User-specific counts
if ($_SESSION["role"] == "user") {
    $user_id = $_SESSION["user_id"];
    $user_bids_count = $mysqli->query("SELECT COUNT(*) AS count FROM bids WHERE UserID = $user_id")->fetch_assoc()["count"];
    
}

// Admin-specific counts
if ($_SESSION["role"] == "admin") {
    $bids_count = $mysqli->query("SELECT COUNT(*) AS count FROM bids")->fetch_assoc()["count"];
    $messages_count = $mysqli->query("SELECT COUNT(*) AS count FROM messages")->fetch_assoc()["count"];
    $transactions_count = $mysqli->query("SELECT COUNT(*) AS count FROM transactions")->fetch_assoc()["count"];
    $users_count = $mysqli->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()["count"];
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Dashboard</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CDN -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Navbar */
        .navbar {
            background-color: #003366;
        }
        .navbar-brand, .nav-link {
            color: white;
        }

        /* Sidebar */
        .sidebar {
            top: 0;
            left: 0;
            background-color: #f0f0f0;
            border-right: 1px solid #ccc;
            height: 100vh;
            position: fixed;
        }
        .sidebar-icon {
            font-size: 30px;
            color: #003366;
            margin: 10px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px;
            color: #003366;
            text-decoration: none;
        }
        .sidebar-link:hover {
            background-color: #e6e6e6;
        }

        /* Main content */
        .main-container {
            margin-top: 40px;
            
        }
        .card {
            margin: 20px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #ffcc00;
            color: #003366;
            font-weight: bold;
        }
        .card-body {
            background-color: white;
        }
        .card-footer {
            background-color: #e6e6e6;
        }

        /* Media queries */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php include_once "includes/navbar.php"; ?>
    
    <!-- Sidebar and Main content -->
    <div class="container-fluid main-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <?php include_once "includes/sidenav.php"; ?>
            </div>            
            <!-- Main content -->
            <div class="col-md-8 main-content">
                <div class="container">
                    <?php  
                    if($_SESSION['role'] == 'admin') { ?>
                    <div class="row">
                        <div class="card col">
                            <div class="card-header">
                                ADMINISTRATORS
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">You are logged in as an Administrator</h5>
                                <p class="card-text">Admimistrators role is to make sure the system continue working, 
                                    you can now add new products and approve some pending bids to give customers mandate 
                                    to pay if they for Company products</p>
                                <a href="upload_product.php" class="btn btn-primary">Add New product</a>
                                <a href="bids.php" class="btn btn-primary">Check Pending Bids</a>
                                
                            </div>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="row">
                        <div class="card col">
                            <div class="card-header">
                                NOTICE
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">WELCOME USER</h5>
                                <p class="card-text">You have customer rights, you can now identify products you need and 
                                    make Bids on if they have reached their auction date. You can also negotiate with the administrator
                                about the price negotiations through a respective chart for each product provided</p>
                                <a href="products.php" class="btn btn-primary">View Products</a>
                            </div>
                            </div>
                        </div>
                    <?php }?>
                    <div class="row">
                        <!-- Common Cards for all users -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Products
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center"><?php echo $products_count ?></h1>
                                </div>
                                <div class="card-footer">
                                    <a href="products.php">View Details</a>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($_SESSION["role"] == "admin") { ?>
                            <!-- Additional Cards for Admin -->
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total Bids
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center"><?php echo $bids_count ?></h1>
                                    </div>
                                    <div class="card-footer">
                                        <a href="bids.php">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total Auctions
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center"><?php echo $auctions_count ?></h1>
                                    </div>
                                    <div class="card-footer">
                                        <a href="auctions.php">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total Transactions
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center"><?php echo $transactions_count ?></h1>
                                    </div>
                                    <div class="card-footer">
                                        <a href="#">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total Revenue
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center">$10,000</h1> <!-- Example static value, replace with dynamic -->
                                    </div>
                                    <div class="card-footer">
                                        <a href="#">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total Users
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center"><?php echo $users_count ?></h1>
                                    </div>
                                    <div class="card-footer">
                                        <a href="#">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($_SESSION["role"] == "user") { ?>
                            <!-- Additional Cards for User -->
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        My Bids
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center"><?php echo $user_bids_count ?></h1>
                                    </div>
                                    <div class="card-footer">
                                        <a href="bids.php">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total Auctions
                                    </div>
                                    <div class="card-body">
                                        <h1 class="text-center"><?php echo $auctions_count ?></h1>
                                    </div>
                                    <div class="card-footer">
                                        <a href="auctions.php">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once "includes/footer.php"; ?>
    
    <!-- Bootstrap CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
