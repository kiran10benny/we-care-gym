<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="userman.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Black+Ops+One&family=Caveat:wght@400..700&family=Chela+One&family=Edu+VIC+WA+NT+Beginner:wght@400..700&family=Kalam:wght@300;400;700&family=Michroma&family=PT+Sans+Narrow:wght@400;700&family=Pixelify+Sans:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            font-family: 'Michroma', sans-serif;
        }

        body {
            background-color: #16110b;
        }

        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h1{
            text-align: center;
            color: #C3BBB2;
            margin-top: 20px;
            text-decoration:underline;
        }
        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #C3BBB2;
            font-family: 'Michroma', sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            padding: 10px;
            text-align: center;
            background-color: #C3BBB2;
        }

        td {
            padding: 10px;
            text-align: left;
        }

        .search {
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search input[type="text"] {
            width: 300px;
            padding: 10px;
            border-radius: 50px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
            margin-top: 20px;
        }

        .edit-btn,
        .save-btn,
        .delete-btn {
            cursor: pointer;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>

<body>
        <h1>User Management</h1>
    <div class="table-container">
        <!-- Search Bar -->
        <div class="search">
            <label>
                <input type="text" id="search-input" placeholder="Search here">
            </label>
        </div>

        <?php
        $con = mysqli_connect("localhost", "root", "", "gym");
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT `id`, `uname`, `email`, `pass`, `image`, `role` FROM `users`";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table id='user-table'>";
            echo "<tr><th>User ID</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td contenteditable='false'>" . htmlspecialchars($row['uname']) . "</td>";
                echo "<td contenteditable='false'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td contenteditable='false'>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td>
                    <button class='btn btn-primary edit-btn'>Edit</button>
                    <button class='btn btn-success save-btn' style='display:none;'>Save</button>
                    <button class='btn btn-danger delete-btn'>Delete</button>
                  </td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No users found.";
        }

        mysqli_close($con);
        ?>
    </div>

    <script>
        document.getElementById('search-input').addEventListener('input', function () {
            let filter = this.value.toUpperCase();
            let table = document.getElementById('user-table');
            let tr = table.getElementsByTagName('tr');

            for (let i = 1  ; i < tr.length; i++) { // Skip the header row
                let td = tr[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < td.length - 1; j++) { 
                    if (td[j]) {
                        let txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                if (match) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        });

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
                let uname = row.cells[1].innerText;
                let email = row.cells[2].innerText;
                let role = row.cells[3].innerText;

                fetch('update_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `uid=${uid}&uname=${encodeURIComponent(uname)}&email=${encodeURIComponent(email)}&role=${encodeURIComponent(role)}`
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
                        fetch('delete.php', {
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
                                    'User deleted successfully.',
                                    'success'
                                );
                                row.remove(); // Remove the row from the table
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
</body>
</html>
