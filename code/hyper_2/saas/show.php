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
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Status | Admin Dashboard</title>
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
                                                <div class="col-12">
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
                                                                                        <th>Quantity</th>
                                                                                        <th>Unit Cost</th>
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
                                                                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $room_type; ?></span> - <span class="badge <?php echo $badgeClass2; ?>"><?php echo $Bed_type; ?></span>
                                                                                        </td>

                                                                                        <td>1</td>
                                                                                        <td>$1799.00</td>
                                                                                        <td class="text-end">$1799.00
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>2</td>
                                                                                        <td>
                                                                                            <b>Meal Costs</b> <br>
                                                                                            Two Year Extended Warranty -
                                                                                            Parts and Labor
                                                                                        </td>
                                                                                        <td>3</td>
                                                                                        <td>$499.00</td>
                                                                                        <td class="text-end">$1497.00
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>3</td>
                                                                                        <td>
                                                                                            <b>LED</b> <br>
                                                                                            80cm (32) HD Ready LED TV
                                                                                        </td>
                                                                                        <td>2</td>
                                                                                        <td>$412.00</td>
                                                                                        <td class="text-end">$824.00
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
                                                                                    class="float-end">$4120.00</span>
                                                                            </p>
                                                                            <p><b>VAT (12.5):</b> <span
                                                                                    class="float-end">$515.00</span></p>
                                                                            <h3>$4635.00 USD</h3>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div> <!-- end col -->
                                                                </div>
                                                                <!-- end row-->

                                                                <div class="d-print-none mt-4">
                                                                    <div class="text-end">
                                                                        <a href="javascript:window.print()"
                                                                            class="btn btn-primary"><i
                                                                                class="mdi mdi-printer"></i> Print</a>
                                                                        <a href="javascript: void(0);"
                                                                            class="btn btn-info">Submit</a>
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