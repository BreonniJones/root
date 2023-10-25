<?php
session_start();
// Include Database
include('config/db.php');

// Check if the user is logged in and has Super Admin role
if (strlen($_SESSION['userlogin']) == 0 || $_SESSION['userRole'] != 'Super Admin') {
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

// Initialize variables for adding/editing customers
$customer_id = "";
$first_name = "";
$last_name = "";
$email = "";
$address_id = "";
$active = "";

// Check if the form is submitted for adding/editing customers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addCustomer'])) {
        // Handle adding a new customer
        $first_name = sanitize_input($_POST['firstName']);
        $last_name = sanitize_input($_POST['lastName']);
        $email = sanitize_input($_POST['email']);
        $address_id = sanitize_input($_POST['addressId']);
        $active = sanitize_input($_POST['active']);

        // Your INSERT query here to add the new customer to the database
        // Don't forget to validate and sanitize user input before inserting into the database
        // Example:
        // $query = "INSERT INTO customer (first_name, last_name, email, address_id, active) VALUES (?, ?, ?, ?, ?)";
        // $stmt = $con->prepare($query);
        // $stmt->bindParam(1, $first_name);
        // $stmt->bindParam(2, $last_name);
        // $stmt->bindParam(3, $email);
        // $stmt->bindParam(4, $address_id);
        // $stmt->bindParam(5, $active);
        // $stmt->execute();
        // Redirect to the same page after adding a customer
        header('location: superadmin.php');
    } elseif (isset($_POST['editCustomer'])) {
        // Handle editing an existing customer
        $customer_id = sanitize_input($_POST['customerId']);
        $first_name = sanitize_input($_POST['editFirstName']);
        $last_name = sanitize_input($_POST['editLastName']);
        $email = sanitize_input($_POST['editEmail']);
        $address_id = sanitize_input($_POST['editAddressId']);
        $active = sanitize_input($_POST['editActive']);

        // Your UPDATE query here to edit the customer in the database
        // Don't forget to validate and sanitize user input before updating the database
        // Example:
        // $query = "UPDATE customer SET first_name = ?, last_name = ?, email = ?, address_id = ?, active = ? WHERE customer_id = ?";
        // $stmt = $con->prepare($query);
        // $stmt->bindParam(1, $first_name);
        // $stmt->bindParam(2, $last_name);
        // $stmt->bindParam(3, $email);
        // $stmt->bindParam(4, $address_id);
        // $stmt->bindParam(5, $active);
        // $stmt->bindParam(6, $customer_id);
        // $stmt->execute();
        // Redirect to the same page after editing a customer
        header('location: superadmin.php');
    } elseif (isset($_POST['deleteCustomer'])) {
        // Handle deleting an existing customer
        $customer_id = sanitize_input($_POST['customerId']);

        // Your DELETE query here to delete the customer from the database
        // Example:
        // $query = "DELETE FROM customer WHERE customer_id = ?";
        // $stmt = $con->prepare($query);
        // $stmt->bindParam(1, $customer_id);
        // $stmt->execute();
        // Redirect to the same page after deleting a customer
        header('location: superadmin.php');
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
    <title>Super Admin Page</title>
</head>

<body>
    <div class="container">
        <?php
        // Code for fetching user full name on the basis of username or email.
        $Username = $_SESSION['userlogin'];
        $query = $con->prepare("SELECT UserEmail FROM userdata WHERE (Username=:username || UserEmail=:username)");
        $query->execute(array(':username' => $Username));
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $Email = $row['UserEmail'];
        }
        ?>
        <div class="row">
            <h1>Welcome to Super Admin Page<font face="Tahoma" color="red"><?php echo $Username . "    " . $Email; ?></font>
            </h1>
            <br>
            <a href="logout.php" class="btn btn-danger">Log me out</a>
        </div>

        <!-- Add new customer form -->
        <h2>Add New Customer</h2>
        <h2>Add New Customer</h2>
            <a href="add.php" button type="submit" class="btn btn-primary" name="addCustomer">Add Customer</button></a>
        </form>

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
                    <th>ACTIONS</th>
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
                            <!-- Edit customer button -->
                            <a href="edit.php?id=<?php echo $customer['customer_id']; ?>" class="btn btn-warning btn-space">Edit</a>
                            <!-- Delete customer button -->
                            <form method="post" style="display: inline-block;">
                                <input type="hidden" name="customerId" value="<?php echo $customer['customer_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" name="deleteCustomer" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
