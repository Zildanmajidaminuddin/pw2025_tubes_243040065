<?php 
// Include Constants File
include('../config/constants.php');

// Check whether the id and image_name value is set or not
if(isset($_GET['id']) && isset($_GET['image_name'])) {
    $id = $_GET['id'];
    $image_name = $_GET['image_name'];

    // Remove the physical image file if available
    if($image_name != "") {
        $path = "../images/category/" . $image_name;

        // Check if file exists first
        if(file_exists($path)) {
            $remove = unlink($path);

            if($remove == false) {
                $_SESSION['remove'] = "<div class='error'>Failed to remove category image. Permission or file lock issue.</div>";
                header('location:' . SITEURL . 'admin/manage-category.php');
                die();
            }
        } else {
            $_SESSION['remove'] = "<div class='error'>Image file not found at: $path</div>";
            header('location:' . SITEURL . 'admin/manage-category.php');
            die();
        }
    }

    // Delete data from database
    $sql = "DELETE FROM tbl_category WHERE id=$id";
    $res = mysqli_query($conn, $sql);

    if($res == true) {
        $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully.</div>";
    } else {
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Category from database.</div>";
    }

    header('location:' . SITEURL . 'admin/manage-category.php');
} else {
    // Redirect if data not set
    header('location:' . SITEURL . 'admin/manage-category.php');
}
?>
