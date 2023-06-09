<?php @include 'includes/session.php' ?>
<?php
include('db.php');

// Function to get the total rent with the condition
function getTotalRent()
{
    global $con;

    $query = "SELECT SUM(rent_price) AS total_rent FROM room WHERE place = 'Occupied'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_rent'];
    }

    // Handle the case where the query failed or no rows found
    return 0;
}

// Function to calculate the total meal costs
function calculateTotalMealCosts()
{
    global $con;

    $mealPricesQuery = "SELECT name, price FROM meal";
    $mealPricesResult = mysqli_query($con, $mealPricesQuery);

    $mealPrices = array();
    while ($row = mysqli_fetch_assoc($mealPricesResult)) {
        $mealPrices[$row['name']] = $row['price'];
    }

    $roombookQuery = "SELECT id FROM roombook WHERE stat = 'Conform'";
    $roombookResult = mysqli_query($con, $roombookQuery);

    $customerOrders = array();
    while ($row = mysqli_fetch_assoc($roombookResult)) {
        $id = $row['id'];
        $customerOrdersQuery = "SELECT Snack, Breakfast, Lunch, Dinner, Special, VIP, Ultimate FROM orders WHERE id = $id";
        $customerOrdersResult = mysqli_query($con, $customerOrdersQuery);
        $customerOrdersRow = mysqli_fetch_assoc($customerOrdersResult);

        if ($customerOrdersRow !== null) {
            $customerOrders[] = $customerOrdersRow;
        }
    }

    $total_meal_costs = 0;

    if (!empty($customerOrders)) {
        foreach ($customerOrders as $orders) {
            foreach ($orders as $mealType => $quantity) {
                $mealCost = $mealPrices[$mealType] * $quantity;
                $total_meal_costs += $mealCost;
            }
        }
    }

    return $total_meal_costs;
}

// Function to get the total values from the 'payment' table
function getTotalNotEarnings()
{
    global $con;

    $query = "SELECT SUM(ttot) AS room, SUM(btot) AS meal, SUM(tips) AS tips FROM payment";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    // Handle the case where the query failed or no rows found
    return array('room' => 0, 'meal' => 0, 'tips' => 0);
}

