<?php
session_start();

// Session fixation protection: Regenerate session ID upon login
if (!isset($_SESSION["regenerated"])) {
    session_regenerate_id(true);
    $_SESSION["regenerated"] = true;
}

// CSRF token generation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION["user"])) {
    header("location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Hyper - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully nature friendly eco resort you can find" name="description" />
    <meta content="Hyper Eco Resort" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="../assets/js/hyper-config.js"></script>

    <!-- App css -->
    <link href="../assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
</head>

<body class="authentication-bg position-relative">
    <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100">
        <svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%' viewBox='0 0 800 800'>
            <g fill-opacity='0.22'>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.1);" cx='400' cy='400' r='600' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.2);" cx='400' cy='400' r='500' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.3);" cx='400' cy='400' r='300' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.4);" cx='400' cy='400' r='200' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.5);" cx='400' cy='400' r='100' />
            </g>
        </svg>
    </div>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header py-4 text-center opacity-75 bg-dark-subtle">
                            <a href="#">
                                <span><img src="../assets/images/hyper.png" alt="logo" height="62"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                                <p class="text-muted mb-4">Enter your username and password to access admin panel.
                                </p>
                            </div>

                            <form method="POST">

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Username</label>
                                    <input class="form-control" type="text" id="username" name="user" required=""
                                        placeholder="Enter your username">
                                </div>

                                <div class="mb-3">
                                    <a href="pages-recoverpw.html" class="text-muted float-end"><small>Forgot your
                                            password?</small></a>
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="pass" id="password" class="form-control"
                                            placeholder="Enter your password">
                                        <div class="input-group-text" data-password="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                <path
                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin"
                                            name="remember" checked>
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Log In </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        2023 - <script>
            document.write(new Date().getFullYear())
        </script> © Hyper - Hyper-eco-resort-resort-hotel.business.site
    </footer>
    <!-- Vendor js -->
    <script src="../assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="../assets/js/app2.min.js"></script>
</body>

</html>

<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Username and password sent from form
    $myusername = $_POST["user"];
    $mypassword = $_POST["pass"];

    // Validate input (example: check for non-empty username and password)

    // Hash the password using SHA-512
    $hashedPassword = hash('sha512', $mypassword);

    $stmt = $con->prepare("SELECT id FROM login WHERE usname = ? and pass = ?");
    $stmt->bind_param("ss", $myusername, $hashedPassword);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = mysqli_num_rows($result);

    // If result matched $myusername and $hashedPassword, table row must be 1 row
    if ($count == 1) {
        $_SESSION["user"] = $myusername;

        // Set a persistent cookie if "Remember me" checkbox is checked
        if (isset($_POST["remember"])) {
            $cookieValue = $_SESSION["user"];
            setcookie("remember_me_cookie", $cookieValue, time() + (30 * 24 * 60 * 60), "/"); // Cookie expires in 30 days
        } else {
            // If the "Remember me" checkbox is not checked, delete any existing cookie
            setcookie("remember_me_cookie", "", time() - 3600, "/");
        }

        header("location: dashboard.php");
        exit;
    } else {
        echo '<script>alert("Invalid username or password")</script>';
    }
}
?>