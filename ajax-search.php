<?php
// Include constants (adjust path as needed)
include('config/constants.php');

// Set content type to JSON
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Check if loading all foods
    if (isset($_POST['load_all'])) {
        $foodMenuHtml = loadAllFoods($conn);
        echo json_encode([
            'success' => true,
            'foodMenu' => $foodMenuHtml,
            'results' => []
        ]);
        exit;
    }

    // Get search query
    if (!isset($_POST['search']) || empty(trim($_POST['search']))) {
        echo json_encode(['success' => false, 'message' => 'Search query is required']);
        exit;
    }

    $search = mysqli_real_escape_string($conn, trim($_POST['search']));
    
    // Minimum search length
    if (strlen($search) < 2) {
        echo json_encode(['success' => false, 'message' => 'Search query too short']);
        exit;
    }

    // Search for dropdown results (limited for performance)
    $dropdownResults = searchFoodsForDropdown($conn, $search);
    
    // Search for full menu display
    $foodMenuHtml = searchFoodsForMenu($conn, $search);

    echo json_encode([
        'success' => true,
        'results' => $dropdownResults,
        'foodMenu' => $foodMenuHtml
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

function searchFoodsForDropdown($conn, $search) {
    $sql = "SELECT f.id, f.title, f.price, c.title as category_name 
            FROM tbl_food f 
            INNER JOIN tbl_category c ON f.category_id = c.id 
            WHERE (f.title LIKE '%$search%' OR f.description LIKE '%$search%' OR c.title LIKE '%$search%') 
            AND f.active='Yes' AND c.active='Yes'
            ORDER BY f.title ASC
            LIMIT 10"; // Limit untuk dropdown

    $res = mysqli_query($conn, $sql);
    $results = [];

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $results[] = [
                'id' => $row['id'],
                'title' => htmlspecialchars($row['title']),
                'price' => number_format($row['price'], 2),
                'category_name' => htmlspecialchars($row['category_name'])
            ];
        }
    }

    return $results;
}

function searchFoodsForMenu($conn, $search) {
    $sql = "SELECT f.*, c.title as category_name 
            FROM tbl_food f 
            INNER JOIN tbl_category c ON f.category_id = c.id 
            WHERE (f.title LIKE '%$search%' OR f.description LIKE '%$search%' OR c.title LIKE '%$search%') 
            AND f.active='Yes' AND c.active='Yes'
            ORDER BY f.title ASC
            LIMIT 50"; // Limit untuk menampilkan hasil

    $res = mysqli_query($conn, $sql);
    $html = '';

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $title = htmlspecialchars($row['title']);
            $price = number_format($row['price'], 2);
            $description = htmlspecialchars($row['description']);
            $image_name = $row['image_name'];
            $category_name = htmlspecialchars($row['category_name']);

            $html .= '<div class="food-menu-box">';
            $html .= '<div class="food-menu-img">';
            
            if ($image_name == "") {
                $html .= '<div class="error">Image not Available.</div>';
            } else {
                $html .= '<img src="' . SITEURL . 'images/food/' . $image_name . '" alt="' . $title . '" class="img-responsive img-curve">';
            }
            
            $html .= '</div>';
            $html .= '<div class="food-menu-desc">';
            $html .= '<div class="category-badge">';
            $html .= '<span class="category-tag">' . $category_name . '</span>';
            $html .= '</div>';
            $html .= '<h4>' . $title . '</h4>';
            $html .= '<p class="food-price">$' . $price . '</p>';
            $html .= '<p class="food-detail">' . $description . '</p>';
            $html .= '<br>';
            $html .= '<a href="' . SITEURL . 'order.php?food_id=' . $id . '" class="btn btn-primary">Order Now</a>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html = '<div class="no-results">';
        $html .= '<h3>No foods found</h3>';
        $html .= '<p>Sorry, we couldn\'t find any foods matching "<strong>' . htmlspecialchars($search) . '</strong>"</p>';
        $html .= '<p>Try searching with different keywords or browse our <a href="' . SITEURL . 'foods.php">complete menu</a>.</p>';
        $html .= '</div>';
    }

    return $html;
}

function loadAllFoods($conn) {
    $sql = "SELECT f.*, c.title as category_name 
            FROM tbl_food f 
            INNER JOIN tbl_category c ON f.category_id = c.id 
            WHERE f.active='Yes' AND c.active='Yes'
            ORDER BY f.title ASC
            LIMIT 20"; // Limit untuk performa awal

    $res = mysqli_query($conn, $sql);
    $html = '';

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $title = htmlspecialchars($row['title']);
            $price = number_format($row['price'], 2);
            $description = htmlspecialchars($row['description']);
            $image_name = $row['image_name'];
            $category_name = htmlspecialchars($row['category_name']);

            $html .= '<div class="food-menu-box">';
            $html .= '<div class="food-menu-img">';
            
            if ($image_name == "") {
                $html .= '<div class="error">Image not Available.</div>';
            } else {
                $html .= '<img src="' . SITEURL . 'images/food/' . $image_name . '" alt="' . $title . '" class="img-responsive img-curve">';
            }
            
            $html .= '</div>';
            $html .= '<div class="food-menu-desc">';
            $html .= '<div class="category-badge">';
            $html .= '<span class="category-tag">' . $category_name . '</span>';
            $html .= '</div>';
            $html .= '<h4>' . $title . '</h4>';
            $html .= '<p class="food-price">$' . $price . '</p>';
            $html .= '<p class="food-detail">' . $description . '</p>';
            $html .= '<br>';
            $html .= '<a href="' . SITEURL . 'order.php?food_id=' . $id . '" class="btn btn-primary">Order Now</a>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html = '<div class="no-results">';
        $html .= '<h3>No foods available</h3>';
        $html .= '<p>Currently there are no foods available in our menu.</p>';
        $html .= '</div>';
    }

    return $html;
}
?>