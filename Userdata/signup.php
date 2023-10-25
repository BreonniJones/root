<?php
session_start();
include('config/db.php');

// Define variables to hold error messages and username pattern
$usernameError = '';
$emailError = '';
$passwordError = '';

// Define a regular expression pattern for the username
$usernamePattern = '/^[a-zA-Z0-9_]{3,20}$/';

if (isset($_POST['Signup'])) {
    $Username = $_POST['Username'];
    $UserEmail = $_POST['UserEmail'];
    $UserPassword = $_POST['Password'];

    // Password validation regex
    $passwordPattern = '/^(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/';

    if (
        preg_match($passwordPattern, $UserPassword) &&
        preg_match($usernamePattern, $Username) &&
        filter_var($UserEmail, FILTER_VALIDATE_EMAIL)
    ) {
        // Password, Username, and Email are valid

        // Password Hashing
        $options = ['cost' => 12];
        $hashedpass = password_hash($UserPassword, PASSWORD_BCRYPT, $options);

        // Query for validation of username and email
        $query = "SELECT * FROM userdata WHERE (Username=? OR UserEmail=?)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $Username);
        $stmt->bindParam(2, $UserEmail);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($stmt->rowCount() == 0) {
            // Valid Username and Email

            // Query to insert
            $query = "INSERT INTO userdata (Username, UserEmail, Password) VALUES (?, ?, ?)";

            // Prepare query
            $stmt = $con->prepare($query);

            // Binding
            $stmt->bindParam(1, $Username, PDO::PARAM_STR);
            $stmt->bindParam(2, $UserEmail, PDO::PARAM_STR);
            $stmt->bindParam(3, $hashedpass, PDO::PARAM_STR);

            // Execute Query
            $stmt->execute();

            // Redirect to the login page after successful registration
            header('location: index.php');
            exit;
        } else {
            $usernameError = 'Username or Email already exists';
        }
    } else {
        if (!preg_match($usernamePattern, $Username)) {
            $usernameError = 'Invalid Username';
        }
        if (!filter_var($UserEmail, FILTER_VALIDATE_EMAIL)) {
            $emailError = 'Invalid Email';
        }
        if (!preg_match($passwordPattern, $UserPassword)) {
            $passwordError = 'Invalid Password';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <style>
        .error-message {
            color: red;
        }

        .form-field {
            margin-bottom: 10px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('#loaderIcon').hide(); // Hide the loader initially

            $('#Username').on('blur', function () {
                checkUsernameAvailability();
            });

            $('#UserEmail').on('blur', function () {
                checkUserEmailAvailability();
            });

            function checkUsernameAvailability() {
                $('#loaderIcon').show(); // Show loader while checking

                $.ajax({
                    url: "checkavail.php",
                    data: { Username: $("#Username").val() },
                    type: "POST",
                    success: function (data) {
                        $("#username-availability-status").html(data);
                        $('#loaderIcon').hide(); // Hide loader after checking
                    },
                    error: function () {
                        // Handle error if needed
                    }
                });
            }

            function checkUserEmailAvailability() {
                $('#loaderIcon').show(); // Show loader while checking

                $.ajax({
                    url: "checkavail.php",
                    data: { UserEmail: $("#UserEmail").val() },
                    type: "POST",
                    success: function (data) {
                        $("#useremail-availability-status").html(data);
                        $('#loaderIcon').hide(); // Hide loader after checking
                    },
                    error: function () {
                        // Handle error if needed
                    }
                });
            }
        });
    </script>
    <title>Sign Up Form</title>
</head>
<body>
    <div class="wrapper">
        <div class="logo">
            <img src="image/adduser.jpeg">
        </div>
        <div class="text-center mt-4 name">
            Sign Up
        </div>
        <form class="p-3 mt-3" method="post">
            <div class="form-field">
                <span id="username-availability-status" style="font-size:12px;"></span>
                <label for="Username">Username</label>
                <input type="text" name="Username" id="Username" placeholder="Username">
                <div class="error-message"><?php echo $usernameError; ?></div>
            </div>
            <div class="form-field">
                <span id="useremail-availability-status" style="font-size:12px;"></span>
                <label for="UserEmail">Email</label>
                <input type="text" name="UserEmail" id="UserEmail" placeholder="Email">
                <div class="error-message"><?php echo $emailError; ?></div>
            </div>
            <div class="form-field">
                <label for="UserPassword">Password</label>
                <input type="Password" name="Password" id="UserPassword" placeholder="Password">
                <div class="error-message"><?php echo $passwordError; ?></div>
            </div>
            <button type="submit" class="btn mt-3" name="Signup">Sign Up</button>
            <a href="index.php" class="btn mt-3">Return Home</a>
        </form>
    </div>
</body>
</html>
