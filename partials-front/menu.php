<?php 

include('config/constants.php'); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takashi Food</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="./login.php" title="Logo">
                    <img src="./images/Takahashi 11.jpg" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li>
                        <a href="<?php echo SITEURL; ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>categories.php">Categories</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>foods.php">Foods</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                    <li>
                        <?php
                        if (isset($_SESSION['user'])) {
                            // Jika sudah login, tampilkan tombol Logout
                            echo '<a href="' . SITEURL . 'admin/logout.php">Logout</a>';
                        } else {
                            // Jika belum login, tampilkan tombol Login
                            echo '<a href="' . SITEURL . 'login.php">Login</a>';
                        }
                        ?>
                    </li>

                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->