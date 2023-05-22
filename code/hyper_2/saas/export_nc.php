<?php
if (isset($_POST['export'])) {
    // Database connection
    include('db.php');

    // Fetch data from the table
    $sql = "SELECT * FROM roombook";
    $result = mysqli_query($con, $sql);

    // Create a file pointer
    $file = fopen('export.csv', 'w');

    // Write column headers to the CSV file
    $headers = array('ID', 'Title', 'FName', 'LName', 'Email', 'National', 'Country', 'Phone', 'TRoom', 'Bed', 'NRoom', 'Meal', 'cin', 'cout', 'stat', 'nodays');
    fputcsv($file, $headers);

    // Write data rows to the CSV file
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($file, $row);
    }

    // Close the file pointer
    fclose($file);

    // Close the database connection
    mysqli_close($con);

    // Force download the CSV file
    $file = 'export.csv';
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $file);
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile($file);
    exit();
}
?>