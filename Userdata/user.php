<?php
session_start();
// Include Database
include('config/db.php');

if (strlen($_SESSION['userlogin']) == 0) {
    header('location: index.php');
} elseif ($_SESSION['userRole'] != 'User') {
    include 'logout.php';
    header('location: index.php');
}

// Function to sanitize user input
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addCustomer'])) {
        // Handle adding a new customer (implement server-side validation as needed)
        $first_nameame = sanitize_input($_POST['first_name']);
        $las_nameame = sanitize_input($_POST['last_name']);
        $email = sanitize_input($_POST['email']);
        $address_id = sanitize_input($_POST['address_id']);
        $active = sanitize_input($_POST['active']);

        // Your INSERT query here to add the new customer to the database
        // Don't forget to validate and sanitize user input before inserting into the database
        // Example:
        // $query = "INSERT INTO customer (first_name, last_name, email, address_id, active) VALUES (?, ?, ?, ?, ?)";
        // $stmt = $con->prepare($query);
        // $stmt->bindParam(1, $firstName);
        // $stmt->bindParam(2, $lastName);
        // $stmt->bindParam(3, $email);
        // $stmt->bindParam(4, $addressId);
        // $stmt->bindParam(5, $active);
        // $stmt->execute();
        // Redirect to the same page after adding a customer
        header('location: user.php');
    }
}

// Fetch and display customer records
$query = "SELECT * FROM customer";
$stmt = $con->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <title>User Page</title>
</head>

<body>
    <div class="container">
        <?php
        // Code for fetching user full name on the basis of username or email.
        $Username = $_SESSION['userlogin'];
        $query = $con->prepare("SELECT UserEmail FROM userdata WHERE (Username=:username || UserEmail=:username)");
        $query->execute(array(':username' => $Username));
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $UserEmail = $row['UserEmail'];
        }
        ?>
        <div class="row">
            <h1>Welcome to User Page<font face="Tahoma" color="red"><?php echo $Username . "    " . $UserEmail; ?></font>
            </h1>
            <br>
            <a href="logout.php" class="btn btn-danger">Log me out</a>
        </div>

        <!-- Add new customer form -->
        <h2>Add New Customer</h2>
            <a href="add.php" button type="submit" class="btn btn-primary" name="addCustomer">Add Customer</button></a>
        </form>

        <!-- Display customer records -->
        <h2>Customer Data Table</h2>
        <table class="table table-striped text-center mt-3">
            <thead>
                <tr>
                    <th>CUSTOMER ID</th>
                    <th>STORE ID</th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>EMAIL</th>
                    <th>ADDRESS ID</th>
                    <th>ACTIVE</th>
                    <th>CREATE DATE</th>
                    <th>LAST DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer) : ?>
                    <tr>
                        <td><?php echo $customer['customer_id']; ?></td>
                        <td><?php echo $customer['store_id']; ?></td>
                        <td><?php echo $customer['first_name']; ?></td>
                        <td><?php echo $customer['last_name']; ?></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td><?php echo $customer['address_id']; ?></td>
                        <td><?php echo $customer['active']; ?></td>
                        <td><?php echo $customer['create_date']; ?></td>
                        <td><?php echo $customer['last_update']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
