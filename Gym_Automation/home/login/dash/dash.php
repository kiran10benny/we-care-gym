<?php
session_start();
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
$image = isset($_SESSION['image']) ? htmlspecialchars($_SESSION['image']) : 'default.png';
$role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard | Korsat X Parmaga</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="dash.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Black+Ops+One&family=Caveat:wght@400..700&family=Chela+One&family=Edu+VIC+WA+NT+Beginner:wght@400..700&family=Kalam:wght@300;400;700&family=Michroma&family=PT+Sans+Narrow:wght@400;700&family=Pixelify+Sans:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="">
                        <img src="logo.png" alt="">
                        <span class="title">SQUARDS</span>
                    </a>
                </li>

                <div class="side">
                    <li></li>
                    <li>
                        <a href="dash.php">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="title">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.html">
                            <span class="icon">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </span>
                            <span class="title">Sign Out</span>
                        </a>
                    </li>
                </div>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>

            <div class="search">
                <label>
                    <input type="text" placeholder="Search here">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>

            <div class="profile">
                    <div class="info">
                        <p>Hey, <b><?php echo $username; ?></b></p>
                        <small class="text-muted"><?php echo $role; ?></small>
                    </div>
                    <div class="profile-photo">
                        <img src="<?php echo $_SESSION['image']; ?>" alt="nnn.fm">
                    </div>

                </div>
        </div>

        <div class="cardBox">
            <a href="userman.php">
                <div class="card">
                    <div>
                        <div class="carddetails">User Management</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                </div>
            </a>
            <a href="trainer.php">
                <div class="card">
                    <div>
                        <div class="carddetails">Trainer Management</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="accessibility-outline"></ion-icon>
                    </div>
                </div>
            </a>
            <a href="membership.php">
                <div class="card">
                    <div>
                        <div class="carddetails">Membership Plans</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                </div>
            </a>
            <a href="">
                <div class="card">
                    <div>
                        <div class="carddetails">Payments</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                </div>
            </a>
            <a href="">
                <div class="card">
                    <div>
                        <div class="carddetails">Workout Plans</div>
                    </div>
                    <div class="iconBx">
                        <i class="fa-solid fa-person-walking"></i>
                    </div>
                </div>
            </a>
            <a href="euip.php">
                <div class="card">
                    <div>
                        <div class="carddetails">Equipment Tracking</div>
                    </div>
                    <div class="iconBx">
                        <i class="fa-solid fa-dumbbell"></i>
                    </div>
                </div>
            </a>
            <a href="">
                <div class="card">
                    <div>
                        <div class="carddetails">Feedbacks and Comments</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script src="dash.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://kit.fontawesome.com/3d354331c2.js" crossorigin="anonymous"></script>
</body>

</html>
