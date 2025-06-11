<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <div class="main-content">
            <div class="wrapper">
                <h1>Manage Admin</h1>

                <br />

                <?php 
                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add']; //Displaying Session Message
                        unset($_SESSION['add']); //REmoving Session Message
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];
                        unset($_SESSION['delete']);
                    }
                    
                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }

                    if(isset($_SESSION['user-not-found']))
                    {
                        echo $_SESSION['user-not-found'];
                        unset($_SESSION['user-not-found']);
                    }

                    if(isset($_SESSION['pwd-not-match']))
                    {
                        echo $_SESSION['pwd-not-match'];
                        unset($_SESSION['pwd-not-match']);
                    }

                    if(isset($_SESSION['change-pwd']))
                    {
                        echo $_SESSION['change-pwd'];
                        unset($_SESSION['change-pwd']);
                    }

                ?>
                <br><br><br>

                <!-- Button to Add Admin -->
                <a href="add-admin.php" class="btn-primary">Add Admin</a>

                <br /><br /><br />

                <?php
                    // Pagination setup
                    $records_per_page = 4; // Jumlah admin per halaman
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($current_page - 1) * $records_per_page;

                    // Query untuk menghitung total records
                    $count_sql = "SELECT COUNT(*) as total FROM tbl_admin";
                    $count_res = mysqli_query($conn, $count_sql);
                    $total_records = mysqli_fetch_assoc($count_res)['total'];
                    $total_pages = ceil($total_records / $records_per_page);
                ?>

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>

                    
                    <?php 
                        //Query to Get admin with pagination
                        $sql = "SELECT * FROM tbl_admin LIMIT $records_per_page OFFSET $offset";
                        //Execute the Query
                        $res = mysqli_query($conn, $sql);

                        //CHeck whether the Query is Executed of Not
                        if($res==TRUE)
                        {
                            // Count Rows to CHeck whether we have data in database or not
                            $count = mysqli_num_rows($res); // Function to get all the rows in database

                            $sn = ($current_page - 1) * $records_per_page + 1; //Create a Variable and Assign the value

                            //CHeck the num of rows
                            if($count>0)
                            {
                                //WE HAve data in database
                                while($rows=mysqli_fetch_assoc($res))
                                {
                                    //Using While loop to get all the data from database.
                                    //And while loop will run as long as we have data in database

                                    //Get individual DAta
                                    $id=$rows['id'];
                                    $full_name=$rows['full_name'];
                                    $username=$rows['username'];

                                    //Display the Values in our Table
                                    ?>
                                    
                                    <tr>
                                        <td><?php echo $sn++; ?>. </td>
                                        <td><?php echo $full_name; ?></td>
                                        <td><?php echo $username; ?></td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>admin/update-password.php?id=<?php echo $id; ?>" class="btn-primary">Change Password</a>
                                            <a href="<?php echo SITEURL; ?>admin/update-admin.php?id=<?php echo $id; ?>" class="btn-secondary">Update Admin</a>
                                            <a href="<?php echo SITEURL; ?>admin/delete-admin.php?id=<?php echo $id; ?>" class="btn-danger">Delete Admin</a>
                                        </td>
                                    </tr>

                                    <?php

                                }
                            }
                            else
                            {
                                //We Do not Have Data in Database
                                ?>
                                <tr>
                                    <td colspan="4">No admin found.</td>
                                </tr>
                                <?php
                            }
                        }

                    ?>

                </table>

                <!-- Pagination Links -->
                <?php if($total_pages > 1): ?>
                <div class="pagination" style="margin-top: 20px; text-align: center;">
                    <?php if($current_page > 1): ?>
                        <a href="?page=1" class="btn-secondary">First</a>
                        <a href="?page=<?php echo $current_page - 1; ?>" class="btn-secondary">Previous</a>
                    <?php endif; ?>

                    <?php
                    // Tampilkan nomor halaman
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);
                    
                    for($i = $start_page; $i <= $end_page; $i++):
                    ?>
                        <?php if($i == $current_page): ?>
                            <span class="current-page" style="padding: 8px 12px; background-color: #007cba; color: white; margin: 0 2px; border-radius: 3px;"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" class="btn-secondary" style="margin: 0 2px;"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?>" class="btn-secondary">Next</a>
                        <a href="?page=<?php echo $total_pages; ?>" class="btn-secondary">Last</a>
                    <?php endif; ?>
                </div>

                <!-- Info pagination -->
                 <br>
                <div style="margin-top: 10px; text-align: center; color: #666;">
                    Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $records_per_page, $total_records); ?> of <?php echo $total_records; ?> entries
                </div>
                <?php endif; ?>

            </div>
        </div>
        <!-- Main Content Setion Ends -->

<?php include('partials/footer.php'); ?>