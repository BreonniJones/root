<?php
session_start();
include('config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Query the database to check if the username exists
    $query = "SELECT Username, Password, Role FROM userdata WHERE Username = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Password is correct
        $hashedPassword = $user['Password'];
        $_SESSION['userlogin'] = $user['Username'];
        $_SESSION['userRole'] = $user['Role'];

        // Redirect to appropriate page based on user role
        if ($user['Role'] == 'Admin') {
            header('Location: admin.php');
        } elseif ($user['Role'] == 'Super Admin') {
            header('Location: superadmin.php');
        } elseif ($user['Role'] == 'User') {
            header('Location: user.php');
        }
    } else {
        // Invalid credentials, redirect back to login page with an error message
        $loginError = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <title>Login Page</title>
</head>
<body>
    <div class="wrapper">
        <div class="logo">
            <img src="image/adduser.jpeg">
        </div>
        <div class="text-center mt-4 name">
            LOGIN
        </div>
        <form class="p-3 mt-3" method="post">
            <?php if (isset($loginError)) : ?>
                <div class="alert alert-danger"><?php echo $loginError; ?></div>
            <?php endif; ?>
            <div class="form-field d-flex align-items-center">
                <span class="fa fa-user"></span>
                <input type="text" name="username" id="username" placeholder="Username">
            </div>
            <div class="form-field d-flex align-items-center">
                <span class="fa fa-key"></span>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn mt-3" name="login">Login</button>
        </form>
        <div class="text-center fs-6">
            <a href="#">Forget Password?</a> or <a href="signup.php">Sign up</a>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