// Function to get the total number of customers
function getTotalCustomers()
{
    include ('db.php');

    $sql1 = "SELECT COUNT(*) AS count FROM roombook";
    $result1 = mysqli_query($con, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $count1 = $row1['count'];

    return $count1;
}

// Calculate the total rent
$total_rent = getTotalRent();

// Calculate the total meal costs
$total_meal_costs = calculateTotalMealCosts();

// Format the total meal costs to two decimal places
$total_meal_costs_formatted = number_format($total_meal_costs, 2);

// Get the total values from the 'payment' table
$totals = getTotalNotEarnings();

// Calculate the total earnings
$total_earnings_all = $totals['tips'] + $total_meal_costs + $total_rent;

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
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pinarik</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Analytics</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Analytics</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">

                                        <div class="col-6">
                                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Customers">
                                                Customers</h5>
                                            <h3 class="my-2 py-1 text-end"><?php echo getTotalCustomers(); ?></h3>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <div class="text-end">
                                                    <img src="https://cdn-icons-png.flaticon.com/128/3126/3126589.png"
                                                        alt="" width="60px">
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <?php
                                        function getTotalEarnings() {
                                            include('db.php');
                                        
                                            $sql = "SELECT SUM(fintot) AS total FROM payment";
                                            $result = mysqli_query($con, $sql);
                                            $row = mysqli_fetch_assoc($result);
                                            $e = round($row['total']);
                                            
                                            return $e;
                                        }                                        
                                        ?>
                                        <div class="col-6">
                                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Total Earnings">
                                                Total Earnings</h5>
                                            <h3 class="my-2 py-1 text-end"><?php echo '₱ ' . $total_earnings_all; ?></h3>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <div class="text-end">
                                                    <img src="https://cdn-icons-png.flaticon.com/128/5501/5501360.png"
                                                        alt="Total Earnings" width="60px">
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <?php
                                        function getTotalConforms() {
                                            include('db.php');
                                        
                                            $sql = "SELECT SUM(stat='Conform') AS total FROM roombook";
                                            $result = mysqli_query($con, $sql);
                                            $row = mysqli_fetch_assoc($result);
                                            $cf = $row['total'];
                                        
                                            return $cf;
                                        }
                                        ?>
                                        <div class="col-6">
                                            <h5 class="text-muted fw-normal mt-0 text-truncate"
                                                title="Conformed Customers">Conformed Customers</h5>
                                            <h3 class="my-2 py-1 text-end"><?php echo getTotalConforms(); ?></h3>
                                            <p class="mb-0 text-muted">
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <div class="text-end">
                                                    <img src="https://cdn-icons-png.flaticon.com/128/10296/10296120.png"
                                                        alt="Conform" width="60px">
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <?php
                                        function getTotalNotConforms() {
                                            include('db.php');
                                        
                                            $sql = "SELECT SUM(stat='Not Conform') AS total FROM roombook";
                                            $result = mysqli_query($con, $sql);
                                            $row = mysqli_fetch_assoc($result);
                                            $ncf = $row['total'];
                                        
                                            return $ncf;
                                        }
                                        ?>
                                        <div class="col-6">
                                            <h5 class="text-muted fw-normal mt-0 text-truncate"
                                                title="Not Conform Customers">
                                                Not Conform Customers</h5>
                                            <h3 class="my-2 py-1 text-end"><?php echo getTotalNotConforms() ?></h3>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <img src="https://cdn-icons-png.flaticon.com/128/1256/1256650.png"
                                                    alt="Not Conform" width="60px">
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div>

                    <div class="row"> 
                    <?php
                            function getTotalFollowers(){
                                include ('db.php');

                                $sql = "SELECT COUNT(*) AS total FROM contact";
                                $result = mysqli_query($con, $sql);
                                $row = mysqli_fetch_assoc($result);
                                $fl = $row['total'];

                                return $fl;
                            }
                        ?>
                        <!-- ============= TOP ROOMS CARD ============= -->
                        <div class="col-xl-5 col-lg-12 order-lg-1 order-xl-1">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="float-start m-2 me-4">
                                            <img src="<?php echo $profilePicURL; ?>" style="height: 100px;" alt="avatar-2" class="rounded-circle img-thumbnail">
                                        </span>
                                        <div class="">
                                            <h4 class="mt-1 mb-1"><?php echo $_SESSION["user"]; ?></h4>
                                            <p class="font-13">Management</p>
                                    
                                            <ul class="mb-0 list-inline">
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1">₱ <?php echo $total_earnings_all ?></h5>
                                                    <p class="mb-0 font-13">Total Earnings</p>
                                                </li>
                                                <li class="list-inline-item">
                                                    <h5 class="mb-1"><?php echo getTotalFollowers() ?></h5>
                                                    <p class="mb-0 font-13">Number of Followers</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- end div-->
                                    </div>
                                    <!-- end card-body-->
                                </div>

                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <h4 class="header-title">Top Rooms Selected By Customers</h4>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle arrow-none card-drop"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <?php
                                        include('db.php');

                                        $sql = "SELECT troom, SUM(ttot) AS total_amount, COUNT(*) AS customer_count, MAX(cout) AS latest_date FROM payment GROUP BY troom ORDER BY total_amount DESC";
                                        $result = mysqli_query($con, $sql);
                                        ?>

                                        <table class="table table-centered table-nowrap table-hover mb-0">
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td>
                                                        <h5 class="font-14 my-1 fw-normal"><?php echo $row['troom']; ?>
                                                        </h5>
                                                        <span
                                                            class="text-muted font-13"><?php echo date('d F Y', strtotime($row['latest_date'])); ?></span>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-14 my-1 fw-normal">
                                                        ₱ <?php echo number_format($row['total_amount'], 2); ?></h5>
                                                        <span class="text-muted font-13">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-14 my-1 fw-normal">
                                                            <?php echo $row['customer_count']; ?></h5>
                                                        <span class="text-muted font-13">Customers</span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive-->
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->

                            
                        </div> <!-- end col-->

                        
                                    
                        <!-- ============= TOP ROOMS CARD ============= -->
                        <div class="col-lg-4 order-lg-2">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="header-title">Involvements</h4>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div id="dash-campaigns-chart" class="apex-charts" data-colors="#ffbc00,#727cf5,#0acf97, red"></div>

                                        <div class="row text-center mt-3">
                                            <div class="col-sm-4">
                                                <i class="ri ri-team-line widget-icon rounded-circle bg-warning-lighten text-warning"></i>
                                                <h3 class="fw-normal mt-3">
                                                    <span><?php echo getTotalCustomers(); ?></span>
                                                </h3>
                                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i> Customers</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <i class="ri ri-contacts-line widget-icon rounded-circle bg-primary-lighten text-primary"></i>
                                                <h3 class="fw-normal mt-3">
                                                    <span><?php echo getTotalConforms() + getTotalNotConforms() ?></span>
                                                </h3>
                                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> Current</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <?php
                                                function getTotalPaymentRows() {
                                                    include('db.php');
                                                    
                                                    $sql = "SELECT COUNT(*) AS total FROM payment";
                                                    $result = mysqli_query($con, $sql);
                                                    $row = mysqli_fetch_assoc($result);
                                                    $count_ct = $row['total'];
                                                    
                                                    return $count_ct;
                                                }
                                                ?>
                                                <?php
                                                    
                                                    $getTotal = getTotalCustomers() + getTotalConforms() + getTotalNotConforms() + getTotalPaymentRows() + getTotalFollowers();
                                                    $getTotalCustomers = (getTotalCustomers() / $getTotal) * 100;
                                                    $getTotalCurrent = ((getTotalConforms() + getTotalNotConforms()) / $getTotal) * 100;
                                                    $getTotalPaymentRows = (getTotalPaymentRows() / $getTotal) * 100;

                                                ?>
                                                <i class="mdi mdi-email-open widget-icon rounded-circle bg-success-lighten text-success"></i>
                                                <h3 class="fw-normal mt-3">
                                                    <span><?php echo getTotalPaymentRows(); ?></span>
                                                </h3>
                                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i> Customers Paid</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card body-->
                                </div>
                                <!-- end card -->
                        </div>
                            <!-- end col-->

                        <!-- ============= TOP ROOMS CARD ============= -->
                        <div class="col-xl-3 col-lg-6 order-lg-3">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <h4 class="header-title">Total Earnings</h4>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle arrow-none card-drop"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                    </div>
                                </div>
                                

                                <div class="card-body pt-0">
                                <div id="average-sales" class="apex-charts mb-4 mt-2"
                                            data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00"></div>
                                            
                                            
                                            
                                                <div class="chart-widget-list">
                                                    <p>
                                                        <i class="mdi mdi-square text-primary"></i> Room Rent
                                                        <span class="float-end">₱ <?php echo $total_rent ?></span>
                                                    </p>
                                                    <p>
                                                        <i class="mdi mdi-square text-success"></i> Meals
                                                        <span class="float-end">₱ <?php echo $total_meal_costs_formatted ?></span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <i class="mdi mdi-square text-warning"></i> Tips
                                                        <span class="float-end">₱ <?php echo $totals['tips']; ?></span>
                                                    </p>
                                                </div>



                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>

                        <!-- end col -->

                        

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

    <!-- Apexchart js -->
    <?php @include 'includes/demo.dashboard.php' ?>
    <?php @include 'includes/demo.crm-dashboard.php' ?>

</body>

</html>