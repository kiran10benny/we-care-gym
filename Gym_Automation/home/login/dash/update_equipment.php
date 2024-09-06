<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eid = $_POST['eid'];
    $ename = $_POST['ename'];
    $count = $_POST['count'];
    $status = $_POST['status'];
    $lmaintance = $_POST['lmaintance'];

    $con = new mysqli("localhost", "root", "", "gym");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("UPDATE `equipment` SET `ename` = ?, `count` = ?, `status` = ?, `lmaintance` = ? WHERE `eid` = ?");
    $stmt->bind_param("sisss", $ename, $count, $status, $lmaintance, $eid);

    if ($stmt->execute()) {
        echo "User updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
