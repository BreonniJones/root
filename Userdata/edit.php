<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <title>Edit Customer</title>
</head>
<body>
    <div class="container mt-3">
        <h1>Edit Customer</h1>

        <?php
        // Include database connection (db.php)
        include('config/db.php');

        // Initialize variables
        $id = isset($_GET['id']) ? $_GET['id'] : die('Error: Record ID not found');
        $firstName = $lastName = $email = $addressId = $active = '';

        if ($_POST) {
            try {
                $query = "UPDATE customer SET first_name=?, last_name=?, email=?, address_id=?, active=? WHERE customer_id=?";
                $stmt = $con->prepare($query);

                $customerId = sanitize_input($id);
                $firstName = sanitize_input($_POST['firstName']);
                $lastName = sanitize_input($_POST['lastName']);
                $email = sanitize_input($_POST['email']);
                $addressId = sanitize_input($_POST['addressId']);
                $active = sanitize_input($_POST['active']);

                $stmt->bindParam(1, $firstName);
                $stmt->bindParam(2, $lastName);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $addressId);
                $stmt->bindParam(5, $active);
                $stmt->bindParam(6, $id);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update. Please try again.</div>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        // Read current record's data by ID
        try {
            $query = "SELECT * FROM customer WHERE customer_id=?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $customerId = $row['customer_id'];
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $email = $row['email'];
            $addressId = $row['address_id'];
            $active = $row['active'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        function sanitize_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <form action="edit.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName; ?>">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label for="addressId">Address ID:</label>
                <input type="text" class="form-control" id="addressId" name="addressId" value="<?php echo $addressId; ?>">
            </div>
            <div class="form-group">
                <label for="active">Active:</label>
                <input type="text" class="form-control" id="active" name="active" value="<?php echo $active; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="superadmin.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
