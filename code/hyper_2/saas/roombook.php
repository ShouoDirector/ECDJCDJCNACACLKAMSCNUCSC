<?php
include 'includes/session.php';
if (!isset($_GET["rid"])) {
    header("Location: index.php");
    exit;
} else {
    $curdate = date("F d, Y"); // Format curdate
    include 'db.php';
    $id = $_GET['rid'];

    // Validate input and prevent SQL injection
    $id = mysqli_real_escape_string($con, $id);

    $sql = "SELECT * FROM roombook WHERE id = '$id'";
    $result = mysqli_query($con, $sql);

    // Handle database query errors
    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);
    if (!$row) {
        mysqli_free_result($result);
        mysqli_close($con);
        header("Location: index.php");
        exit;
    }

    $title = $row['Title'];
    $fname = $row['FName'];
    $lname = $row['LName'];
    $email = $row['Email'];
    $nat = $row['National'];
    $country = $row['Country'];
    $Phone = $row['Phone'];
    $troom = $row['TRoom'];
    $nroom = $row['NRoom'];
    $bed = $row['Bed'];
    $non = $row['NRoom'];
    $meal = $row['Meal'];
    $cin = date("F d, Y", strtotime($row['cin'])); // Format cin
    $cout = date("F d, Y", strtotime($row['cout'])); // Format cout
    $sta = $row['stat'];
    $days = $row['nodays'];

    mysqli_free_result($result);
    mysqli_close($con);
}
?>

<?php
include('db.php');

