<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plans</title>
    <link rel="stylesheet" href="membership.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link
        href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Black+Ops+One&family=Caveat:wght@400..700&family=Chela+One&family=Edu+VIC+WA+NT+Beginner:wght@400..700&family=Kalam:wght@300;400;700&family=Michroma&family=PT+Sans+Narrow:wght@400;700&family=Pixelify+Sans:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
        <style>
            .table{
                background-color: #C3BBB2;
            }
        </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Membership Plans</h2>

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
            $planNames = $_POST['planName'];
            $prices = $_POST['price'];
            $durations = $_POST['duration'];
            $allSuccess = true;

            for ($i = 0; $i < count($planNames); $i++) {
                $planName = $conn->real_escape_string($planNames[$i]);
                $price = $conn->real_escape_string($prices[$i]);
                $duration = $conn->real_escape_string($durations[$i]);

                $sql = "INSERT INTO `membership` (`name`, `amt`, `duration`) 
                        VALUES ('$planName', '$price', '$duration')";

                if (!$conn->query($sql)) {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error: " . $sql . "<br>" . $conn->error . "',
                            });
                          </script>";
                    $allSuccess = false;
                    break;
                }
            }

            if ($allSuccess) {
                // Redirect to avoid resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
        ?>

        <h2 class="mt-5 mb-4" >Existing Membership Plans</h2>
        <table class="table table-bordered" >
            <thead class="table">
                <tr>
                    <th>Plan ID</th>
                    <th>Plan Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT planid, name, amt, duration FROM membership";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='" . $row['planid'] . "'>";
                        echo "<td>" . $row['planid'] . "</td>";
                        echo "<td contenteditable='false'>" . $row['name'] . "</td>";
                        echo "<td contenteditable='false'>" . $row['amt'] . "</td>";
                        echo "<td contenteditable='false'>" . $row['duration'] . "</td>";
                        echo "<td>
                                <button class='btn btn-primary edit-btn'>Edit</button>
                                <button class='btn btn-success save-btn' style='display:none;'>Save</button>
                               <button class='btn btn-danger delete-btn'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="mt-5 mb-4">Add New Membership Plan</h2>
        <form action="" method="POST">
            <table class="table" id="membershipTable">
                <tbody>
                    <tr>
                        <td><input type="text" name="planName[]" class="form-control" placeholder="Plan Name" required>
                        </td>
                        <td><input type="text" name="price[]" class="form-control" placeholder="Price" required></td>
                        <td><input type="text" name="duration[]" class="form-control" placeholder="Duration" required>
                        </td>
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
                let uid = row.getAttribute('data-id'); 
                let pname = row.cells[1].innerText;
                let price = row.cells[2].innerText;
                let duration = row.cells[3].innerText;

                fetch('update_plan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `uid=${uid}&pname=${encodeURIComponent(pname)}&price=${encodeURIComponent(price)}&dur=${encodeURIComponent(duration)}`
                })
                .then(response => response.text())
                .then(result => {
                    if (result.trim() === 'User updated successfully') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: result
                        });
                        row.querySelectorAll('td[contenteditable]').forEach(td => td.contentEditable = false);
                        row.querySelector('.edit-btn').style.display = 'inline';
                        row.querySelector('.save-btn').style.display = 'none';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating user'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating user'
                    });
                });
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let row = this.closest('tr');
                let uid = row.getAttribute('data-id');

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
                        fetch('delete-membership.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `uid=${uid}`
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result.trim() === 'User deleted successfully') {
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                );
                                row.remove();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error deleting user'
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error deleting user'
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
