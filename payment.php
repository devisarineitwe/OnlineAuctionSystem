<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Stop further execution
}

// Include database connection
include_once "includes/database.php";

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE UserID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if both current_bid and starting_bid are set and greater than or equal to 0
if (isset($_GET['current_bid']) && isset($_GET['starting_bid']) && $_GET['current_bid'] >= 0 && $_GET['starting_bid'] >= 0) {
    // Calculate the payment amount (current_bid + 20% of starting_bid)
    $current_bid = $_GET['current_bid'];
    $starting_bid = $_GET['starting_bid'];
    $payment_amount = $current_bid + 0.2 * $starting_bid;
} else {
    // Use the present bid amount + 20% of the present bid amount
    $current_bid = isset($_GET['current_bid']) ? $_GET['current_bid'] : 0;
    $payment_amount = $current_bid + 0.2 * $current_bid;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .placeholder-text {
            color: #6c757d; /* Custom color for placeholders */
        }
    </style>
</head>
<body>

<?php include_once "includes/navbar.php";?>

<!-- Payment Form -->
<div class="row">
    <div class="col-3">
        <?php include_once "includes/sidenav.php" ?>
    </div>
    <div class="container mt-5 col-8">
        <div class="alert alert-info" role="alert">
            <h4 class="mb-0">Hey <span class="text-primary"><?php echo $user['Username']; ?></span> of email <span class="text-primary"><?php echo $user['Email']; ?></span>, you are about to make a payment</h4>
        </div>
        <div class="alert alert-info" role="alert">
            Pay the current highest bid of UGX <?php echo $payment_amount; ?>
        </div>

        <form method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay">
            <input type="hidden" name="public_key" value="FLWPUBK_TEST-02b9b5fc6406bd4a41c3ff141cc45e93-X" />
            <input type="hidden" name="customer[email]" value="<?php echo $user['Email']; ?>" />
            <input type="hidden" name="customer[name]" value="<?php echo $user['Username']; ?>" />
            <input type="hidden" name="tx_ref" value="txref-81123" />
            <input type="hidden" name="redirect_url" value="https://www.kab.ac.ug/" />
            <input type="hidden" name="amount" value="<?php echo $payment_amount; ?>" />
            <input type="hidden" name="currency" value="UGX" />
            <input type="hidden" name="meta[source]" value="docs-html-test" />
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    </div>
</div>
<?php
    include_once "includes/footer.php";
?>
<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