// Check if the form is submitted
if (isset($_POST['confirmed'])) {
    $c_id = $_POST['room']; // The selected room ID
    $conf = $_POST['conf']; // The selected confirmation value

    // Update the 'stat' and 'place' columns in the 'roombook' and 'room' tables respectively
    $update_query = "UPDATE roombook SET stat = '$conf' WHERE ID = $id";
    $update_result = mysqli_query($con, $update_query);

    // Check if the update query executed successfully
    if ($update_result) {
        $room_update_query = "UPDATE room SET place = 'Occupied', cusid = $id WHERE ID = $c_id";
        $room_update_result = mysqli_query($con, $room_update_query);

        // Check if the room update query executed successfully
        if ($room_update_result) {
            // Redirect to non-conform_status.php
            header("Location: non-conform_status.php");
            exit(); // Ensure that the script stops executing after the redirect
        } else {
            echo "Failed to update room information.";
        }
    } else {
        echo "Failed to update confirmation status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Analytics | Admin Dashboard</title>
    <?php @include 'includes/head.php' ?>
</head>

<body>

    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- End Preloader-->
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Top bar Start ========== -->
        <?php @include 'includes/top_bar.php' ?>
        <!-- ========== Top bar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php @include 'includes/left-sidebar.php' ?>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pinarik</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Room Booking</a></li>
                                    <li class="breadcrumb-item active"><?php echo $title. ' ' . $fname. ' ' . $lname; ?>
                                    </li>
                                </ol>
                            </div>
                            <h4 class="page-title">Room Booking</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <!-- Messages-->
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="header-title">Description | Information</h4>
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Settings</a>
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body pt-0">
                                                <div class="inbox-widget">
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Full Name</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $title.' '.$fname.' '.$lname; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Email</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $email; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Nationality</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $nat; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Country</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $country;  ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Phone Number</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $Phone; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Type of Room</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $troom; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Email</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $email; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Number of Room</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $nroom; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Total Meal Costs</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                P 999.00 </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Bedding</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $bed; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Check In</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $cin; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Check Out</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $cout; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Number of Days</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $days; ?> </a>
                                                        </p>
                                                    </div>
                                                    <div class="inbox-item">
                                                        <p class="inbox-item-author">Status Level</p>
                                                        <p class="inbox-item-date">
                                                            <a href="#" class="btn btn-sm btn-link text-info font-13">
                                                                <?php echo $sta; ?> </a>
                                                        </p>
                                                    </div>

                                                </div> <!-- end inbox-widget -->
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div>
                                    <!-- end col -->

                                    <?php
                                            // Include the 'db.php' file for database connection
                                            include ('db.php');

                                            // Declare and initialize the variables
                                            $superior_room_count = 0;
                                            $guest_house_count = 0;
                                            $single_room_count = 0;
                                            $deluxe_room_count = 0;

                                            // Query to count occurrences in the 'room' table for Superior Room
                                            $superior_room_query = "SELECT COUNT(*) AS count FROM room WHERE type = 'Superior Room' AND place = 'Free'";

                                            // Execute the query for Superior Room
                                            $superior_room_result = mysqli_query($con, $superior_room_query);

                                            // Check if the query for Superior Room executed successfully
                                            if ($superior_room_result) {
                                                // Fetch the count value for Superior Room
                                                $row = mysqli_fetch_assoc($superior_room_result);
                                                $superior_room_count = $row['count'];
                                            } else {
                                                // Error handling if the query for Superior Room fails
                                            }

                                            // Query to count occurrences in the 'room' table for Guest House
                                            $guest_house_query = "SELECT COUNT(*) AS count FROM room WHERE type = 'Guest House' AND place = 'Free'";

                                            // Execute the query for Guest House
                                            $guest_house_result = mysqli_query($con, $guest_house_query);

                                            // Check if the query for Guest House executed successfully
                                            if ($guest_house_result) {
                                                // Fetch the count value for Guest House
                                                $row = mysqli_fetch_assoc($guest_house_result);
                                                $guest_house_count = $row['count'];
                                            } else {
                                                // Error handling if the query for Guest House fails
                                            }

                                            // Query to count occurrences in the 'room' table for Single Room
                                            $single_room_query = "SELECT COUNT(*) AS count FROM room WHERE type = 'Single Room' AND place = 'Free'";

                                            // Execute the query for Single Room
                                            $single_room_result = mysqli_query($con, $single_room_query);

                                            // Check if the query for Single Room executed successfully
                                            if ($single_room_result) {
                                                // Fetch the count value for Single Room
                                                $row = mysqli_fetch_assoc($single_room_result);
                                                $single_room_count = $row['count'];
                                            } else {
                                                // Error handling if the query for Single Room fails
                                            }

                                            // Query to count occurrences in the 'room' table for Deluxe Room
                                            $deluxe_room_query = "SELECT COUNT(*) AS count FROM room WHERE type = 'Deluxe Room' AND place = 'Free'";

                                            // Execute the query for Deluxe Room
                                            $deluxe_room_result = mysqli_query($con, $deluxe_room_query);

                                            // Check if the query for Deluxe Room executed successfully
                                            if ($deluxe_room_result) {
                                                // Fetch the count value for Deluxe Room
                                                $row = mysqli_fetch_assoc($deluxe_room_result);
                                                $deluxe_room_count = $row['count'];
                                            } else {
                                                // Error handling if the query for Deluxe Room fails
                                            }

                                            // Query to retrieve booked room types from the 'roombook' table
                                            $booked_room_query = "SELECT Troom FROM roombook WHERE stat = 'Conform'";

                                            // Execute the query to retrieve booked room types
                                            $booked_room_result = mysqli_query($con, $booked_room_query);

                                            // Check if the query to retrieve booked room types executed successfully
                                            if ($booked_room_result) {
                                                // Iterate through the booked room types and subtract the count from the corresponding room type variables
                                                while ($row = mysqli_fetch_assoc($booked_room_result)) {
                                                    $booked_room_type = $row['Troom'];

                                                    // Subtract the count based on the booked room type
                                                    switch ($booked_room_type) {
                                                        case 'Superior Room':
                                                            $superior_room_count--;
                                                            break;
                                                        case 'Guest House':
                                                            $guest_house_count--;
                                                            break;
                                                        case 'Single Room':
                                                            $single_room_count--;
                                                            break;
                                                        case 'Deluxe Room':
                                                            $deluxe_room_count--;
                                                            break;
                                                        default:
                                                            // Handle unrecognized room types if needed
                                                            break;
                                                    }
                                                }
                                            } else {
                                                // Error handling if the query to retrieve booked room types fails
                                            }

                                            // Calculate the total room count
                                            $total_room_count = $superior_room_count + $guest_house_count + $single_room_count + $deluxe_room_count;

                                            // Close the database connection
                                            mysqli_close($con);
                                            ?>





                                    <div class="col-lg-4">

                                        <div class="border p-3 mt-4 mt-lg-0 rounded">
                                            <h4 class="header-title mb-3">Available Rooms</h4>

                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td>Superior Room</td>
                                                            <td><?php echo $superior_room_count ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Guest House</td>
                                                            <td><?php echo $guest_house_count ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Single Room</td>
                                                            <td><?php echo $single_room_count ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Deluxe Room</td>
                                                            <td><?php echo $deluxe_room_count ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Room</th>
                                                            <th><?php echo $total_room_count ?></th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- end table-responsive -->
                                        </div>

                                        <div class="panel-footer mt-2">
                                        <form method="post" class="row">
                                            <label>Select the Confirmation</label>

                                            <?php
                                            include('db.php');

                                            // Query to retrieve rooms from the 'room' table based on the conditions
                                            $room_query = "SELECT * FROM room WHERE place = 'Free' AND type = '$troom' AND bedding = '$bed'";
                                            $room_result = mysqli_query($con, $room_query);

                                            // Check if the query executed successfully
                                            if ($room_result) {
                                            ?>
                                            <div class="form-group col-md-12 mt-2">
                                                <select class="form-select" name="room" required>
                                                    <option value="">Select a room</option>
                                                    <?php
                                                    // Iterate through the retrieved rows and create option elements
                                                    while ($row = mysqli_fetch_assoc($room_result)) {
                                                        $room_id = $row['ID'];
                                                        $room_name = $row['type'];
                                                        $room_bedding = $row['bedding'];
                                                    ?>
                                                    <option value="<?php echo $room_id; ?>">
                                                        <?php echo $room_name; ?> - <?php echo $room_bedding; ?>
                                                    </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            } else {
                                                // Error handling if the query fails
                                            }
                                            ?>

                                            <div class="form-group col-md-12 mt-2">
                                                <select name="conf" class="form-select mb-2">
                                                    <option value="" selected>Not Conform</option>
                                                    <option value="Conform">Conform</option> <!-- Corrected option value -->
                                                </select>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <input type="submit" name="confirmed" class="btn btn-success mb-2 col-12">
                                            </div>
                                        </form>

                                        </div>

                                    </div> <!-- end col -->

                                </div> <!-- end row -->
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>

            </div>
            <!-- content -->

            <!-- Footer Start -->
            <?php @include 'includes/footer.php' ?>
            <!-- end Footer -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    <!-- Theme Settings -->
    <?php @include 'includes/canvas.php' ?>
    <!---JS Files -->

    <script src="assets/js/vendor.min.js"></script>

    <!-- Daterangepicker js -->
    <script src="assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>

    <!-- Charts js -->
    <script src="assets/vendor/chart.js/chart.min.js"></script>
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Vector Map js -->
    <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Analytics Dashboard App js -->
    <script src="assets/js/pages/demo.dashboard-analytics.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- Apexchart js -->
    <?php @include 'includes/demo.dashboard.php' ?>
    <?php @include 'includes/demo.crm-dashboard.php' ?>

</body>

</html>