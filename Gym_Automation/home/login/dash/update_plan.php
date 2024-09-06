<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pid = $_POST['uid'];
    $pname = $_POST['pname'];
    $amt = $_POST['price'];
    $dur = $_POST['dur'];

    $con = mysqli_connect("localhost", "root", "");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($con, "gym");

    $sql = "UPDATE `membership` SET `name`='$pname',`duration`='$dur',`amt`='$amt' WHERE `planid`='$pid'";
    
    if (mysqli_query($con, $sql)) {
        echo "User updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
