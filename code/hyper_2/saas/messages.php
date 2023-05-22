<?php @include 'includes/session.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Newsletters | Admin Dashboard</title>
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
                                        <li class="breadcrumb-item active">Newsletters</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Newsletters</h4>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane show active" id="modal-varying-preview">
                        <div class="hstack gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Send Newsletters to your Followers</button>
                        </div>

                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <div class="mb-3">
                                                <label for="recipient-name" class="col-form-label">Title</label>
                                                <input type="text" name="title" class="form-control" id="recipient-name"
                                                    placeholder="Enter Title">
                                            </div>
                                            <div class="mb-3">
                                                <label class="col-form-label">Subject</label>
                                                <input name="subject" class="form-control" placeholder="Enter Subject">
                                            </div>
                                            <div class="mb-3">
                                                <label for="message-text" class="col-form-label">Message:</label>
                                                <textarea name="news" class="form-control" id="message-text"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <input type="submit" name="log" value="Send" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        include('db.php'); // Make sure to include the database connection file

                        if (isset($_POST['log'])) {
                            // Validate and sanitize user input
                            $title = mysqli_real_escape_string($con, $_POST['title']);
                            $subject = mysqli_real_escape_string($con, $_POST['subject']);
                            $news = mysqli_real_escape_string($con, $_POST['news']);

                            // Create the SQL query using prepared statements
                            $query = "INSERT INTO `newsletterlog`(`title`, `subject`, `news`) VALUES (?, ?, ?)";
                            $stmt = mysqli_prepare($con, $query);

                            if ($stmt) {
                                // Bind the parameters to the prepared statement
                                mysqli_stmt_bind_param($stmt, 'sss', $title, $subject, $news);

                                // Execute the prepared statement
                                if (mysqli_stmt_execute($stmt)) {
                                    echo '<script>alert("Message Sent!");</script>';
                                } else {
                                    echo '<script>alert("Failed to send message.");</script>';
                                }

                                // Close the prepared statement
                                mysqli_stmt_close($stmt);
                            } else {
                                echo '<script>alert("Failed to prepare statement.");</script>';
                            }
                        }
                        ?>



                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="header-title">Followers</h4>
                                    <p class="text-muted font-14">
                                        Send <code>newsletters</code> to your followers.
                                    </p>
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="basic-borderless-preview" role="tabpanel">
                                            <div class="table-responsive-sm">
                                                <table class="table table-centered table-borderless mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone Number</th>
                                                            <th>Email</th>
                                                            <th>Date</th>
                                                            <th>Allowed</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        include('db.php'); // Database connection

                                                        // Function to convert date to the desired format
                                                        function formatDate($date) {
                                                            return date('F j, Y', strtotime($date));
                                                        }

                                                        if (isset($_POST['id'], $_POST['switch'])) {
                                                            $id = $_POST['id'];
                                                            $approval = $_POST['switch'];

                                                            // Toggle the value
                                                            $approval = ($approval == 'Allowed') ? 'Not Allowed' : 'Allowed';

                                                            // Prepare and execute the SQL statement to update the value in the database using prepared statement
                                                            $updateQuery = "UPDATE contact SET approval = ? WHERE id = ?";
                                                            $stmt = mysqli_prepare($con, $updateQuery);
                                                            mysqli_stmt_bind_param($stmt, 'si', $approval, $id);
                                                            mysqli_stmt_execute($stmt);
                                                            mysqli_stmt_close($stmt);
                                                        }

                                                        $query = "SELECT * FROM contact"; // Replace 'contact' with your actual table name
                                                        $result = mysqli_query($con, $query);
                                                        ?>

                                                        <tbody>
                                                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                                <tr class="gradeC">
                                                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                                                    <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
                                                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                                    <td><?php echo formatDate($row['cdate']); ?></td>
                                                                    <td>
                                                                        <form method="POST">
                                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                            <input type="hidden" name="switch" value="<?php echo $row['approval']; ?>">

                                                                            <div>
                                                                                <input type="checkbox" id="switch<?php echo $row['id']; ?>" <?php echo ($row['approval'] == 'Allowed') ? 'checked' : ''; ?> name="toggleSwitch" value="1" data-switch="success" onchange="this.form.submit()">
                                                                                <label for="switch<?php echo $row['id']; ?>" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                                                                            </div>
                                                                        </form>
                                                                    </td>
                                                                    <td><a href="newsletterdel.php?eid=<?php echo $row['id']; ?>" class="btn btn-danger"><i class="fa fa-edit"></i> Delete</a></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>

                                                </table>
                                            </div> <!-- end table-responsive-->
                                        </div> <!-- end preview-->
                                    </div> <!-- end tab-content-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
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

        <!-- Code Highlight js -->
        <script src="assets/vendor/highlightjs/highlight.pack.min.js"></script>
        <script src="assets/vendor/clipboard/clipboard.min.js"></script>
        <script src="assets/js/hyper-syntax.js"></script>

        <script>
            const exampleModal = document.getElementById('exampleModal')
            exampleModal.addEventListener('show.bs.modal', event => {
                // Button that triggered the modal
                const button = event.relatedTarget
                // Extract info from data-bs-* attributes
                const recipient = button.getAttribute('data-bs-whatever')
                // If necessary, you could initiate an AJAX request here
                // and then do the updating in a callback.
                //
                // Update the modal's content.
                const modalTitle = exampleModal.querySelector('.modal-title')
                const modalBodyInput = exampleModal.querySelector('.modal-body input')

                modalTitle.textContent = `New Message to your followers`
                modalBodyInput.value = recipient
            })
        </script>

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