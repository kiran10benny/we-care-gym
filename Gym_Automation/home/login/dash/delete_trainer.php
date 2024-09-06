<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = intval($_POST['uid']);

    // Create database connection
    $con = mysqli_connect("localhost", "root", "", "gym");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL query to delete the trainer
    $sql = "DELETE FROM `users` WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    if ($stmt === FALSE) {
        die("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("i", $uid);

    // Execute the statement
    if ($stmt->execute()) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting trainer: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($con);
}
?>
