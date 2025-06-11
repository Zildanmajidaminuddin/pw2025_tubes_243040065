<?php 
include('config/constants.php');

// Process login form submission BEFORE any HTML output
if(isset($_POST['submit'])) {
    // Get and sanitize input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $raw_password = $_POST['password'];
    $password = mysqli_real_escape_string($conn, md5($raw_password));

    // Check if fields are not empty
    if(empty($username) || empty($raw_password)) {
        $_SESSION['login'] = "Please fill in all fields.";
        $_SESSION['login_type'] = "error";
        header('location:'.SITEURL.'login.php');
        exit();
    }

    // SQL to check user credentials
    $sql = "SELECT * FROM tbl_admin WHERE username='$username' AND password='$password'";
    $res = mysqli_query($conn, $sql);

    if($res) {
        $count = mysqli_num_rows($res);
        
        if($count == 1) {
            // Get user data
            $row = mysqli_fetch_array($res);
            $user_role = $row['role'];
            $full_name = $row['full_name'];
            
            // Login successful
            $_SESSION['login'] = "Login Successful!";
            $_SESSION['login_type'] = "success";
            $_SESSION['user'] = $username;
            $_SESSION['user_role'] = $user_role;
            $_SESSION['full_name'] = $full_name;
            
            // Redirect based on role
            if($user_role == 'admin') {
                header('location:'.SITEURL.'admin/index.php');
            } else {
                header('location:'.SITEURL.'index.php'); // atau halaman untuk user biasa
            }
            exit();
        } else {
            // Login failed
            $_SESSION['login'] = "Invalid username or password. Please try again.";
            $_SESSION['login_type'] = "error";
            header('location:'.SITEURL.'login.php');
            exit();
        }
    } else {
        // Database query error
        $_SESSION['login'] = "Database connection error. Please try again.";
        $_SESSION['login_type'] = "error";
        header('location:'.SITEURL.'login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Food Order System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b6b, #feca57);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
            font-weight: 700;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .alert {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-container {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            margin: 30px 0;
            position: relative;
            text-align: center;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e5e9;
        }

        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 20px;
            color: #666;
            font-size: 0.9rem;
        }

        .register-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .register-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5e9;
            color: #999;
            font-size: 0.8rem;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-utensils"></i>
        </div>
        
        <h1>Welcome Back</h1>
        <p class="subtitle">Sign in to your Food Order System account</p>

        <?php 
            if(isset($_SESSION['login'])) {
                $alert_class = isset($_SESSION['login_type']) ? $_SESSION['login_type'] : 'error';
                echo '<div class="alert ' . $alert_class . '">' . $_SESSION['login'] . '</div>';
                unset($_SESSION['login']);
                unset($_SESSION['login_type']);
            }

            if(isset($_SESSION['no-login-message'])) {
                echo '<div class="alert error">' . $_SESSION['no-login-message'] . '</div>';
                unset($_SESSION['no-login-message']);
            }
        ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-container">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Enter your username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>
            </div>

            <button type="submit" name="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                Sign In
            </button>
        </form>

        <div class="divider">
            <span>Don't have an account?</span>
        </div>

        <a href="register.php" class="register-link">
            <i class="fas fa-user-plus" style="margin-right: 5px;"></i>
            Create New Account
        </a>

        <div class="footer">
            <p>Created with 💀 by <a href="#">Zildan M.A</a></p>
        </div>
    </div>
</body>
</html>