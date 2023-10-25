<?php
// Include database connection (db.php)
include('config/db.php'); // Adjust the path as needed

// Select all data from the "customer" table
$query = "SELECT * FROM customer";

// Prepare the SQL statement
$stmt = $con->prepare($query);

// Execute the SQL statement
$stmt->execute();

// Fetch all rows as an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
