<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management</title>
    <link rel="stylesheet" href="membership.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Black+Ops+One&family=Caveat:wght@400..700&family=Chela+One&family=Edu+VIC+WA+NT+Beginner:wght@400..700&family=Kalam:wght@300;400;700&family=Michroma&family=PT+Sans+Narrow:wght@400;700&family=Pixelify+Sans:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Equipment Management</h2>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gym";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $names = $_POST['names'];
            $counts = $_POST['count'];
            $statuses = $_POST['status'];
            $lastMaints = $_POST['last'];
            $allSuccess = true;

            for ($i = 0; $i < count($names); $i++) {
                $name = $conn->real_escape_string($names[$i]);
                $count = $conn->real_escape_string($counts[$i]);
                $status = $conn->real_escape_string($statuses[$i]);
                $lastMaint = $conn->real_escape_string($lastMaints[$i]);

                $sql = "INSERT INTO `equipment`(`ename`, `count`, `status`, `lmaintance`) 
                        VALUES ('$name', '$count', '$status', '$lastMaint')";

                if (!$conn->query($sql)) {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error: " . $conn->error . "',
                            });
                          </script>";
                    $allSuccess = false;
                    break;
                }
            }

            if ($allSuccess) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Equipment added successfully!'
                        }).then(function() {
                            window.location = '" . $_SERVER['PHP_SELF'] . "';
                        });
                      </script>";
            }
        }
        ?>

        <h2 class="mt-5 mb-4">All Equipment</h2>
        <table class="table table-bordered">
            <thead class="table">
                <tr>
                    <th>Equipment ID</th>
                    <th>Name</th>
                    <th>Count</th>
                    <th>Status</th>
                    <th>Last Maintenance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT `eid`, `ename`, `count`, `status`, `lmaintance` FROM `equipment`";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='" . $row['eid'] . "'>";
                        echo "<td>" . $row['eid'] . "</td>";
                        echo "<td contenteditable='false'>" . $row['ename'] . "</td>";
                        echo "<td contenteditable='false'>" . $row['count'] . "</td>";
                        echo "<td contenteditable='false'>" . $row['status'] . "</td>";
                        echo "<td><input type='date' value='" . $row['lmaintance'] . "'></td>";
                        echo "<td>
                                <button class='btn btn-primary edit-btn'>Edit</button>
                                <button class='btn btn-success save-btn' style='display:none;'>Save</button>
                                <button class='btn btn-danger delete-btn'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="mt-5 mb-4">Add New Equipment</h2>
        <form action="" method="POST">
            <table class="table" id="equipmentTable">
                <tbody>
                    <tr>
                        <td><input type="text" name="names[]" class="form-control" placeholder="Name" required></td>
                        <td><input type="text" name="count[]" class="form-control" placeholder="Count" required></td>
                        <td><input type="text" name="status[]" class="form-control" placeholder="Status" required></td>
                        <td><input type="date" name="last[]" class="form-control" placeholder="Last Maintenance" required></td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                let row = this.closest('tr');
                row.querySelectorAll('td[contenteditable]').forEach(td => td.contentEditable = true);
                row.querySelector('.edit-btn').style.display = 'none';
                row.querySelector('.save-btn').style.display = 'inline';
            });
        });

        document.querySelectorAll('.save-btn').forEach(button => {
    button.addEventListener('click', function () {
        let row = this.closest('tr');
        let eid = row.getAttribute('data-id');
        let ename = row.querySelector('td:nth-child(2)').innerText;
        let count = row.querySelector('td:nth-child(3)').innerText;
        let status = row.querySelector('td:nth-child(4)').innerText;
        let lmaintance = row.querySelector('input[type="date"]').value; // Corrected: Fetch value from input

        fetch('update_equipment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `eid=${eid}&ename=${encodeURIComponent(ename)}&count=${encodeURIComponent(count)}&status=${encodeURIComponent(status)}&lmaintance=${encodeURIComponent(lmaintance)}`
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === 'User updated successfully') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Equipment updated successfully!'
                });
                row.querySelectorAll('td[contenteditable]').forEach(td => td.contentEditable = false);
                row.querySelector('input[type="date"]').disabled = true; // Disable date input after saving
                row.querySelector('.edit-btn').style.display = 'inline';
                row.querySelector('.save-btn').style.display = 'none';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating equipment'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating equipment'
            });
        });
    });
});


        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let row = this.closest('tr');
                let eid = row.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('delete_equipment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `eid=${eid}`
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result.trim() === 'Equipment deleted successfully') {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                row.remove();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error deleting equipment'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error deleting equipment'
                            });
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
