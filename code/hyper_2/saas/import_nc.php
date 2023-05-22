<?php
if (isset($_POST['import'])) {
    // Database connection
    include('db.php');

    // Check if a file was uploaded
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['size'] > 0) {
        $file = $_FILES['csvFile']['tmp_name'];
        $handle = fopen($file, "r");

        // Skip the header row
        fgetcsv($handle);

        // Process each row of the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $id = $data[0];

            // Check if a record with the same ID already exists
            $existingRecordQuery = "SELECT * FROM roombook WHERE id = '$id'";
            $existingRecordResult = mysqli_query($con, $existingRecordQuery);

            if (mysqli_num_rows($existingRecordResult) > 0) {
                // Record with the same ID already exists, skip insertion
                continue;
            }

            $title = $data[1];
            $fname = $data[2];
            $lname = $data[3];
            $email = $data[4];
            $national = $data[5];
            $country = $data[6];
            $phone = $data[7];
            $troom = $data[8];
            $bed = $data[9];
            $nroom = $data[10];
            $meal = $data[11];
            $cin = $data[12];
            $cout = $data[13];
            $stat = $data[14];
            $nodays = $data[15];

            // SQL insert statement
            $sql = "INSERT INTO roombook (id, Title, FName, LName, Email, National, Country, Phone, TRoom, Bed, NRoom, Meal, cin, cout, stat, nodays) 
                    VALUES ('$id', '$title', '$fname', '$lname', '$email', '$national', '$country', '$phone', '$troom', '$bed', '$nroom', '$meal', '$cin', '$cout', '$stat', '$nodays')";
            
            // Execute the insert statement
            if (mysqli_query($con, $sql)) {
                echo "CSV file imported successfully";
            } else {
                echo "Error inserting record: " . mysqli_error($con);
            }
        }

        // Close the file handle
        fclose($handle);

        // Close the database connection
        mysqli_close($con);
    } else {
        // Handle the case when no file was uploaded
        echo "No file selected";
    }
}
?>