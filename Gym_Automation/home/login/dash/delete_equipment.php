<?php
$con = mysqli_connect("localhost", "root", "", "gym");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['eid'])) {
    $eid = intval($_POST['eid']);


    $sql = "DELETE FROM `equipment` WHERE `eid` = ?";
    $stmt = $con->prepare($sql);


    $stmt->bind_param("i", $eid);

    if ($stmt->execute()) {
        echo "Equipment deleted successfully";
    } else {
        echo "Error deleting equipment";
    }

    $stmt->close();
}

mysqli_close($con);
?>