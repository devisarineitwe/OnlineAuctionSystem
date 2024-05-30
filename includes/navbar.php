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

</style>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="http://localhost/online_auction/index.php">
            <img src="images/logo.jpg" alt="Logo" width="50" height="50" class="d-inline-block align-top">
            Auction Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="http://localhost/online_auction/index.php?%20message=You%20must%20login%20first&alertClass=alert-danger">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/online_auction/products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/online_auction/auctions.php">Today's Action</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/online_auction/bids.php">Bids</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Transactions</a>
                </li>
                <?php 
                if(isset($_SESSION['user_id'])){
                    if(isset($_SESSION['username'])){
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php  echo $_SESSION['username']; ?></a>
                </li>
                <?php }else{
                    echo "No username found";
                } ?>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/online_auction/user_authentication.php?action=logout">Logout</a>
                </li>
                <?php }else{
                    echo "you are not logged in";
                } 
                ?>
            </ul>
        </div>
    </div>
</nav>
