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

// Function to fetch bids from the database based on user role and search parameters
function fetchBidsFromDatabase($conn, $userId, $isAdmin, $search) {
    $searchQuery = "";
    if ($search) {
        $searchQuery = " AND (products.ProductName LIKE ? OR users.Username LIKE ? OR bids.BidTime LIKE ?)";
    }

    if ($isAdmin) {
        // Admin: Fetch all bids
        $query = "SELECT bids.*, products.ProductName, users.Username FROM bids 
                  JOIN products ON bids.ProductID = products.ProductID 
                  JOIN users ON bids.UserID = users.UserID 
                  WHERE 1=1 $searchQuery";
    } else {
        // User: Fetch only their own bids
        $query = "SELECT bids.*, products.ProductName, users.Username FROM bids 
                  JOIN products ON bids.ProductID = products.ProductID 
                  JOIN users ON bids.UserID = users.UserID 
                  WHERE bids.UserID = ? $searchQuery";
    }

    $stmt = $conn->prepare($query);

    if ($search) {
        $searchTerm = "%" . $search . "%";
        if ($isAdmin) {
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        } else {
            $stmt->bind_param("isss", $userId, $searchTerm, $searchTerm, $searchTerm);
        }
    } else {
        if (!$isAdmin) {
            $stmt->bind_param("i", $userId);
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful
    if ($result) {
        // Fetch the data as an associative array
        $bids = $result->fetch_all(MYSQLI_ASSOC);
        return $bids;
    } else {
        // Handle the case where the query failed
        return false;
    }
}

// Handle bid approval and deletion
if (isset($_POST['action'])) {
    $bidId = intval($_POST['bid_id']);

    if ($_POST['action'] == 'approve') {
        // Approve the bid (example logic, adjust as needed)
        $stmt = $conn->prepare("UPDATE bids SET BidStatus = 'Approved' WHERE BidID = ?");
        $stmt->bind_param("i", $bidId);
        $stmt->execute();
    } elseif ($_POST['action'] == 'delete') {
        // Delete the bid
        $stmt = $conn->prepare("DELETE FROM bids WHERE BidID = ?");
        $stmt->bind_param("i", $bidId);
        $stmt->execute();
    }
}

// Get search parameter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch bids from the database
$bids = fetchBidsFromDatabase($conn, $_SESSION['user_id'], $_SESSION['role'] == 'admin', $search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bids Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<?php
include_once "includes/navbar.php";
?>
<div class="row">
    <div class="col-4">
        <?php
        include_once "includes/sidenav.php";
        ?>
    </div>
    <div class="container col-8">
        <h2 class="text-center mb-4">Bids Management</h2>
        <form class="form-inline mb-4" method="get">
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Bid ID</th>
                    <th>Product Name</th>
                    <th>Username</th>
                    <th>Bid Amount</th>
                    <th>Bid Time</th>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <th>Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($bids) { ?>
                    <?php foreach ($bids as $bid) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bid['BidID']); ?></td>
                            <td><?php echo htmlspecialchars($bid['ProductName']); ?></td>
                            <td><?php echo htmlspecialchars($bid['Username']); ?></td>
                            <td><?php echo htmlspecialchars($bid['BidAmount']); ?></td>
                            <td><?php echo htmlspecialchars($bid['BidTime']); ?></td>
                            <?php if ($_SESSION['role'] == 'admin') { ?>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="bid_id" value="<?php echo $bid['BidID']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="bid_id" value="<?php echo $bid['BidID']; ?>">
                                        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?php echo $_SESSION['role'] == 'admin' ? '6' : '5'; ?>" class="text-center">No bids found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

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
