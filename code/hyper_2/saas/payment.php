<?php @include 'includes/session.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payments | Admin Dashboard</title>
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
                                        <li class="breadcrumb-item active">Payments</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Payments</h4>
                            </div>
                        </div>
                    </div>

                    <?php
						include('db.php');
                        $sql = "SELECT COUNT(*) AS count FROM payment";
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $c = $row['count'];?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-5">
                                            <a href="javascript:void(0);" class="btn btn-primary mb-2">Total Payments : <?php echo $c ?></a>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                    <?php
                                        include('db.php');

                                        // Retrieve data from 'payment' table
                                        $sql = "SELECT * FROM payment";
                                        $result = mysqli_query($con, $sql);

                                        // Check if any rows exist in the result
                                        if (mysqli_num_rows($result) > 0) {
                                            echo '<table class="table table-centered table-striped dt-responsive nowrap w-100" id="products-datatable">';
                                            echo '<thead>';
                                            echo '<tr>';
                                            echo '<th style="width: 20px;">';
                                            echo '<div class="form-check">';
                                            echo '<input type="checkbox" class="form-check-input" id="customCheck1">';
                                            echo '<label class="form-check-label" for="customCheck1">&nbsp;</label>';
                                            echo '</div>';
                                            echo '</th>';
                                            echo '<th>ID</th>';
                                            echo '<th>Full Name</th>';
                                            echo '<th>Room Type</th>';
                                            echo '<th>Bedding Type</th>';
                                            echo '<th>Check In</th>';
                                            echo '<th>Check Out</th>';
                                            echo '<th>Room Rent</th>';
                                            echo '<th>Meal Paid</th>';
                                            echo '<th>Total Payment</th>';
                                            echo '<th>No. of Days</th>';
                                            echo '<th>Tips</th>';
                                            echo '<th>Invoice</th>';
                                            echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';

                                            // Loop through each row and display data in table rows
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<div class="form-check">';
                                                echo '<input type="checkbox" class="form-check-input" id="customCheck2">';
                                                echo '<label class="form-check-label" for="customCheck2">&nbsp;</label>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td class="table-user">';
                                                echo '<a href="javascript:void(0);" class="text-body fw-semibold">' . $row['id'] . '</a>';
                                                echo '</td>';
                                                echo '<td>' . $row['fname'] . ' ' . $row['lname'] . '</td>';
                                                echo '<td>' . $row['troom'] . '</td>';
                                                echo '<td>' . $row['tbed'] . '</td>';
                                                echo '<td>' . $row['cin'] . '</td>';
                                                echo '<td>' . $row['cout'] . '</td>';
                                                echo '<td>' . $row['ttot'] . '</td>';
                                                echo '<td>' . $row['meal_total'] . '</td>';
                                                echo '<td>' . $row['total_payment'] . '</td>';
                                                echo '<td>' . $row['noofdays'] . '</td>';
                                                echo '<td>' . $row['tips'] . '</td>';
                                                echo '<td class="text-center">';
                                                echo '<a href="show.php?sid=' . $row['id'] . '" class="action-icon">';
                                                echo '<i class="mdi mdi-eye"></i>';
                                                echo '</a>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }

                                            echo '</tbody>';
                                            echo '</table>';
                                        } else {
                                            echo 'No rows found in the payment table.';
                                        }

                                        // Close the database connection
                                        mysqli_close($con);
                                    ?>

                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
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
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

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

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="importStatus"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImportStatus(message) {
            var importStatusElement = document.getElementById('importStatus');
            importStatusElement.innerText = message;
            $('#importModal').modal('show');
        }
    </script>

</body>

</html>