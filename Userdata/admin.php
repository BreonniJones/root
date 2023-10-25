<?php
session_start();
// Include Database
include('config/db.php');
if (strlen($_SESSION['userlogin'] == 0)) {
    header('location: index.php');
} elseif ($_SESSION['userRole'] != 'Admin') {
    include 'logout.php';
    header('location: index.php');
}

// Check if the user is authorized to view customers
if ($_SESSION['userRole'] == 'Admin') {
    // Fetch and display customer data
    $query = "SELECT * FROM customer";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <title>Admin Page</title>
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
            <h1>Welcome to Admin Page<font face="Tahoma" color="red"><?php echo $Username . "    " . $UserEmail; ?></font>
            </h1>
            <br>
            <a href="logout.php" class="btn btn-danger">Log me out</a>
        </div>

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
                    <th>ACTIONS</th> <!-- Add this column for Edit button -->
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
                        <td>
                            <a href="edit.php?id=<?php echo $customer['customer_id']; ?>" class="btn btn-warning btn-space">Edit</a>
                            <a href="add.php" button type="submit" class="btn btn-primary" name="addCustomer">Add Customer</button></a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
