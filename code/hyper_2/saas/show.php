<?php @include 'includes/session.php' ?>
<?php
	ob_start();
	include ('db.php');

	$pid = isset($_GET['sid']) ? $_GET['sid'] : "";


	if (!empty($pid)) {
		$sql = "SELECT * FROM roombook WHERE id = '$pid'";
		$re = mysqli_query($con, $sql);

		if ($re) {
			if (mysqli_num_rows($re) > 0) {
				while ($row = mysqli_fetch_array($re)) {
					$id = $row['id'];
					$title = $row['Title'];
					$Fname = $row['FName'];
					$lname = $row['LName'];
					$email = $row['Email'];
					$National = $row['National'];
					$country = $row['Country'];
                    $nroom = 1;
					$phone = $row['Phone'];
					$room_type = $row['TRoom'];
					$Bed_type = $row['Bed'];
					$cin_date = $row['cin'];
					$cout_date = $row['cout'];
					$nodays = $row['nodays'];
				}
			} else {
				echo "No records found for the provided ID." . mysqli_error($con);
			}
		} else {
			echo "Query execution failed: " . mysqli_error($con);
		}
	} else {
		echo "Missing ID parameter in the URL.";
	}
?>

<?php
// Include the database connection
include('db.php');

// Execute the SQL query to count occurrences
$query = "SELECT COUNT(*) as count FROM room WHERE cusid = $id";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch the count from the result
    $row = mysqli_fetch_assoc($result);
    $room_rent_count = $row['count'];

} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>

<?php
// Include the database connection
include('db.php');

// Execute the SQL query to get the sum
$query = "SELECT SUM(rent_price) as total FROM room WHERE cusid = $id";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch the sum from the result
    $row = mysqli_fetch_assoc($result);
    $room_rent_price = $row['total'];

} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>

<?php
// Include the database connection
include('db.php');

// Initialize an empty array to store the column names
$nonZeroColumns = array();

// Execute the SQL query to retrieve the row with the matching ID
$query = "SELECT * FROM orders WHERE id = $id";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch the row from the result
    $row = mysqli_fetch_assoc($result);

    // Loop through the columns of the row
    foreach ($row as $column => $value) {
        // Exclude columns with null values or values equal to 0
        if ($value !== null && $value !== "0" && $value !== 0) {
            // Add the column name to the array
            $nonZeroColumns[] = $column;
        }
    }
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

$badgeClasses = array(
    'snack' => 'badge-primary',
    'breakfast' => 'badge-secondary',
    'lunch' => 'badge-success',
    'dinner' => 'badge-danger',
    'special' => 'badge-warning',
    'vip' => 'badge-info',
    'ultimate' => 'badge-dark'
);

// Initialize an empty variable to store the HTML markup
// Initialize an empty variable to store the HTML markup
$badgesHTML = '';

// Iterate over the $nonZeroColumns array
foreach ($nonZeroColumns as $column) {
    // Capitalize the column name
    $capitalizedColumn = ucfirst($column);

    // Check if the column exists in the $badgeClasses array
    if (array_key_exists($column, $badgeClasses)) {
        // Get the Bootstrap badge class for the column
        $badgeClass = $badgeClasses[$column];

        // Append the Bootstrap badge HTML to the $badgesHTML variable, using the capitalized column name
        $badgesHTML .= '<span class="badge ' . $badgeClass . '">' . $capitalizedColumn . '</span> ';
    }
}


// Close the database connection
mysqli_close($con);
?>

<?php
// Include the database connection
include('db.php');

// Initialize an empty array to store the column names and values
$orderItems = array();

// Execute the SQL query to retrieve the row with the matching ID
$query = "SELECT * FROM orders WHERE id = $id";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch the row from the result
    $row = mysqli_fetch_assoc($result);

    // Loop through the columns of the row
    foreach ($row as $column => $value) {
        // Exclude the 'id' column and columns with null values or values equal to 0
        if ($column !== 'id' && $value !== null && $value !== "0" && $value !== 0) {
            // Capitalize the column name and add it to the array with the formatted value
            $orderItems[ucfirst($column)] = ucfirst($column) . ' x' . $value;
        }
    }
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>

