<?php
include_once "includes/sessions.php";
include_once "includes/database.php";

// Initialize variables
$success_message = $error_message = "";
$userID = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null; // Fetch the logged-in user's ID
$testimonialText = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the testimonial form is submitted
    if (isset($_POST["submit_testimonial"])) {
        // Validate and sanitize form inputs
        $testimonialText = trim($_POST["testimonialText"]);
        $testimonialText = htmlspecialchars($testimonialText);
        // Validate testimonial text (you can add more validation if needed)
        if (empty($testimonialText)) {
            $error_message = "Please enter your testimonial.";
        } else {
            try {
                // Prepare SQL statement to insert testimonial into the database
                $sql = "INSERT INTO testimonials (UserID, TestimonialText) VALUES (:user_id, :testimonial_text)";
                $stmt = $pdo->prepare($sql);
                // Bind parameters
                $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':testimonial_text', $testimonialText, PDO::PARAM_STR);
                // Execute the statement
                $stmt->execute();
                // Check if the testimonial is successfully inserted
                if ($stmt->rowCount() > 0) {
                    $success_message = "Testimonial submitted successfully.";
                } else {
                    $error_message = "Failed to submit testimonial. Please try again.";
                }
            } catch (PDOException $e) {
                // Handle database errors
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
}


// Fetch testimonials with associated users from the database
try {
    $sql = "SELECT t.TestimonialText, u.Username, t.SubmissionDate FROM testimonials t
            INNER JOIN users u ON t.UserID = u.UserID ORDER BY t.SubmissionDate DESC LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database errors
    $error_message = "Error: " . $e->getMessage();
    $testimonials = []; // Empty array to prevent errors in case of failure
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS for styling -->
    <style>
        .testimonial {
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .author {
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include_once "includes/navbar.php";?>
<div class="row">
    <div class="col-3">
        <?php
        include_once "includes/sidenav.php";
        ?>
    </div>
    <div class="col-8 container">
        <h2>Testimonials</h2>
        <?php
        // Display error message if there's any
        if (!empty($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>
        <!-- Carousel for displaying testimonials -->
        <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php if (!empty($testimonials)) : ?>
                    <?php foreach ($testimonials as $key => $testimonial) : ?>
                        <div class="carousel-item <?php echo $key == 0 ? 'active' : ''; ?>">
                            <div class="testimonial bg-primary">
                                <p class="text-white">"<?php echo $testimonial['TestimonialText']; ?>"</p>
                                <p class="author text-white">- <?php echo $testimonial['Username']; ?>, <?php echo $testimonial['SubmissionDate']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="carousel-item active">
                        <div class="testimonial">
                            <p>No testimonials available.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Previous and Next buttons -->
            <a class="carousel-control-prev" href="#testimonialCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#testimonialCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- Form for submitting testimonials -->
        <h2>Submit Your Testimonial</h2>
        <form id="testimonialForm" method="post" action="">
            <div class="form-group">
                <input type="hidden" name="user_id" value="<?php echo $userID; ?>">
                <label for="testimonialText">Your Testimonial</label>
                <textarea class="form-control" id="testimonialText" name="testimonialText" rows="3"><?php echo $testimonialText; ?></textarea>
            </div>
            <button type="submit" name="submit_testimonial" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<?php
    include_once "includes/footer.php";
?>
<!-- Link to Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- JavaScript for handling form submission -->
<script>
    // JavaScript for initializing Bootstrap Carousel
    $('.carousel').carousel();
</script>

</body>
</html>
