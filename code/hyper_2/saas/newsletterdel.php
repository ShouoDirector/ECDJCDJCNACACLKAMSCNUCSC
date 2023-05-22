<?php
include('db.php');

$id = $_GET['eid'];

if (empty($id)) {
    echo '<script>alert("Sorry! Wrong Entry")</script>';
    header("Location: messages.php");
} else {
    $view = "DELETE FROM contact WHERE id = ?";
    $statement = mysqli_prepare($con, $view);
    mysqli_stmt_bind_param($statement, "i", $id);

    if (mysqli_stmt_execute($statement)) {
        echo '<script>alert("Newsletter Subscriber Removed")</script>';
        header("Location: messages.php");
    } else {
        echo '<script>alert("An error occurred while deleting the subscriber")</script>';
        echo "Error: " . mysqli_error($con);
    }
    mysqli_stmt_close($statement);
}
mysqli_close($con);
?>