<?php
// Include the database connection
include('db.php');

// Initialize an empty array to store the column names and values
$orderItemsPrices = array();

// Execute the SQL query to retrieve the row with the matching ID
$query = "SELECT * FROM orders WHERE id = $id";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch the row from the result
    $row = mysqli_fetch_assoc($result);

    // Initialize a variable to store the total meal costs
    $total_meal_costs = 0;

    // Loop through the columns of the row
    foreach ($row as $column => $value) {
        // Exclude the 'id' column and columns with null values or values equal to 0
        if ($column !== 'id' && $value !== null && $value !== "0" && $value !== 0) {
            // Capitalize the column name
            $mealType = ucfirst($column);

            // Retrieve the price from the 'meal' table
            $mealQuery = "SELECT price FROM meal WHERE name = '$mealType'";
            $mealResult = mysqli_query($con, $mealQuery);

            // Check if the meal price query was successful
            if ($mealResult && mysqli_num_rows($mealResult) > 0) {
                // Fetch the price from the result
                $mealRow = mysqli_fetch_assoc($mealResult);
                $mealPrice = $mealRow['price'];

                // Format the meal price
                $formattedPrice = number_format($mealPrice, 2);

                // Add the meal type and formatted price to the array
                $orderItemsPrices[] = $mealType . ' - ' . $formattedPrice;

                // Calculate the total meal costs
                $total_meal_costs += $value * $mealPrice;
            }
        }
    }
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title>Invoice</title>
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

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pinarik</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Invoice</li>
                                    </ol>
                                </div>
                                <div class="page-title-left">
                                    <h4 class="page-title">Invoice <a href="conform_status.php" style="font-size: 12px;"
                                            class="ms-2">
                                            < Go Back </a> </h4> </div> </div> </div> </div> <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">

                                                            <!-- Invoice Logo-->
                                                            <div class="clearfix">
                                                                <div class="float-start mb-3">
                                                                    <img src="https://cdn-icons-png.flaticon.com/128/3074/3074046.png"
                                                                        alt="logo" width="50px;"> <span
                                                                        style="font-size: 16px; font-weight: bold;"
                                                                        class="ms-3">Pinarik Eco Resort</span>
                                                                </div>
                                                                <div class="float-end">
                                                                    <h4 class="m-0 d-print-none">Invoice</h4>
                                                                    <?php echo $cin_date ?>
                                                                    <?php echo $cout_date ?>
                                                                </div>
                                                            </div>

                                                            <!-- Invoice Detail-->
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="float-end mt-3">
                                                                        <p><b>Hello,
                                                                                <?php echo $title." ".$Fname." ".$lname;?></b>
                                                                        </p>
                                                                        <p class="text-muted font-13">Please find below
                                                                            a cost-breakdown for the recent work
                                                                            completed. Please make payment at your
                                                                            earliest convenience, and do not hesitate to
                                                                            contact me with any questions.</p>
                                                                    </div>

                                                                </div><!-- end col -->
                                                                <div class="col-sm-4 offset-sm-2">
                                                                    <div class="mt-3 float-sm-end">
                                                                        <p class="font-13"><strong>Date: </strong>
                                                                            &nbsp;&nbsp;&nbsp; <?php echo $cin_date ?>
                                                                        </p>
                                                                        <p class="font-13"><strong>Status: </strong>
                                                                            <span
                                                                                class="badge bg-success float-end">Paid</span>
                                                                        </p>
                                                                        <p class="font-13"><strong>ID: </strong> <span
                                                                                class="float-end"><?php echo $id ?></span>
                                                                        </p>
                                                                    </div>
                                                                </div><!-- end col -->
                                                            </div>
                                                            <!-- end row -->

                                                            <div class="row mt-4">
                                                                <!-- end row -->

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="table-responsive">
                                                                            <table class="table mt-4">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Categories</th>
                                                                                        <th>Quantity | Price</th>
                                                                                        <th>Days</th>
                                                                                        <th class="text-end">Total</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>1</td>
                                                                                        <td>
                                                                                            <b>Room Rent</b> <br>
                                                                                            <?php
                                                                                                $badgeClass = '';

                                                                                                switch ($room_type) {
                                                                                                    case 'Deluxe Room':
                                                                                                        $badgeClass = 'badge-primary';
                                                                                                        break;
                                                                                                    case 'Guest House':
                                                                                                        $badgeClass = 'badge-dark';
                                                                                                        break;
                                                                                                    case 'Single Room':
                                                                                                        $badgeClass = 'badge-success';
                                                                                                        break;
                                                                                                    case 'Superior Room':
                                                                                                        $badgeClass = 'badge-danger';
                                                                                                        break;
                                                                                                    default:
                                                                                                        $badgeClass = 'badge-secondary';
                                                                                                        break;
                                                                                                }
                                                                                                ?>
                                                                                            <?php
                                                                                            $badgeClass2 = '';

                                                                                            switch($Bed_type){
                                                                                                case 'Single':
                                                                                                    $badgeClass2 = 'badge-primary';
                                                                                                    break;
                                                                                                case 'Double':
                                                                                                    $badgeClass2 = 'badge-dark';
                                                                                                    break;
                                                                                                case 'Triple':
                                                                                                    $badgeClass2 = 'badge-success';
                                                                                                    break;
                                                                                                case 'Quad':
                                                                                                    $badgeClass2 = 'badge-danger';
                                                                                                    break;
                                                                                                default:
                                                                                                    $badgeClass2 = 'badge-secondary';
                                                                                                    break;

                                                                                            }
                                                                                            ?>
                                                                                            <span
                                                                                                class="badge <?php echo $badgeClass; ?>"><?php echo $room_type; ?></span>
                                                                                            - <span
                                                                                                class="badge <?php echo $badgeClass2; ?>"><?php echo $Bed_type; ?></span>
                                                                                        </td>

                                                                                        <td><span
                                                                                                class="badge <?php echo $badgeClass; ?>"><?php echo $room_type; ?></span>
                                                                                            - <span
                                                                                                class="badge <?php echo $badgeClass2; ?>"><?php echo $Bed_type; ?>
                                                                                                x<?php echo $room_rent_count ?></span>
                                                                                            <br><span
                                                                                                class="badge <?php echo $badgeClass; ?>"><?php echo '₱' . number_format($room_rent_price, 2, '.', ','); ?>
                                                                                        </td>
                                                                                        <td><?php echo $nodays ?> Days
                                                                                        </td>
                                                                                        <td class="text-end">
                                                                                            <?php 
                                                                                        $total_room_payment = $room_rent_price * $room_rent_count;
                                                                                        echo '₱' . number_format($total_room_payment, 2, '.', ','); ?>
                                                                                        </td>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>2</td>
                                                                                        <td>
                                                                                            <b>Meal Costs</b> <br>
                                                                                            <?php echo $badgesHTML ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div>
                                                                                                <?php
                                                                                            // Check if there are any order items
                                                                                            if (!empty($orderItems)) {
                                                                                                // Iterate over the order items array and print each item
                                                                                                foreach ($orderItems as $item) {
                                                                                                    echo '<p class="badge">' . $item . '</p>';
                                                                                                }
                                                                                            } else {
                                                                                                echo '<p>No order items found.</p>';
                                                                                            }
                                                                                            ?>
                                                                                                <br>
                                                                                                <?php
                                                                                            // Check if there are any order items
                                                                                            if (!empty($orderItems)) {
                                                                                                // Iterate over the order items array and print each item
                                                                                                foreach ($orderItemsPrices as $item) {
                                                                                                    echo '<p class="badge">' . $item . '</p>';
                                                                                                }
                                                                                            } else {
                                                                                                echo '<p>No order items found.</p>';
                                                                                            }
                                                                                            ?>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td></td>
                                                                                        <td class="text-end">
                                                                                            <?php echo  '₱'. number_format($total_meal_costs, 2, '.', ','); ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div> <!-- end table-responsive-->
                                                                    </div> <!-- end col -->
                                                                </div>
                                                                <!-- end row -->

                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="clearfix pt-3">
                                                                            <h6 class="text-muted">Notes:</h6>
                                                                            <small>
                                                                                All accounts are to be paid within 7
                                                                                days from receipt of
                                                                                invoice. To be paid by cheque or credit
                                                                                card or direct payment
                                                                                online. If account is not paid within 7
                                                                                days the credits details
                                                                                supplied as confirmation of work
                                                                                undertaken will be charged the
                                                                                agreed quoted fee noted above.
                                                                            </small>
                                                                        </div>
                                                                    </div> <!-- end col -->
                                                                    <div class="col-sm-6">
                                                                        <div class="float-end mt-3 mt-sm-0">
                                                                            <p><b>Sub-total:</b> <span
                                                                                    class="float-end"><?php echo  '₱'. number_format($total_meal_costs + ($room_rent_price * $room_rent_count), 2, '.', ','); ?></span>
                                                                            </p>
                                                                            <?php $discount = 0.00; ?>
                                                                            <p><b>Discount Voucher:</b> <span
                                                                                    class="float-end text-danger">&nbsp;
                                                                                    ₱ 0.00</span></p>
                                                                            <h3><?php
                                                                            $total_payment = ($total_meal_costs + ($room_rent_price * $room_rent_count)) - $discount;
                                                                            
                                                                            echo  '₱'. number_format($total_payment, 2, '.', ','); ?>
                                                                            </h3>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div> <!-- end col -->
                                                                </div>
                                                                <!-- end row-->

                                                                <div class="d-print-none mt-4">
                                                                    <div class="text-end">
                                                                        <a href="javascript:window.print()"
                                                                            class="btn btn-primary mb-2"><i
                                                                                class="mdi mdi-printer"></i> Print</a>
                                                                                <div class="col-xl-12">
                                                                                    <form method="POST">
                                                                                        <div class="col-xl-12">
                                                                                        

                                                                                            
                                                                                            <input class="btn btn-info" name="submit" type="submit" value="Click if Paid">
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                    </div>
                                                                </div>
                                                                <!-- end buttons -->

                                                            </div> <!-- end card-body-->
                                                        </div> <!-- end card -->
                                                    </div> <!-- end col-->
                                                </div>
                                                

                                </div>

                            </div>
                            <!-- container -->
                        </div>

                        <?php
                            include('db.php');

                            if (isset($_POST['submit'])) {
                                // Retrieve the form data
                                $id2 = $id;
                                $title2 = $title;
                                $Fname2 = $Fname;
                                $lname2 = $lname;
                                $troom = $room_type;
                                $tbed = $Bed_type;
                                $nroom = 1;
                                $cin = $cin_date;
                                $cout = $cout_date;
                                $ttot = $total_room_payment;
                                $noofdays = $nodays;
                                $tips = 0.00; // Default value for tips
                                $meal_total = $total_meal_costs;
                                $total_payment = $total_payment;

                                // Insert the values into the 'payment' table
                                $insert_query = "INSERT INTO payment (id, title, fname, lname, troom, tbed, nroom, cin, cout, ttot, noofdays, tips, meal_total, total_payment) 
                                                VALUES ('$id2', '$title', '$Fname', '$lname', '$troom', '$tbed', '$nroom', '$cin', '$cout', '$ttot', '$noofdays', '$tips', '$meal_total', '$total_payment')";

                                // Execute the query
                                if (!mysqli_query($con, $insert_query)) {
                                    echo "Error: " . mysqli_error($con);
                                } else {
                                    echo "Payment recorded successfully!";
                                }
                                }


                            ?>
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
                <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js">
                </script>

                <!-- Analytics Dashboard App js -->
                <script src="assets/js/pages/demo.dashboard-analytics.js"></script>

                <!-- App js -->
                <script src="assets/js/app.min.js"></script>

                <!-- Customers Demo App js -->
                <script src="assets/js/pages/demo.customers.js"></script>

                <!-- Datatable js -->
                <script src="assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
                <script src="assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
                <script src="assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
                <script src="assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
                <script src="assets/vendor/jquery-datatables-checkboxes/js/dataTables.checkboxes.min.js"></script>

                <!-- Apexchart js -->
                <?php @include 'includes/demo.dashboard.php' ?>
                <?php @include 'includes/demo.crm-dashboard.php' ?>

</body>

</html>