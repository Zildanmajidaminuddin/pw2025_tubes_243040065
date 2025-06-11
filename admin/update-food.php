<?php include('partials/menu.php'); ?>

<?php 
    //Check whether id is set or not 
    if(isset($_GET['id']))
    {
        //Get all the details
        $id = $_GET['id'];

        //SQL Query to Get the Selected Food
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
        //execute the Query
        $res2 = mysqli_query($conn, $sql2);

        //Check if food exists
        if(mysqli_num_rows($res2) == 1)
        {
            //Get the value based on query executed
            $row2 = mysqli_fetch_assoc($res2);

            //Get the Individual Values of Selected Food
            $title = $row2['title'];
            $description = $row2['description'];
            $price = $row2['price'];
            $current_image = $row2['image_name'];
            $current_category = $row2['category_id'];
            $featured = $row2['featured'];
            $active = $row2['active'];
        }
        else
        {
            //Food not found
            $_SESSION['no-food-found'] = "<div class='error'>Food not found.</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
    }
    else
    {
        //Redirect to Manage Food
        header('location:'.SITEURL.'admin/manage-food.php');
    }
?>

<!-- Enhanced CSS -->
<style>
.main-content {
    background: #f8f9fa;
    min-height: 100vh;
}

.form-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    padding: 30px;
    margin: 20px 0;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    margin: -30px -30px 30px -30px;
}

.form-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 300;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.form-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.form-section h3 {
    margin-top: 0;
    color: #333;
    font-size: 18px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.file-upload-container {
    position: relative;
    display: inline-block;
    width: 100%;
}

.file-upload-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-upload-label {
    display: block;
    padding: 12px 15px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-label:hover {
    background: #e9ecef;
    border-color: #667eea;
}

.file-upload-label.has-file {
    background: #e8f4fd;
    border-color: #667eea;
    color: #667eea;
}

.current-image-container {
    text-align: center;
    margin-bottom: 15px;
}

.current-image {
    max-width: 200px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.image-preview-container {
    text-align: center;
    margin-top: 15px;
    display: none;
}

.image-preview {
    max-width: 200px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.radio-group {
    display: flex;
    gap: 20px;
}

.radio-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.radio-item input[type="radio"] {
    width: auto;
    margin: 0;
}

.btn-update {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 150px;
}

.btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin-right: 15px;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.preview-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-top: 20px;
}

.preview-card-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
}

.preview-card-body {
    padding: 20px;
}

.price-display {
    font-size: 24px;
    font-weight: bold;
    color: #28a745;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .radio-group {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<div class="main-content">
    <div class="wrapper">
        <div class="form-container">
            <div class="form-header">
                <h1>Update Food Item</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">Edit and update food information</p>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" id="updateFoodForm">
                <div class="form-grid">
                    <!-- Left Column - Basic Information -->
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        
                        <div class="form-group">
                            <label for="title">Food Title</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Describe the food item..."><?php echo htmlspecialchars($description); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <input type="number" id="price" name="price" value="<?php echo $price; ?>" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <?php 
                                    //Query to Get Active Categories
                                    $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                    //Execute the Query
                                    $res = mysqli_query($conn, $sql);
                                    //Count Rows
                                    $count = mysqli_num_rows($res);

                                    //Check whether category available or not
                                    if($count>0)
                                    {
                                        //Category Available
                                        while($row=mysqli_fetch_assoc($res))
                                        {
                                            $category_title = $row['title'];
                                            $category_id = $row['id'];
                                            ?>
                                            <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo htmlspecialchars($category_title); ?></option>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        //Category Not Available
                                        echo "<option value='0'>No Categories Available</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column - Image and Settings -->
                    <div class="form-section">
                        <h3>Image & Settings</h3>
                        
                        <div class="form-group">
                            <label>Current Image</label>
                            <div class="current-image-container">
                                <?php 
                                    if($current_image == "")
                                    {
                                        //Image not Available 
                                        echo "<div class='error'>No image available</div>";
                                    }
                                    else
                                    {
                                        //Image Available
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" class="current-image" alt="Current food image">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload New Image</label>
                            <div class="file-upload-container">
                                <input type="file" id="image" name="image" class="file-upload-input" accept="image/*">
                                <label for="image" class="file-upload-label">
                                    <i>📁</i> Choose new image or drag & drop
                                </label>
                            </div>
                            <div class="image-preview-container">
                                <img id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Featured Item</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input <?php if($featured=="Yes") {echo "checked";} ?> type="radio" id="featured_yes" name="featured" value="Yes">
                                    <label for="featured_yes">Yes</label>
                                </div>
                                <div class="radio-item">
                                    <input <?php if($featured=="No") {echo "checked";} ?> type="radio" id="featured_no" name="featured" value="No">
                                    <label for="featured_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input <?php if($active=="Yes") {echo "checked";} ?> type="radio" id="active_yes" name="active" value="Yes">
                                    <label for="active_yes">Active</label>
                                </div>
                                <div class="radio-item">
                                    <input <?php if($active=="No") {echo "checked";} ?> type="radio" id="active_no" name="active" value="No">
                                    <label for="active_no">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Card -->
                <div class="preview-card">
                    <div class="preview-card-header">
                        <h3>Live Preview</h3>
                    </div>
                    <div class="preview-card-body">
                        <h4 id="preview-title"><?php echo htmlspecialchars($title); ?></h4>
                        <div class="price-display" id="preview-price">$<?php echo $price; ?></div>
                        <p id="preview-description"><?php echo htmlspecialchars($description); ?></p>
                        <small id="preview-status">
                            Status: <span class="badge <?php echo $active == 'Yes' ? 'badge-success' : 'badge-danger'; ?>"><?php echo $active; ?></span>
                            <?php if($featured == 'Yes'): ?> | <span class="badge badge-warning">Featured</span><?php endif; ?>
                        </small>
                    </div>
                </div>

                <!-- Hidden fields and Submit -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                <div style="text-align: center; margin-top: 30px;">
                    <a href="<?php echo SITEURL; ?>admin/manage-food.php" class="btn-cancel">Cancel</a>
                    <input type="submit" name="submit" value="Update Food Item" class="btn-update">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Enhanced Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image upload preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.querySelector('.image-preview-container');
    const fileUploadLabel = document.querySelector('.file-upload-label');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
                fileUploadLabel.textContent = file.name;
                fileUploadLabel.classList.add('has-file');
            };
            reader.readAsDataURL(file);
        }
    });

    // Live preview updates
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const priceInput = document.getElementById('price');
    const activeRadios = document.querySelectorAll('input[name="active"]');
    const featuredRadios = document.querySelectorAll('input[name="featured"]');

    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const previewPrice = document.getElementById('preview-price');
    const previewStatus = document.getElementById('preview-status');

    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Food Title';
    });

    descriptionInput.addEventListener('input', function() {
        previewDescription.textContent = this.value || 'Food description...';
    });

    priceInput.addEventListener('input', function() {
        previewPrice.textContent = '$' + (this.value || '0.00');
    });

    function updateStatusPreview() {
        const activeValue = document.querySelector('input[name="active"]:checked')?.value || 'No';
        const featuredValue = document.querySelector('input[name="featured"]:checked')?.value || 'No';
        
        let statusHtml = 'Status: <span class="badge ' + 
                        (activeValue === 'Yes' ? 'badge-success' : 'badge-danger') + 
                        '">' + activeValue + '</span>';
        
        if (featuredValue === 'Yes') {
            statusHtml += ' | <span class="badge badge-warning">Featured</span>';
        }
        
        previewStatus.innerHTML = statusHtml;
    }

    activeRadios.forEach(radio => {
        radio.addEventListener('change', updateStatusPreview);
    });

    featuredRadios.forEach(radio => {
        radio.addEventListener('change', updateStatusPreview);
    });

    // Form validation
    const form = document.getElementById('updateFoodForm');
    form.addEventListener('submit', function(e) {
        const title = titleInput.value.trim();
        const price = priceInput.value;

        if (!title) {
            alert('Please enter a food title.');
            e.preventDefault();
            titleInput.focus();
            return;
        }

        if (!price || price <= 0) {
            alert('Please enter a valid price.');
            e.preventDefault();
            priceInput.focus();
            return;
        }
    });
});
</script>

