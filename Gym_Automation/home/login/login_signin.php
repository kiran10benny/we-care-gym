<?php  
// Step 1: Start the Session
session_start();

// Step 2: Get User Input
$name = $_POST['unames'];
$email = $_POST['emails'];
$pass = $_POST['passu'];
$image=$_POST['filename'];

// Step 3: Hash the Password
$hash = password_hash($pass, PASSWORD_DEFAULT);

// Step 4: Connect to the Database
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_select_db($con, "gym");

// Step 5: Insert User Data into the Database
$sql = "INSERT INTO `users`(`uname`, `email`, `pass`,`image`) VALUES ('$name','$email','$hash','$image')";
$que = mysqli_query($con, $sql);

// Step 6: Check if the Query was Successful
if ($que) {
    // Step 7: Set Session Variables
    $_SESSION['username'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['image']=$image;
    
    header("Location:dash/dash.php");
} else {
    echo "Registration failed. Please try again.";
}

// Step 8: Close the Database Connection
mysqli_close($con);
?>
