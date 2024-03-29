<?php
include_once "includes/sessions.php";
$mysqli = new mysqli("localhost", "root", "", "online_auction_kab");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Auctions count
$result = $mysqli->query("SELECT COUNT(*) AS auctions_count FROM auctions");
$row = $result->fetch_assoc();
$auctions_count = $row["auctions_count"];

// Bids count
$result = $mysqli->query("SELECT COUNT(*) AS bids_count FROM bids");
$row = $result->fetch_assoc();
$bids_count = $row["bids_count"];

// Messages count
$result = $mysqli->query("SELECT COUNT(*) AS messages_count FROM messages");
$row = $result->fetch_assoc();
$messages_count = $row["messages_count"];

// Products count
$result = $mysqli->query("SELECT COUNT(*) AS products_count FROM products");
$row = $result->fetch_assoc();
$products_count = $row["products_count"];

// Transactions count
$result = $mysqli->query("SELECT COUNT(*) AS transactions_count FROM transactions");
$row = $result->fetch_assoc();
$transactions_count = $row["transactions_count"];

// Users count
$result = $mysqli->query("SELECT COUNT(*) AS users_count FROM users");
$row = $result->fetch_assoc();
$users_count = $row["users_count"];

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

        .navbar-brand {
            color: white;
        }

        .nav-link {
            color: white;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f0f0f0;
            border-right: 1px solid #ccc;
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
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            margin:20px;
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

        /* Chart */
        .chart {
            width: 100%;
            height: 300px;
        }

        /* Media queries */
        @media (max-width: 768px) {
            /* Hide sidebar */
            .sidebar {
                display: none;
            }

            /* Adjust main content margin */
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <div class="container">
                <a class="navbar-brand" href="#">Auction Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
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
                            <a class="nav-link" href="#">Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Transactions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user_authentication.php?action=logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Sidebar -->
        <div class="sidebar">
            <a class="sidebar-link" href="#">
                <i class="fas fa-home sidebar-icon"></i>
                <span>Home</span>
            </a>
            
        </div>
        <!-- Sidebar and Main content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <a class="sidebar-link" href="index.php">
                        <i class="fas fa-home sidebar-icon"></i>
                        <span>Home</span>
                    </a>
                    <a class="sidebar-link" href="products.php">
                        <i class="fas fa-shopping-cart sidebar-icon"></i>
                        <span>Products</span>
                    </a>
                    <a class="sidebar-link" href="#">
                        <i class="fas fa-gavel sidebar-icon"></i>
                        <span>Bids</span>
                    </a>
                    <a class="sidebar-link" href="#">
                        <i class="fas fa-envelope sidebar-icon"></i>
                        <span>Messages</span>
                    </a>
                    <a class="sidebar-link" href="#">
                        <i class="fas fa-dollar-sign sidebar-icon"></i>
                        <span>Transactions</span>
                    </a>
                    <a class="sidebar-link" href="#">
                        <i class="fas fa-user sidebar-icon"></i>
                        <span>Profile</span>
                    </a>
                    <a class="sidebar-link" href="#">
                        <i class="fas fa-sign-out-alt sidebar-icon"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        <!-- Main content -->
        <div class="col-md-9 main-content">
                <div class="container">
                    <div class="row">
                        <!-- Card 1 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Products
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center"><?php echo $products_count?></h1>
                                </div>
                                <div class="card-footer">
                                    <a href="products.php">View Details</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Bids
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center"><?php echo $bids_count?></h1>
                                </div>
                                <div class="card-footer">
                                    <a href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Messages
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center"><?php echo $messages_count?></h1>
                                </div>
                                <div class="card-footer">
                                    <a href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Transactions
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center"><?php echo $transactions_count?></h1>
                                </div>
                                <div class="card-footer">
                                    <a href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Revenue
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center">$10,000</h1>
                                </div>
                                <div class="card-footer">
                                    <a href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card 6 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Users
                                </div>
                                <div class="card-body">
                                    <h1 class="text-center"><?php echo $users_count?></h1>
                                </div>
                                <div class="card-footer">
                                    <a href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                        <!-- Chart 1 -->
                        <div class="col-md-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    Products by Category
                                </div>
                                <div class="card-body">
                                    <div id="chart1" class="chart"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Chart 2 -->
                        <div class="col-md-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    Bids by Time
                                </div>
                                <div class="card-body">
                                    <div id="chart2" class="chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Google Charts-->
</body>
</html>