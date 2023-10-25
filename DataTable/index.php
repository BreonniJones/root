<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
    <title>Customer Data Table</title>
</head>
<body>
    <div class="container mt-3">
        <h1>Customer Data Table</h1>
        <table id="customer-table" class="table table-stripped text-center my-3">
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
                <?php
                // Include database connection (db.php)
                include('config/db.php');

                // Select all data from the "customer" table
                $query = "SELECT * FROM customer";

                // Prepare the SQL statement
                $stmt = $con->prepare($query);

                // Execute the SQL statement
                $stmt->execute();

                // Fetch all rows as an associative array
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output table rows with PHP
                foreach ($data as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['customer_id'] . "</td>";
                    echo "<td>" . $row['store_id'] . "</td>";
                    echo "<td>" . $row['first_name'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['address_id'] . "</td>";
                    echo "<td>" . $row['active'] . "</td>";
                    echo "<td>" . $row['create_date'] . "</td>";
                    echo "<td>" . $row['last_update'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script>
        $(document).ready(function () 
        {
            // Initialize DataTable
             $('table').DataTable({
                order: [0, 'desc']
            });
        });
    </script>
</body>
</html>
