<?php
// Include the database connection file
include 'config.php';

// Define variables and initialize with empty values
$username = $password = $user_type = '';
$username_err = $password_err = $user_type_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter your username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate user type
    if (empty($_POST['users']) || !in_array($_POST['users'], array('1', '2'))) {
        $user_type_err = 'Please select a user type.';
        $errorBorder = 'card border-danger';
    } else {
        $user_type = $_POST['users'];
    }

    // Check input errors before querying the database
    if (empty($username_err) && empty($password_err) && empty($user_type_err)) {
        // Prepare a select statement based on the selected user type
        if ($user_type == '1') {
            $sql = 'SELECT adminID as userid, username, status, password FROM Admins WHERE username = :username AND status = "active"';
        } elseif ($user_type == '2') {
            $sql = 'SELECT userID as userid, username, status, password FROM users WHERE username = :username AND status = "active"';
        }

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                
                if ($stmt->rowCount() == 1) {

                    if ($row = $stmt->fetch()) {
                        $hashed_password = $row['password'];
                        
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start(); 

                            // Store data in session variables
                            $_SESSION['userid'] = $row['userID'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['user_type'] = $user_type;

                            // Redirect to index.phpz
                            header('location: index.php');
                            exit();
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered is not valid.';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found or account has been deactivated.';
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>



<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RDCJ IMS</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
    }

    .bi {
        vertical-align: -.125em;
        fill: currentColor;
    }

    .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
    }

    .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
    }

    .bd-mode-toggle {
        z-index: 1500;
    }

    .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
    }

    .transparent-card {
        background-color: transparent !important;
        border: none !important;
    }
    </style>


    <!-- Custom styles for this template -->
    <link href="styles/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <?php include "includes/bgtheme.php" ?>

    <main class="form-signin w-100 m-auto">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-4 text-center">
                <img class="mx-auto" src="assets/imgs/logo.png" alt="" width="150" height="100">
            </div>
            <h1 class="h3 mb-3 fw-normal text-center"><b>SIGN IN FORM</b></h1>

            <div class="form-floating">
                <input type="text" name="username"
                    class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $username; ?>">
                <label for="floatingInput">Username</label>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-floating">
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <label for="floatingPassword">Password</label>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="<?php echo $errorBorder ?> mb-2 px-3 py-1">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="users" value="1" id="users">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Admin
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="users" value="2" id="users">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Employee
                    </label>
                </div>
                <span class="error" style="color: red; font-size:small;"><?php echo $user_type_err; ?></span>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit" style="margin-bottom: 10px;">Sign in</button>
            <p class="text-center">Don't have an account? <a href="signup.php">Sign Up</a> here.</p>
        </form>
    </main>
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>