<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainers List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Black+Ops+One&family=Caveat:wght@400..700&family=Chela+One&family=Edu+VIC+WA+NT+Beginner:wght@400..700&family=Kalam:wght@300;400;700&family=Michroma&family=PT+Sans+Narrow:wght@400;700&family=Pixelify+Sans:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family:michroma ;
            margin: 0;
            background-color: #16;
        }

        table {
            /* border-collapse: collapse; */
            width: 100%;
            
        }
        body{
   
   background-color: #16110B !important;
   color: #C3BBB2 !important;
}
h2{
   text-decoration: underline;
   margin-bottom: 15px;
}
*{
   font-family: 'michroma', sans-serif;
}
.table{
   background-color: #C3BBB2 !important;
}

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td[contenteditable="true"] {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4" >Trainers List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Certification</th>
                    <th>Experience</th>
                    <th>Date of Join </th>
                    <th>Time</th>
                    <th>Fee</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "gym";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to fetch details of users whose role is 'trainer'
                $sql = "SELECT 
                            users.id AS id, 
                            users.uname AS username, 
                            users.email, 
                            trainer.pho AS phone, 
                            trainer.cert AS certification, 
                            trainer.exp AS experience, 
                            trainer.doj AS date_of_join, 
                            trainer.time, 
                            trainer.fee 
                        FROM 
                            users 
                        LEFT JOIN 
                            trainer ON users.id = trainer.id 
                        WHERE 
                            users.role = 'trainer'";

                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "<tr><td colspan='10' class='text-center'>Error executing query: " . $conn->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['certification']) . "</td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['experience']) . "</td>";
                        echo "<td contenteditable='false'><input type='date' value='" . htmlspecialchars($row['date_of_join']) . "'></td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['time']) . "</td>";
                        echo "<td contenteditable='false'>" . htmlspecialchars($row['fee']) . "</td>";
                        echo "<td>
                                <button class='btn btn-primary edit-btn'>Edit</button>
                                <button class='btn btn-success save-btn' style='display:none;'>Save</button>
                                <button class='btn btn-danger delete-btn'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        // Edit button functionality
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                let row = this.closest('tr');
                row.querySelectorAll('td').forEach(td => td.contentEditable = true);
                row.querySelector('.edit-btn').style.display = 'none';
                row.querySelector('.save-btn').style.display = 'inline';
            });
        });

        // Save button functionality
        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', function () {
                let row = this.closest('tr');
                let uid = row.getAttribute('data-id');
                let username = row.cells[1].innerText;
                let email = row.cells[2].innerText;
                let phone = row.cells[3].innerText;
                let certification = row.cells[4].innerText;
                let experience = row.cells[5].innerText;
                let date_of_join = row.querySelector('input[type="date"]').value;
                let time = row.cells[7].innerText;
                let fee = row.cells[8].innerText;

                fetch('update_trainer.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `uid=${uid}&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&certification=${encodeURIComponent(certification)}&experience=${encodeURIComponent(experience)}&date_of_join=${encodeURIComponent(date_of_join)}&time=${encodeURIComponent(time)}&fee=${encodeURIComponent(fee)}`
                })
                    .then(response => response.text())
                    .then(result => {
                        if (result.trim() === 'Trainer updated successfully') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: result
                            });
                            row.querySelectorAll('td').forEach(td => td.contentEditable = false);
                            row.querySelector('.edit-btn').style.display = 'inline';
                            row.querySelector('.save-btn').style.display = 'none';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error updating trainer'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating trainer'
                        });
                    });
            });
        });

        // Delete button functionality
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
                        fetch('delete_trainer.php', {
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
                                        'Trainer has been deleted.',
                                        'success'
                                    );
                                    row.remove(); // Remove the row from the DOM
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error deleting trainer'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error deleting trainer'
                                });
                            });
                    }
                });
            });
        });

    </script>
</body>

</html>