<style>
.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}
</style>

<?php 
    if(isset($_POST['submit']))
    {
        //1. Get all the details from the form
        $id = intval($_POST['id']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        $current_image = $_POST['current_image'];
        $category = intval($_POST['category']);
        $featured = $_POST['featured'];
        $active = $_POST['active'];

        //2. Upload the image if selected
        $image_name = $current_image; // Default to current image

        //Check whether upload button is clicked or not
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
        {
            //Upload Button Clicked
            $new_image_name = $_FILES['image']['name'];

            //Check whether the file is available or not
            if($new_image_name != "")
            {
                // Validate file type
                $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
                $file_ext = strtolower(pathinfo($new_image_name, PATHINFO_EXTENSION));
                
                if(in_array($file_ext, $allowed_types))
                {
                    //Rename the Image
                    $image_name = "Food-".uniqid().'-'.rand(1000, 9999).'.'.$file_ext;

                    //Get the Source Path and Destination Path
                    $src_path = $_FILES['image']['tmp_name'];
                    $dest_path = "../images/food/".$image_name;

                    //Upload the image
                    $upload = move_uploaded_file($src_path, $dest_path);

                    //Check whether the image is uploaded or not
                    if($upload == false)
                    {
                        //Failed to Upload
                        $_SESSION['upload'] = "<div class='error'>Failed to upload new image.</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                        die();
                    }

                    //Remove current image if available
                    if($current_image != "" && file_exists("../images/food/".$current_image))
                    {
                        unlink("../images/food/".$current_image);
                    }
                }
                else
                {
                    $_SESSION['upload'] = "<div class='error'>Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                    die();
                }
            }
        }

        //4. Update the Food in Database
        $sql3 = "UPDATE tbl_food SET 
            title = '$title',
            description = '$description',
            price = $price,
            image_name = '$image_name',
            category_id = $category,
            featured = '$featured',
            active = '$active'
            WHERE id = $id
        ";

        //Execute the SQL Query
        $res3 = mysqli_query($conn, $sql3);

        //Check whether the query is executed or not 
        if($res3 == true)
        {
            //Query Executed and Food Updated
            $_SESSION['update'] = "<div class='success'>Food item updated successfully.</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //Failed to Update Food
            $_SESSION['update'] = "<div class='error'>Failed to update food item.</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
    }
?>

<?php include('partials/footer.php'); ?>