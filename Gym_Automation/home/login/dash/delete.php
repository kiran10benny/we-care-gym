<?php
$con = mysqli_connect("localhost", "root", "", "gym");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['uid'])) {
    $uid = intval($_POST['uid']);

    $sql = "DELETE FROM `users` WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $uid);

    if ($stmt->execute()) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user";
    }

    $stmt->close();
}

mysqli_close($con);
?>

