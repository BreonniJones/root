<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <title>PHP & MySQL CRUD</title>
</head>
<style type="text/css">
    .btn-space {
        margin-right: 5px;
    }

    .error {
        color: #FF0000;
    }
</style>

<body>
<?php
$first_nameError = "";
$last_nameError = "";
$emailError = "";
$isfirst_nameValid = false;
$islast_nameValid = false;
$isemailValid = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['first_name'])) {
        $first_nameError = "First Name is required!";
    } else {
        $first_name = $_POST['first_name'];
        $isfirst_nameValid = true;
    }

    if (empty($_POST['last_name'])) {
        $last_nameError = "Last Name is required!";
    } else {
        $last_name = $_POST['last_name'];
        $islast_nameValid = true;
    }

    if (empty($_POST['email'])) {
        $emailError = "Email is required!";
    } else {
        $email = $_POST['email'];
        $isemailValid = true;
    }

    if ($isfirst_nameValid && $islast_nameValid && $isemailValid) {
        // include database connection
        include "Config/db.php";

        try {
            // insert query
            $query = "INSERT INTO customer (first_name, last_name, email) VALUES (?, ?, ?)";

            // prepare query for execution
            $stmt = $con->prepare($query);

            // bind parameters
            $stmt->bindParam(1, $first_name);
            $stmt->bindParam(2, $last_name);
            $stmt->bindParam(3, $email);

            // execute the query
            if ($stmt->execute()) {
                // Display success message
                echo '<div class="alert alert-success"><strong>Customer was added!</strong></div>';

                // Determine where to redirect based on the user role (you may need to modify this logic)
                if ($_SESSION['userRole'] == 'User') {
                    header('location: user.php');
                } elseif ($_SESSION['userRole'] == 'Admin') {
                    header('location: admin.php');
                } elseif ($_SESSION['userRole'] == 'Super Admin') {
                    header('location: superadmin.php');
                } else {
                    // Handle other roles or provide a default redirect
                    header('location: index.php');
                }
                exit(); // Important: Exit to prevent further execution
            } else {
                echo '<div class="alert alert-danger"><strong>Unable to save the record!</strong></div>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<div class="container mt-3">
    <h2>Add User Info</h2>
    <table class="table table-bordered">
        <tbody>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <tr>
                <td>First Name</td>
                <td>
                    <input type="text" class="form-control" id="first_name" placeholder="Enter First name" name="first_name">
                    <span class="error"><?php echo $first_nameError; ?></span>
                </td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td>
                    <input type="text" class="form-control" id="last_name" placeholder="Enter Last name" name="last_name">
                    <span class="error"><?php echo $last_nameError; ?></span>
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                    <span class="error"><?php echo $emailError; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="Submit" class="btn btn-success btn-block" value="Add Customer">
                </td>
            </tr>
        </form>
        <tr>
            <td colspan="2">
                <a href="index.php" class="btn btn-danger btn-block">Cancel</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
