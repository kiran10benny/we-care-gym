<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['uid'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $con = mysqli_connect("localhost", "root", "");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($con, "gym");

    $sql = "UPDATE `users` SET `uname`='$uname', `email`='$email', `role`='$role' WHERE `id`='$uid'";
    
    if (mysqli_query($con, $sql)) {
        echo "User updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
