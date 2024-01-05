<?php
session_start();
// Include your database connection code here
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "online_auction_kab";

// Function to check if a username is available
function isUsernameAvailable($conn, $username) {
    $query = "SELECT * FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows == 0; // If the result set is empty, the username is available
}


// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // registering new user
    if (isset($_POST["registerbtn"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $fullname = $_POST["fullname"];

    // Check if the username is available
    if (isUsernameAvailable($conn, $username)) {
        // Perform the user registration
        $query = "INSERT INTO Users (Username, Password, Email, FullName) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $username, $password, $email, $fullname);

        if ($stmt->execute()) {
            $alertClass = "alert-success";
            $message = "User registered successfully!";
        } else {
            $alertClass = "alert-danger";
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $alertClass = "alert-danger";
        $message = "Username is Already in Use. Kindly Choose a Different Username";
    }
     // Redirect to another page with the message in the URL
     header("Location: index.php?message=$message&alertClass=$alertClass");
     exit();

}
// Check if the login form is submitted
else if (isset($_POST["loginbtn"])) {
    // Retrieve user input from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the entered username exists
    $query = "SELECT * FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, verify the password
        $row = $result->fetch_assoc();
        if ($password == $row["Password"]) {
            // Password is correct, create a session
            session_start();
            $_SESSION["user_id"] = $row["UserID"];
            $_SESSION["username"] = $row["Username"];
            $_SESSION["email"] = $row["Email"];
            $_SESSION["fullname"] = $row["FullName"];

            // Set success message
            $message = urlencode("Login successful!");
            $alertClass = "alert-success";

            // Redirect to another page with the message in the URL
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            $message = urlencode("Incorrect password. Please try again.");
            $alertClass = "alert-danger";
        }
    } else {
        // User does not exist
        $message = urlencode("User not found. Please check your username.");
        $alertClass = "alert-danger";
    }

    // Redirect to another page with the message in the URL
    header("Location: index.php?message=$message&alertClass=$alertClass");
    exit();
}

}

// logout section of this page
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    echo "we are logging out the user";
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after logout
    $message="You are logged out";
    $alertClass = "alert-danger";
    header("Location: index.php?message=$message&alertClass=$alertClass");
    exit();
}

// Close the database connection
$conn->close();
?>
