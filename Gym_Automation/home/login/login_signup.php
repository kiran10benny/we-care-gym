<?php
session_start();  // Start session at the beginning

// Database connection details
$dbname = "gym";
$conn = mysqli_connect("localhost", "root", "", $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form data is submitted
if (isset($_POST['uname']) && isset($_POST['pass'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $pass = $_POST['pass'];

    // Prepare a statement to prevent SQL injection
    $sql = $conn->prepare("SELECT * FROM users WHERE uname = ?");
    $sql->bind_param("s", $uname);
    $sql->execute();
    $result = $sql->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hash = $row['pass'];
        $role = $row['role'];   // Fetch the role
        $image = $row['image']; // Fetch the image
        if (password_verify($pass, $hash)) {
            if ($role == 'Admin') {
                $_SESSION['username'] = $uname;
                $_SESSION['image'] = $image;  // Store the image in session
                $_SESSION['role'] = $role;
                header("Location: dash/dash.php");
                exit();
            } else {
                echo "You are not authorized as an admin.";
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "User not found.";
    }
    
    
    // Close the statement
    $sql->close();
}

// Close the database connection
$conn->close();
?>
