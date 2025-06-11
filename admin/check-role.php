<?php
// check-role.php - File untuk mengecek role dan melindungi halaman admin

// Function untuk mengecek apakah user sudah login
function checkLogin() {
    if(!isset($_SESSION['user'])) {
        $_SESSION['no-login-message'] = "Please login to access this page.";
        header('location:'.SITEURL.'login.php');
        exit();
    }
}

// Function untuk mengecek apakah user adalah admin
function checkAdminRole() {
    checkLogin(); // Pastikan user sudah login dulu
    
    if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
        $_SESSION['no-access-message'] = "Access denied. Admin privileges required.";
        header('location:'.SITEURL.'index.php'); // redirect ke halaman user
        exit();
    }
}

// Function untuk mengecek apakah user adalah user biasa
function checkUserRole() {
    checkLogin(); // Pastikan user sudah login dulu
    
    if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'user') {
        $_SESSION['no-access-message'] = "Access denied. User privileges required.";
        header('location:'.SITEURL.'./index.php'); // redirect ke halaman admin
        exit();
    }
}

// Function untuk mendapatkan informasi user yang sedang login
function getCurrentUser() {
    return array(
        'username' => isset($_SESSION['user']) ? $_SESSION['user'] : '',
        'role' => isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '',
        'full_name' => isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''
    );
}

// Function untuk logout
function logout() {
    session_start();
    session_destroy();
    header('location:'.SITEURL.'./login.php');
    exit();
}
?>