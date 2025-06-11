<?php 
include('config/constants.php');

// Process registration form submission BEFORE any HTML output
if(isset($_POST['submit'])) {
    // Get and sanitize input data
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $raw_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input fields
    if(empty($full_name) || empty($username) || empty($raw_password) || empty($confirm_password)) {
        $_SESSION['register'] = "Please fill in all fields.";
        $_SESSION['register_type'] = "error";
        header('location:'.SITEURL.'register.php');
        exit();
    }
    
    // Check if passwords match
    if($raw_password !== $confirm_password) {
        $_SESSION['register'] = "Passwords do not match.";
        $_SESSION['register_type'] = "error";
        header('location:'.SITEURL.'register.php');
        exit();
    }
    
    // Check password length
    if(strlen($raw_password) < 6) {
        $_SESSION['register'] = "Password must be at least 6 characters long.";
        $_SESSION['register_type'] = "error";
        header('location:'.SITEURL.'register.php');
        exit();
    }
    
    // Check if username already exists
    $check_username_sql = "SELECT username FROM tbl_admin WHERE username='$username'";
    $check_res = mysqli_query($conn, $check_username_sql);
    
    if(mysqli_num_rows($check_res) > 0) {
        $_SESSION['register'] = "Username already exists. Please choose a different username.";
        $_SESSION['register_type'] = "error";
        header('location:'.SITEURL.'register.php');
        exit();
    }
    
    // Hash the password
    $password = mysqli_real_escape_string($conn, md5($raw_password));
    
    // Insert new user into database with default role 'user'
    $sql = "INSERT INTO tbl_admin (full_name, username, password, role) VALUES ('$full_name', '$username', '$password', 'user')";
    $res = mysqli_query($conn, $sql);
    
    if($res) {
        $_SESSION['register'] = "Registration successful! You can now login.";
        $_SESSION['register_type'] = "success";
        header('location:'.SITEURL.'login.php');
        exit();
    } else {
        $_SESSION['register'] = "Registration failed. Please try again.";
        $_SESSION['register_type'] = "error";
        header('location:'.SITEURL.'register.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Food Order System</title>
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

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
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

        .login-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-link:hover {
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

        .password-requirements {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
            text-align: left;
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
            .register-container {
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
    <div class="register-container">
        <div class="logo">
            <i class="fas fa-user-plus"></i>
        </div>
        
        <h1>Create Account</h1>
        <p class="subtitle">Join the Food Order System today</p>

        <?php 
            if(isset($_SESSION['register'])) {
                $alert_class = isset($_SESSION['register_type']) ? $_SESSION['register_type'] : 'error';
                echo '<div class="alert ' . $alert_class . '">' . $_SESSION['register'] . '</div>';
                unset($_SESSION['register']);
                unset($_SESSION['register_type']);
            }
        ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <div class="input-container">
                    <i class="fas fa-id-card input-icon"></i>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           placeholder="Enter your full name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-container">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Choose a username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>
                <div class="password-requirements">
                    Password must be at least 6 characters long
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                           placeholder="Confirm your password" required>
                </div>
            </div>

            <button type="submit" name="submit" class="btn-primary">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                Create Account
            </button>
        </form>

        <div class="divider">
            <span>Already have an account?</span>
        </div>

        <a href="login.php" class="login-link">
            <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i>
            Sign In Here
        </a>

        <div class="footer">
            <p>Created with ❤️ by <a href="#">Zildan M.A</a></p>
        </div>
    </div>

    <script>
        // Simple password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword !== '') {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e1e5e9';
            }
        });
    </script>
</body>
</html>