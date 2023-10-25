<?php
// Code for checking username and email availability

// Include Database
include('config/db.php');

if (!empty($_POST['Username'])) {
    $Username = $_POST['Username'];
    $sql = "SELECT Username FROM userdata WHERE Username=?";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(1, $Username, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
        echo "<span style='color:red'>Username already exists</span>";
    } else {
        echo "<span style='color:green'>Username available for registration</span>";
    }
}

if (!empty($_POST['UserEmail'])) {
    $UserEmail = $_POST['UserEmail'];
    $sql = "SELECT UserEmail FROM userdata WHERE UserEmail=:email";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':email', $UserEmail, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
        echo "<span style='color:red'>Email already exists</span>";
    } else {
        echo "<span style='color:green'>Email available for registration</span>";
    }
}
?>
