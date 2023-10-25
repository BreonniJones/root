<?php
try {
    include "config/db.php";
    
    // Check if the customer_id parameter exists in the URL
    if(isset($_GET['customer_id'])) {
        $customer_id = $_GET['customer_id'];

        // Delete query
        $query = "DELETE FROM customer WHERE customer_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $customer_id);

        if($stmt->execute()) {
            // Redirect back to superadmin.php after successful deletion
            header("Location: superadmin.php");
        } else {
            // Handle any errors here
            echo "Error: Could not delete the customer record.";
        }
    } else {
        // Handle the case where customer_id is not provided in the URL
        echo "Error: Record ID not found in the URL.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
