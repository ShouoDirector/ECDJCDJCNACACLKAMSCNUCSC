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

<?php
// Include database connection
include('db.php');

// Function to hash password using SHA-512
function hashPassword($password) {
    return hash('sha512', $password);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $newUsername = $_POST['username'];
    $newPassword = hashPassword($_POST['password']);

    // Retrieve the profile picture file
    $newProfilePic = $_FILES['profile_pic']['tmp_name'];

    // Validate and sanitize input values
    $newUsername = preg_replace("/[^a-zA-Z\s]/", '', $newUsername);
    $newUsername = trim($newUsername);

    // Convert the profile picture file to a binary string
    $profilePicData = file_get_contents($newProfilePic);

    // Prepare and execute the update query
    $query = "UPDATE login SET usname = ?, pass = ?, profile_pic = ? WHERE id = 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $newUsername, $newPassword, $profilePicData);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->affected_rows > 0) {
        echo "Admin details updated successfully.";
    } else {
        echo "Failed to update admin details.";
    }

    // Close statement and database connection
    $stmt->close();
    $con->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile | Admin Dashboard</title>
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
        <form method="post" enctype="multipart/form-data">
        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="row">
                        
                            <div class="row col-sm-12">
                                <!-- Profile -->
                                <div class="card bg-primary">
                                    <div class="card-body profile-user-box">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar-lg">
                                                            <img src="<?php echo $profilePicURL; ?>" alt="" class="rounded-circle img-thumbnail">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div>
                                                            <h4 class="mt-1 mb-1 text-white"><?php echo $_SESSION["user"]; ?></h4>
                                                            <p class="font-13 text-white-50"> Management</p>
    
                                                            <ul class="mb-0 list-inline text-light">
                                                                <li class="list-inline-item me-3">
                                                                    <h5 class="mb-1 text-white"><?php echo '₱' . number_format($total_earnings_all, 2) ?></h5>
                                                                    <p class="mb-0 font-13 text-white-50">Total Earnings</p>
                                                                </li>
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
                                                                <li class="list-inline-item">
                                                                    <h5 class="mb-1 text-white"><?php echo getTotalFollowers() ?></h5>
                                                                    <p class="mb-0 font-13 text-white-50">Number of Followers</p>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- end col-->

                                            <div class="col-sm-4">
                                                <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                                    <input class="btn btn-light" type="submit" name="submit" value="Update Profile">
                                                </div>
                                            </div> <!-- end col-->
                                        </div> <!-- end row -->

                                        

                                    </div> <!-- end card-body/ profile-user-box-->
                                </div><!--end profile/ card -->
                            </div>

                            <div class="row">
                            <div class="col-lg-5">
                                <div class="card h-100">
                                <div class="card cta-box bg-primary text-white">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start align-items-center">
                                                <div class="w-100 overflow-hidden">
                                                    <h2 class="mt-0"><i class="mdi mdi-bullhorn-outline"></i>&nbsp;</h2>
                                                    <h3 class="m-0 fw-normal cta-box-title">Enhance your <b>Campaign</b> for better outreach <i class="mdi mdi-arrow-right"></i></h3>
                                                </div>
                                                <img class="ms-1" src="assets/images/svg/email-campaign.svg" width="120" alt="Generic placeholder image">
                                            </div>
                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Change Profile Settings</h4>
                                    
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="basic-form-preview" role="tabpanel">
                                                    <div class="mb-2">
                                                        <label class="form-label">Username</label>
                                                        <input type="text" name="username" required class="form-control" placeholder="Enter new username">
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label">Password</label>
                                                        <div class="input-group input-group-merge border-0">
                                                            <input type="password" name="password" class="form-control" placeholder="Enter new password">
                                                            <div class="input-group-text" data-password="false">
                                                                <span class="password-eye"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-0 mt-3">
                                                    <input type="file" name="profile_pic" id="example-fileinput" accept="image/*" class="form-control border-0">
                                                    </div>                                 
                                            </div> <!-- end preview-->
                                    
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <div class="col-xl-7">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="header-title">Your Calendar</h4>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Weekly Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Monthly Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Settings</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div data-provide="datepicker-inline" data-date-today-highlight="true" class="calendar-widget"><div class="datepicker datepicker-inline"><div class="datepicker-days" style=""><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev text-center">«</th><th colspan="5" class="datepicker-switch text-center">May 2023</th><th class="next text-center">»</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="old day" data-date="1682812800000">30</td><td class="day" data-date="1682899200000">1</td><td class="day" data-date="1682985600000">2</td><td class="day" data-date="1683072000000">3</td><td class="day" data-date="1683158400000">4</td><td class="day" data-date="1683244800000">5</td><td class="day" data-date="1683331200000">6</td></tr><tr><td class="day" data-date="1683417600000">7</td><td class="day" data-date="1683504000000">8</td><td class="day" data-date="1683590400000">9</td><td class="day" data-date="1683676800000">10</td><td class="day" data-date="1683763200000">11</td><td class="day" data-date="1683849600000">12</td><td class="day" data-date="1683936000000">13</td></tr><tr><td class="day" data-date="1684022400000">14</td><td class="day" data-date="1684108800000">15</td><td class="day" data-date="1684195200000">16</td><td class="day" data-date="1684281600000">17</td><td class="day" data-date="1684368000000">18</td><td class="day" data-date="1684454400000">19</td><td class="day" data-date="1684540800000">20</td></tr><tr><td class="day" data-date="1684627200000">21</td><td class="day" data-date="1684713600000">22</td><td class="today day" data-date="1684800000000">23</td><td class="day" data-date="1684886400000">24</td><td class="day" data-date="1684972800000">25</td><td class="day" data-date="1685059200000">26</td><td class="day" data-date="1685145600000">27</td></tr><tr><td class="day" data-date="1685232000000">28</td><td class="day" data-date="1685318400000">29</td><td class="day" data-date="1685404800000">30</td><td class="day" data-date="1685491200000">31</td><td class="new day" data-date="1685577600000">1</td><td class="new day" data-date="1685664000000">2</td><td class="new day" data-date="1685750400000">3</td></tr><tr><td class="new day" data-date="1685836800000">4</td><td class="new day" data-date="1685923200000">5</td><td class="new day" data-date="1686009600000">6</td><td class="new day" data-date="1686096000000">7</td><td class="new day" data-date="1686182400000">8</td><td class="new day" data-date="1686268800000">9</td><td class="new day" data-date="1686355200000">10</td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2023</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month">Mar</span><span class="month">Apr</span><span class="month focused">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2020-2029</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2019</span><span class="year">2020</span><span class="year">2021</span><span class="year">2022</span><span class="year focused">2023</span><span class="year">2024</span><span class="year">2025</span><span class="year">2026</span><span class="year">2027</span><span class="year">2028</span><span class="year">2029</span><span class="year new">2030</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-decades" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2000-2090</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="decade old">1990</span><span class="decade">2000</span><span class="decade">2010</span><span class="decade focused">2020</span><span class="decade">2030</span><span class="decade">2040</span><span class="decade">2050</span><span class="decade">2060</span><span class="decade">2070</span><span class="decade">2080</span><span class="decade">2090</span><span class="decade new">2100</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div><div class="datepicker-centuries" style="display: none;"><table class="table-condensed"><thead><tr><th colspan="7" class="datepicker-title" style="display: none;"></th></tr><tr><th class="prev">«</th><th colspan="5" class="datepicker-switch">2000-2900</th><th class="next">»</th></tr></thead><tbody><tr><td colspan="7"><span class="century old">1900</span><span class="century focused">2000</span><span class="century">2100</span><span class="century">2200</span><span class="century">2300</span><span class="century">2400</span><span class="century">2500</span><span class="century">2600</span><span class="century">2700</span><span class="century">2800</span><span class="century">2900</span><span class="century new">3000</span></td></tr></tbody><tfoot><tr><th colspan="7" class="today" style="display: none;">Today</th></tr><tr><th colspan="7" class="clear" style="display: none;">Clear</th></tr></tfoot></table></div></div></div>
                                            </div> <!-- end col-->
<!-- end col -->
                                        </div>
                                        <!-- end row -->

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
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
        </form>
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

    <!-- plugin js -->
    <script src="assets/vendor/dropzone/min/dropzone.min.js"></script>
    <!-- init js -->
    <script src="assets/js/ui/component.fileupload.js"></script>

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