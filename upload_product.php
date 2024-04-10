<?php
include 'includes/navbar.php';
session_start(); 

if(isset($_POST["add_product"])){ 
// Define your database parameters
$host = "localhost";
$dbname = "online_auction_kab";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product details from the form
$productname = $_POST["productname"];
$description = $_POST["description"];
$startingprice = $_POST["startingprice"];
$auctionstart = $_POST["auctionstart"];
$auctionend = $_POST["auctionend"];
$sellerid = $_SESSION["user_id"]; // Assuming you have a session variable for the logged in user id

// Validate the input data
if (empty($productname) || empty($startingprice) || empty($auctionstart) || empty($auctionend)) {
    echo "Please fill in all the required fields.";
    exit();
}

if ($startingprice < 0) {
    echo "Starting price must be positive.";
    exit();
}

if ($auctionstart > $auctionend) {
    echo "Auction start date must be before auction end date.";
    exit();
}

// Upload the image file
if (isset($_FILES["image"])) {
    $target_dir = "uploads/"; // The directory where you want to save the uploaded images
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        exit();
    }

    // Check if the file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        exit();
    }

    // Check the file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        exit();
    }

    // Check the file type
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Move the file to the target directory
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
        exit();
    }

    // Get the image URL
    $imageurl = $target_dir . $_FILES["image"]["name"];
} else {
    echo "Please upload an image file.";
    exit();
}

// Insert the product into the Products table
$sql = "INSERT INTO Products (ProductName, Description, ImageURL, StartingPrice, AuctionStartDate, AuctionEndDate, SellerID) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $productname, $description, $imageurl, $startingprice, $auctionstart, $auctionend, $sellerid);

if ($stmt->execute()) {
    echo "Product uploaded successfully.";
} else {
    echo "Error: " . $stmt->error;
}



$stmt->close();
$conn->close();
}
?>
<html>
<head>
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

        label {
            display: block;
            margin: 10px 0;
        }

        input, textarea, select {
            width: 90%;
            font-size: 16px;
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
</head>
<body>    
    <!-- Wrap the form in a container class -->
    <div class="row">
        <div class="col-3">
            <?php
            include_once "includes/sidenav.php"
            ?>
        </div>
    <div class="container col-9">
        <h1>Upload Product</h1>
        <form action="upload_product.php" method="post" enctype="multipart/form-data">
            <!-- Use the form-group and form-control classes for each input field -->
            <div class="form-group">
                <label for="productname">Product Name:</label>
                <input type="text" id="productname" name="productname" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control-file" required>
            </div>

            <div class="form-group">
                <label for="startingprice">Starting Price:</label>
                <input type="number" id="startingprice" name="startingprice" min="0" step="0.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="auctionstart">Auction Start Date:</label>
                <input type="datetime-local" id="auctionstart" name="auctionstart" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="auctionend">Auction End Date:</label>
                <input type="datetime-local" id="auctionend" name="auctionend" class="form-control" required>
            </div>

            <input type="submit" name="add_product" value="Upload" class="btn btn-primary">
        </form>
    </div>
</div>
    <!-- Include Bootstrap 5 JS and Popper.js files from CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
