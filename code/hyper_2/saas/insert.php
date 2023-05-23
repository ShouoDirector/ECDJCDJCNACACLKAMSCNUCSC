<?php @include 'includes/session.php' ?>
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include('db.php');

if (isset($_POST['submit'])) {
    // Retrieve form data
    $title = $_POST['title'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $nation = $_POST['nation'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    // Verify the human verification code
    $code1 = $_POST['code1'];
    $code = $_POST['code'];
    if ($code1 != $code) {
        $msg = "Invalid code";
    } else {
        // Calculate the number of days between check-in and check-out
        $datetime1 = new DateTime($cin);
        $datetime2 = new DateTime($cout);
        $interval = $datetime1->diff($datetime2);
        $no_days = $interval->format('%a');

        // Set default status
        $default_status = 'Not Conform';

        // Insert data into 'roombook' table
        $insert_query = "INSERT INTO roombook (Title, FName, LName, Email, Phone, Country, National, TRoom, Bed, NRoom, cin, cout, stat, nodays) 
                        VALUES ('$title', '$fname', '$lname', '$email', '$phone', '$country', '$nation', '$troom', '$bed', '$nroom', '$cin', '$cout', '$default_status', '$no_days')";

        // Execute the query
        if (!mysqli_query($con, $insert_query)) {
            echo "Error: " . mysqli_error($con);
        }

        // Insert data into 'orders' table
        $snack = $_POST['snack'];
        $breakfast = $_POST['breakfast'];
        $lunch = $_POST['lunch'];
        $dinner = $_POST['dinner'];
        $special = $_POST['special'];
        $vip = $_POST['vip'];
        $ultimate = $_POST['ultimate'];

        $order_query = "INSERT INTO `orders` (snack, breakfast, lunch, dinner, special, vip, ultimate) 
                        VALUES ('$snack', '$breakfast', '$lunch', '$dinner', '$special', '$vip', '$ultimate')";

        // Execute the query
        if (!mysqli_query($con, $order_query)) {
            echo "Error: " . mysqli_error($con);
        }

        // Close the database connection
        mysqli_close($con);

        // Redirect to index.php
        //header("Location: dashboard.php");
        exit();
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

                <!-- Start Content-->
                <div class="container-fluid">
                    <form class="needs-validation" method="post" novalidate="">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pinarik</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active">Insert</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Insert</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">PERSONAL INFORMATION</h4>
                                        <p class="text-muted font-14">When inserting a customer into the room book
                                            table, you need to provide the necessary information about the customer,
                                            such as their name, email address, room details, check-in and check-out
                                            dates.
                                        </p>
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="custom-styles-preview"
                                                role="tabpanel">

                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">Title</label>
                                                    <div class="col-md-9">
                                                        <select class="form-select" name="title" required>
                                                            <option selected>Select Title</option>
                                                            <option value="Dr.">Dr</option>
                                                            <option value="Miss.">Miss</option>
                                                            <option value="Mr.">Mr</option>
                                                            <option value="Mrs.">Mrs</option>
                                                            <option value="Prof.">Prof</option>
                                                            <option value="Rev.">Rev.</option>
                                                            <option value="Rev. Fr.">Rev Fr.</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName3">First
                                                        Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="userName3"
                                                            name="fname" placeholder="ex. Ivan Miles" required="">
                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName3">Last
                                                        Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="userName3"
                                                            name="lname" placeholder="ex. Vista" required="">
                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName3">Email</label>
                                                    <div class="input-group col">
                                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                        <input type="email" class="form-control" id="userName3"
                                                            name="email" placeholder="ex. ivanmilesvista@gmail.com"
                                                            required="">
                                                        <div class="invalid-feedback">
                                                            Please choose email.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName3">Phone
                                                        Number</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="userName3"
                                                            name="phone" placeholder="ex. 09876543210" required>
                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>

                                                <?php
                                                                        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                                                                        ?>

                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName3">Passport
                                                        Country</label>
                                                    <div class="col-md-9">
                                                        <select class="form-select" name="country" required>
                                                            <option selected>Select Passport</option>
                                                            <?php
                                                                                    foreach($countries as $key => $value):
                                                                                    echo '<option value="'.$value.'">'.$value.'</option>'; //close your tags!!
                                                                                    endforeach;
                                                                                    ?>
                                                        </select>
                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="mt-2">
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" id="customRadio3" name="nation"
                                                                value="Filipino" class="form-check-input">
                                                            <label class="form-check-label"
                                                                for="customRadio3">Filipino</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" id="customRadio4" name="nation"
                                                                value="Foreigner" class="form-check-input">
                                                            <label class="form-check-label"
                                                                for="customRadio4">Foreigner</label>
                                                        </div>

                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div> <!-- end preview-->
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>





                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">RESERVATION</h4>
                                        <p class="text-muted font-14">When inserting a customer into the room book
                                            table, you need to provide the necessary information about the customer,
                                            such as their name, email address, room details, check-in and check-out
                                            dates.
                                        </p>
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="custom-styles-preview"
                                                role="tabpanel">

                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">Type of
                                                        Room</label>
                                                    <div class="col-md-9">
                                                        <select name="troom" class="form-control" required="">
                                                            <option value selected>Select Room</option>
                                                            <option value="Superior Room">SUPERIOR ROOM</option>
                                                            <option value="Deluxe Room">DELUXE ROOM</option>
                                                            <option value="Guest House">GUEST HOUSE</option>
                                                            <option value="Single Room">SINGLE ROOM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">Type of
                                                        Room</label>
                                                    <div class="col-md-9">
                                                        <select name="bed" class="form-control" required>
                                                            <option value selected>Select Bedding</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Double">Double</option>
                                                            <option value="Triple">Triple</option>
                                                            <option value="Quad">Quad</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">No. of
                                                        Rooms</label>
                                                    <div class="col-md-9">
                                                        <select name="nroom" class="form-control" required>
                                                            <option value selected>Select</option>
                                                            <option value="1">1</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-3">Check In</label>
                                                    <div class="col-md-9">
                                                        <input name="cin" type="date" class="form-control date">
                                                    </div>

                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-form-label col-md-3">Check Out</label>
                                                    <div class="col-md-9">
                                                        <input name="cout" type="date" class="form-control date">
                                                    </div>
                                                </div>

                                            </div> <!-- end preview-->
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>




                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">MEAL ORDERS</h4>
                                        <p class="text-muted font-14">When inserting a customer into the room book
                                            table, you need to provide the necessary information about the customer,
                                            such as their name, email address, room details, check-in and check-out
                                            dates.
                                        </p>
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="custom-styles-preview"
                                                role="tabpanel">

                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">Snack</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="snack">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label"
                                                        for="userName1">Breakfast</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="breakfast">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">Lunch</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="lunch">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label"
                                                        for="userName1">Dinner</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="dinner">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label"
                                                        for="userName1">Special</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="special">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">VIP</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="vip">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label"
                                                        for="userName1">Ultimate</label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="example-number" type="number"
                                                            min="0" name="ultimate">
                                                    </div>
                                                </div>


                                            </div> <!-- end preview-->
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>



                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">HUMAN VERIFICATION</h4>
                                        <p class="text-muted font-14">When inserting a customer into the room book
                                            table, you need to provide the necessary information about the customer,
                                            such as their name, email address, room details, check-in and check-out
                                            dates.
                                        </p>
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="custom-styles-preview"
                                                role="tabpanel">

                                                <div class="row mb-3">
                                                    <label class="col-md-3 col-form-label" for="userName1">Type this
                                                        code | <?php $Random_code=rand(); echo$Random_code; ?></label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" name="code1" id="example-number"
                                                            type="text" placeholder="Enter the random code"
                                                            name="snack">
                                                        <input type="hidden" name="code"
                                                            value="<?php echo $Random_code; ?>" />
                                                    </div>
                                                    <div style="text-align: right;">
                                                        <input type="submit" name="submit" class="btn btn-primary"
                                                            style="width: 200px; height: 40px; font-size: 16px;">
                                                    </div>

                                                </div>


                                            </div> <!-- end preview-->
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>







                        </div>
                    </form>

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

    <!-- Bootstrap Wizard Form js -->
    <script src="assets/vendor/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

    <!-- Wizard Form Demo js -->
    <script src="assets/js/pages/demo.form-wizard.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- Apexchart js -->
    <?php @include 'includes/demo.dashboard.php' ?>
    <?php @include 'includes/demo.crm-dashboard.php' ?>

</body>

</html>