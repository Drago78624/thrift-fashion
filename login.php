<?php
require "partials/_connection.php";

$registerFullName = $registerEmail = $registerUsername = $registerPhone = $registerPassword = "";
$loginEmail = $loginPassword = "";
$errMsgRegister = "";
$errMsgLogin = "";
$showAlert = false;

if (isset($_POST['register'])) {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $registerFullName = test_input($_POST['register-name']);
    $registerEmail = test_input($_POST['register-email']);
    $registerUsername = test_input($_POST['register-username']);
    $registerPhone = test_input($_POST['register-phone']);
    $registerPassword = test_input($_POST['register-password']);
    $passwordHash = password_hash($registerPassword, PASSWORD_DEFAULT);
    
    $stmt = $mysqli->prepare("SELECT email FROM `user` WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $registerEmail, $registerUsername);
    $stmt->execute();
    $existenceCheckingResult = $stmt->get_result();
    $num = mysqli_num_rows($existenceCheckingResult);
    if ($num) {
        $errMsgRegister = "User already exists";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO `user` (`name`, `email`, `contact`, `username`, `password`) VALUES (?, ?, ?, ?, ?);");
        $stmt->bind_param("sssss", $registerFullName, $registerEmail, $registerPhone, $registerUsername,  $passwordHash);
        $stmt->execute();
        $signupResult = $stmt->get_result();

        $stmt = $mysqli->prepare("SELECT * FROM `user` WHERE email = ?");
        $stmt->bind_param("s", $registerEmail);
        $stmt->execute();
        $signupResult = $stmt->get_result();

        if($signupResult){
            $showAlert = true;
        }

        $registerFullName = $registerEmail = $registerUsername = $registerPhone =  $registerPassword = "";
    }
}
if (isset($_POST['login'])) {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    $loginEmail = test_input($_POST['login-email']);
    $loginPassword = test_input($_POST['login-password']);
    
    $stmt =  $mysqli->prepare("SELECT * FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $loginEmail);
    $stmt->execute();
    $loginResult = $stmt->get_result();
    $num = mysqli_num_rows($loginResult);
    if($num){
        $row = mysqli_fetch_assoc($loginResult);
        $userid = $row['user_id'];
        if(password_verify($loginPassword, $row['password'])){
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: index.php");
        }else {
            $errMsgLogin = "Bad email or password";
        }
        }else {
            $errMsgLogin = "Bad email or password";
        }
    }

        $registerFullName = $registerEmail = $registerUsername = $registerPhone =  $registerPassword = "";
?>
<!DOCTYPE html>
<html lang="en">
<!-- molla/login.html  22 Nov 2019 10:04:03 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thrift Fashion - Login / Register</title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - Bootstrap eCommerce Template">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#007bff">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/skins/skin-demo-4.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">
</head>

<body>
    <div class="page-wrapper">
        <?php require "partials/_header.php" ?>
        <?php if($showAlert): ?>
            <div class="container">
                <div class="alert alert-success">
                    <strong>Success!</strong> You account has been registered
                </div>
            </div>
        <?php endif; ?>
        <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login / Register</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->
            <div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17"
            style="background-image: url('assets/images/backgrounds/login-bg.jpg')">
                <div class="container">
                    <div class="form-box">
                        <div class="form-tab">
                            <ul class="nav nav-pills nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="signin-tab-2" data-toggle="tab" href="#signin-2" role="tab"
                                        aria-controls="signin-2" aria-selected="false">Log In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="register-tab-2" data-toggle="tab" href="#register-2"
                                        role="tab" aria-controls="register-2" aria-selected="true">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade" id="signin-2" role="tabpanel" aria-labelledby="signin-tab-2">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <div class="form-group">
                                            <label for="login-email">Email address *</label>
                                            <input type="email" class="form-control mb-1" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="login-email"
                                                name="login-email" required>
                                                <span class="text-danger"><?php echo htmlspecialchars($errMsgLogin) ?></span>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="login-password">Password *</label>
                                            <input type="password" class="form-control" id="login-password"
                                                name="login-password" required>
                                        </div><!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit" name="login" value="LOG IN" class="btn btn-outline-primary-2">
                                                <span>LOG IN</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>
                                        </div><!-- End .form-footer -->
                                    </form>
                                </div><!-- .End .tab-pane -->
                                <div class="tab-pane fade show active" id="register-2" role="tabpanel"
                                    aria-labelledby="register-tab-2">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                        <div class="form-group">
                                            <label for="register-name">Full Name *</label>
                                            <input type="text" class="form-control" id="register-name"
                                                name="register-name" required>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-email">Email address *</label>
                                            <input type="email" class="form-control mb-1" id="register-email"
                                                name="register-email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                                                <span class="text-danger"><?php echo htmlspecialchars($errMsgRegister) ?></span>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-username">Username *</label>
                                            <input type="text" class="form-control mb-1" id="register-username"
                                                name="register-username" pattern="[a-zA-Z0-9](_(?!(\.|_))|\.(?!(_|\.))|[a-zA-Z0-9]){6,18}[a-zA-Z0-9]$" required>
                                                <span class="text-danger"><?php echo htmlspecialchars($errMsgRegister) ?></span>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-phone">Phone Number *</label>
                                            <input type="tel" class="form-control" id="register-phone"
                                                name="register-phone" required>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-password">Password *</label>
                                            <input type="password" class="form-control" id="register-password"
                                                name="register-password" pattern="[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,64}$" required>
                                        </div><!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit" name="register" value="REGISTER"
                                                class="btn btn-outline-primary-2">
                                                <span>REGISTER</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="register-policy-2" required>
                                                <label class="custom-control-label" for="register-policy-2">I agree to
                                                    the <a href="#">privacy policy</a> *</label>
                                            </div><!-- End .custom-checkbox -->
                                        </div><!-- End .form-footer -->
                                    </form>
                                </div><!-- .End .tab-pane -->
                            </div><!-- End .tab-content -->
                        </div><!-- End .form-tab -->
                    </div><!-- End .form-box -->
                </div><!-- End .container -->
            </div><!-- End .login-page section-bg -->
        </main><!-- End .main -->

        <?php require "partials/_footer.php"; ?>
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    <?php require "partials/_mobile-menu.php"; ?>

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/login.html  22 Nov 2019 10:04:03 GMT -->

</html>