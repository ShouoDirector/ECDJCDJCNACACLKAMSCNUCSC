<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("location:index.php");
    exit;
}

// Include the database connection file
include("db.php");

// Prepare the SQL query
$query = "SELECT profile_pic FROM login WHERE usname = ?";

// Prepare and execute the statement
$statement = mysqli_prepare($con, $query);
if (!$statement) {
    die("Error: " . mysqli_error($con));
}

mysqli_stmt_bind_param($statement, "s", $_SESSION["user"]);
mysqli_stmt_execute($statement);

// Bind the result to a variable
mysqli_stmt_bind_result($statement, $profilePicData);

// Fetch the profile picture data
mysqli_stmt_fetch($statement);

// Encode the profile picture data as Base64
$profilePicURL = 'data:image/jpeg;base64,' . base64_encode($profilePicData);

// Store the profile picture URL in the session for future use
$_SESSION["profile_pic"] = $profilePicURL;

// Close the statement
mysqli_stmt_close($statement);

// Close the database connection
mysqli_close($con);
?>