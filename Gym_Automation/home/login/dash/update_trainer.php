<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $uid = intval($_POST['uid']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cert = $_POST['certification'];
    $experience = $_POST['experience'];
    $date_of_join = $_POST['date_of_join'];
    $time = $_POST['time'];
    $fee = $_POST['fee'];

    // Create database connection
    $con = mysqli_connect("localhost", "root", "", "gym");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the trainer already exists
    $checkSql = "SELECT COUNT(*) AS count FROM trainer WHERE id = ?";
    $stmt = $con->prepare($checkSql);
    if ($stmt === FALSE) {
        die("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Prepare SQL query for upsert (insert/update)
    if ($count > 0) {
        // Update existing trainer
        $sql = "UPDATE `trainer` 
                SET `exp` = ?, `doj` = ?, `pho` = ?, `cert` = ?, `time` = ?, `fee` = ? 
                WHERE `id` = ?";
        //   $sql="UPDATE `trainer`
        //          SET `exp`='$experience',`doj`='$date_of_join',`pho`='$phone',`cert`='$cert',`time`='$time',`fee`=' $fee' WHERE id = '$uid'";
        $stmt = $con->prepare($sql);
        if ($stmt === FALSE) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("ssssssi", $experience, $date_of_join, $phone, $certification, $time, $fee, $uid);
    } else {
        // Insert new trainer
        $sql = "INSERT INTO `trainer` (`id`, `exp`, `doj`, `pho`, `cert`, `time`, `fee`) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        if ($stmt === FALSE) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("issssss", $uid, $experience, $date_of_join, $phone, $certification, $time, $fee);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "Trainer updated successfully";
    } else {
        echo "Error updating trainer: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($con);
}
?>