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

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <form class="d-flex">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-light"
                                                id="dash-daterange">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="mdi mdi-calendar-range font-13"></i>
                                            </span>
                                        </div>
                                        <a href="javascript: void(0);" class="btn btn-primary ms-2">
                                            <i class="mdi mdi-autorenew"></i>
                                        </a>
                                    </form>
                                </div>
                                <h4 class="page-title">Nothing Here</h4>
                            </div>
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
    <?php @include 'includes/js_links.php' ?>

</body>

</html>