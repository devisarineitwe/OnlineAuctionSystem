<?php
session_start();
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "online_auction_kab";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch products from the database
function fetchProductsFromDatabase($conn) {
    // Modify the query based on your database schema
    $query = "SELECT * FROM Products LIMIT 2";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Fetch the data as an associative array
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $products;
    } else {
        // Handle the case where the query failed
        return false;
    }
}

// Fetch products from the database
$products = fetchProductsFromDatabase($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Auction System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Custom styles -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>

<!-- Display alerts div -->
<div id="alertDiv" class="mt-3">
    <?php
    // Check if message and alertClass are set in the URL
    if (isset($_GET['message']) && isset($_GET['alertClass'])) :
        $message = urldecode($_GET['message']);
        $alertClass = $_GET['alertClass'];
    ?>
        <div class="alert <?php echo $alertClass; ?>" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
</div>

<div class="container mt-5">
    <h1 class="mb-4">Welcome to the Online Auction System</h1>

    <div class="row product-row">
        <?php foreach ($products as $product) : ?>
            <div class="col-md-6 mb-4 product-card">
                <div class="card">
                <img src="<?php echo $product['ImageURL']; ?>" class="card-img-top" alt="<?php echo $product['ProductName']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['ProductName']; ?></h5>
                        <p class="card-text"><?php echo $product['Description']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <?php if (isset($_SESSION['user_id'])){?>
            <!-- If the user is logged in, show a link to view more products -->
            <a href="dashboard.php" class="btn btn-primary">Go to dashboard</a>
            <a href="user_authentication.php?action=logout" class="btn btn-danger">Logout</a>
        <?php }else {?>
            <!-- If the user is not logged in, show a button to prompt login -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#loginModal">
                Log In to View More
            </button>
            <!-- Button to trigger the register modal -->
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#registerModal">
                Register
            </button>
        <?php } ?>
    </div>

    <!-- Login/Signup Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Include your login form here -->
                    <form action="user_authentication.php" method="post">
                        <!-- Your login form fields go here -->
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="loginbtn">Log In</button>
                    </form>
                    <hr>
                    <p class="text-center">Don't have an account? <a href="#" onclick="showRegisterModal()">Register</a></p>

                </div>
            </div>
        </div>
    </div>
    <!-- Registration Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Include your registration form here -->
                    <form action="user_authentication.php" method="post">
                        <!-- Your registration form fields go here -->
                        <div class="form-group">
                            <label for="register-username">Full Name</label>
                            <input type="text" class="form-control" id="register-username" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="register-username">Username</label>
                            <input type="text" class="form-control" id="register-username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Password</label>
                            <input type="password" class="form-control" id="register-password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" class="form-control" id="register-email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="registerbtn">Register</button>
                    </form>
                    <p class="text-center">Already have an account? <a href="#" onclick="showLoginModal()">Log In</a></p>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- JavaScript to handle modal visibility -->
<script>
    function showLoginModal() {
        $("#registerModal").modal("hide");
        $("#loginModal").modal("show");
    }

    function showRegisterModal() {
        $("#loginModal").modal("hide");
        $("#registerModal").modal("show");
    }
</script>


<!-- Bootstrap JS and Popper.js (for Bootstrap modal and tooltip) -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
