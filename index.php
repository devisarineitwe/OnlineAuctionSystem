<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Auction System</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>

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
        <script>
            // Hide the alert after 5 seconds
            setTimeout(function() {
                document.getElementById('alertDiv').style.display = 'none';
            }, 5000);
        </script>
    <?php endif; ?>
</div>
<div class="container card">
    <div class="row">
        <div class="col-4">
            <img src=" images/logo.jpg" alt="Logo" class="img-fluid img-thumbnail">
        </div>
        <div class="bg-primary text-white col-8">
            <h1 class="banner-title">Welcome to Online Auction System for Guma Stocks</h1>
            <p class="banner-description">Your partner in Kabale providing you with quality second-hand products at affordable prices.</p>
            <p class="banner-subtitle">We are located at Nyerere Avenue, between KABASCO and Little Litz, opposite Hindu temple.</p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Explore products at auction from Guma Stocks</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <img src="images/mattress.jpeg" alt="Sample Product" class="img-fluid">
            </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Interactive Chat</h5>
                <p class="card-text">Chat and negotiate products based on available bids. You can also buy products using this system</p>
                <img src="images/sofa.jpeg" alt="Sample Product" class="img-fluid">
            </div>
            </div>
        </div>
    </div>            
</div>

<div class="text-center mt-4">
    <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="dashboard.php" class="btn btn-primary">Go to dashboard</a>
        <a href="user_authentication.php?action=logout" class="btn btn-danger">Logout</a>
    <?php } else { ?>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#loginModal">
            Log In to View More
        </button>
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
                    <input type="text" class="form-control" id="username" name="username" value="Devisarineitwe" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="Deal@2000" required>
                </div>
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select class="form-control" id="user_type" name="user_type" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
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
                    <p>by clicking <span class="text-primary">Register</span> then you have agreed by all <a href="http://localhost/online_auction/user_terms.php#terms-and-conditions">Terms and Conditions</a> of our service</p>
                    <button type="submit" class="btn btn-primary" name="registerbtn">Register</button>
                </form>
                <p class="text-center">Already have an account? <a href="#" onclick="showLoginModal()">Log In</a></p>
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


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
