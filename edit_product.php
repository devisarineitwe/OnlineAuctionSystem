<?php
include 'includes/navbar.php';
session_start();

// Define your database parameters
$host = "localhost";
$dbname = "online_auction_kab";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume you have a product ID passed to this page
$productID = $_GET['product_id']; // Make sure to validate and sanitize this input

// Fetch product data from the database
$sql = "SELECT * FROM Products WHERE ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $productData = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit();
}
$stmt->close();

// Check if the update form is submitted
if (isset($_POST["update_product"])) {
    // Get the updated product details from the form
    $productname = $_POST["productname"];
    $description = $_POST["description"];
    $startingprice = $_POST["startingprice"];
    $auctionstart = $_POST["auctionstart"];
    $auctionend = $_POST["auctionend"];

    // Validate the input data
    // Add your validation logic here...

    // Update the product in the Products table
    $updateSql = "UPDATE Products SET ProductName=?, Description=?, StartingPrice=?, AuctionStartDate=?, AuctionEndDate=? WHERE ProductID=?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssssi", $productname, $description, $startingprice, $auctionstart, $auctionend, $productID);

    if ($updateStmt->execute()) {
        echo "Product updated successfully.";
        // Redirect to the product page after successful update
        header("Location: products.php?id=" . $productData['ProductID']);
        exit();
    } else {
        echo "Error updating product: " . $updateStmt->error;
    }

    $updateStmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            background-color: white;
            font-family: Arial, sans-serif;
        }

        h1 {
            color: blue;
            text-align: center;
        }

        form {
            margin: 20px auto;
            width: 80%;
            border: 2px solid blue;
            padding: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, textarea, select {
            width: 100%;
            font-size: 16px;
            padding: 8px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: deepyellow;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <!-- Include your custom style file -->
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <form action="edit_product.php?product_id=<?php echo $productData['ProductID']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $productData['ProductID']; ?>">
            
            <div class="form-group">
                <label for="productname">Product Name:</label>
                <input type="text" id="productname" name="productname" class="form-control" value="<?php echo $productData['ProductName']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" class="form-control"><?php echo $productData['Description']; ?></textarea>
            </div>

            <!-- Other form fields with values from $productData -->

            <div class="form-group">
                <label for="startingprice">Starting Price:</label>
                <input type="number" id="startingprice" name="startingprice" min="0" step="0.01" class="form-control" value="<?php echo $productData['StartingPrice']; ?>" required>
            </div>

            <div class="form-group">
                <label for="auctionstart">Auction Start Date:</label>
                <input type="datetime-local" id="auctionstart" name="auctionstart" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($productData['AuctionStartDate'])); ?>" required>
            </div>

            <div class="form-group">
                <label for="auctionend">Auction End Date:</label>
                <input type="datetime-local" id="auctionend" name="auctionend" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($productData['AuctionEndDate'])); ?>" required>
            </div>

            <input type="submit" name="update_product" value="Update" class="btn btn-primary">
        </form>
    </div>
</body>
<!-- Include Bootstrap 5 JS and Popper.js files from CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</html>
