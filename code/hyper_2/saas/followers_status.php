<?php @include 'includes/session.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Followers | Admin Dashboard</title>
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
                                        <li class="breadcrumb-item active">Followers</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Followers</h4>
                            </div>
                        </div>
                    </div>

                    <?php
                        include('db.php');

                        $countQuery = "SELECT COUNT(*) AS total FROM `contact`";
                        $result = mysqli_query($con, $countQuery);
                        $row = mysqli_fetch_assoc($result);
                        $f = $row['total'];

                        mysqli_close($con);
                        ?>

                    
                    <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-sm-5">
                                                <a href="javascript:void(0);" class="btn btn-primary mb-2">Total Followers : <?php echo $f ?></a>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="text-sm-end">
                                                <style>
                                                    .form-inline {
                                                        display: inline-block;
                                                    }
                                                </style>

                                                <form action="import_nc.php" method="post" enctype="multipart/form-data" class="form-inline">
                                                    <div class="d-inline-block">
                                                        <input type="file" name="csvFile" id="csvFile" class="form-control border-0 mb-2">
                                                    </div>
                                                    <button type="submit" name="import" class="btn btn-light mb-2">Import CSV</button>
                                                </form>
                                                <form action="export_nc.php" method="post" class="form-inline">
                                                    <button type="submit" name="export" class="btn btn-light mb-2" style="display: inline-block;">Export CSV</button>
                                                </form>

                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="table-responsive">
                                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="products-datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20px;">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                                <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                                            </div>
                                                        </th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>ID</th>
                                                        <th>Phone Number</th>
                                                        <th>Follow Start</th>
                                                        <th>Permission Status</th>
                                                        <th>More</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    include('db.php');

                                                    $tsql = "SELECT * FROM contact";
                                                    $stmt = mysqli_prepare($con, $tsql);
                                                    mysqli_stmt_execute($stmt);
                                                    $result = mysqli_stmt_get_result($stmt);

                                                    while ($trow = mysqli_fetch_array($result)) {
                                                        $coutFormatted = date("F j, Y", strtotime($trow['cdate']));

                                                        echo "<tr id='row-" . $trow['id'] . "'>
                                                                <td>
                                                                    <div class='form-check'>
                                                                        <input type='checkbox' class='form-check-input' id='customCheck2'>
                                                                        <label class='form-check-label' for='customCheck2'>&nbsp;</label>
                                                                    </div>
                                                                </td>
                                                                <td class='table-user'>
                                                                    <a href='javascript:void(0);' class='text-body fw-semibold'>" . $trow['fullname'] . "</a>
                                                                </td>
                                                                <td>
                                                                    " . $trow['email'] . "
                                                                </td>
                                                                <td>
                                                                    " . $trow['id'] . "
                                                                </td>
                                                                <td>
                                                                    " . $trow['phoneno'] . "
                                                                </td>
                                                                <td>
                                                                    " . $coutFormatted . "
                                                                </td>
                                                                <td>";

                                                        if ($trow['approval'] == 'Allowed') {
                                                            echo "<span class='badge badge-success-lighten'>" . $trow['approval'] . "</span>";
                                                        } else {
                                                            echo "<span class='badge badge-danger-lighten'>" . $trow['approval'] . "</span>";
                                                        }

                                                        echo "</td>
                                                                <td class='text-center'>
                                                                    <a href='newsletter.php' class='action-icon'>
                                                                        <i class='mdi mdi-square-edit-outline'></i>
                                                                    </a>
                                                                </td>
                                                            </tr>";
                                                    }
                                                    ?>

                                                </tbody>


                                            </table>
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

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
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