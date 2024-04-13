<?php
include 'config.php';

session_start();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('location: login.php');
    exit();
}

$userType = $_SESSION['user_type'];

// Define variables and initialize with empty values
$username = $firstname = $lastname = $password = $confirm_password = '';
$username_err = $firstname_err = $lastname_err = $password_err = $confirm_password_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter a username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate firstname
    if (empty(trim($_POST['firstname']))) {
        $firstname_err = 'Please enter your firstname.';
    } else {
        $firstname = trim($_POST['firstname']);
    }

    // Validate lastname
    if (empty(trim($_POST['lastname']))) {
        $lastname_err = 'Please enter your lastname.';
    } else {
        $lastname = trim($_POST['lastname']);
    }

    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = 'Password must have at least 6 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($firstname_err) && empty($lastname_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = 'INSERT INTO admins (username, firstname, lastname, password, status, userType) 
                    VALUES (:username, :firstname, :lastname, :password, "active", 1)';

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':firstname', $param_firstname, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_username = $username;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header('location: userData.php');
            } else {
                echo 'Something went wrong. Please try again later.';
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
    </style>


    <!-- Custom styles for this template -->
    <link href="styles/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <?php include "includes/bgtheme.php" ?>

    <main class="form-signin w-100 m-auto" style="max-width: 450px;">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-4 text-center">
                <img class="mx-auto" src="assets/imgs/logo.png" alt="" width="200" height="100">
            </div>
            <h1 class="h3 mb-3 fw-normal text-center">CREATE ADMIN ACCOUNT</h1>
            <div class="form-floating">
                <input type="text" name="username"
                    class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $username; ?>">
                <label for="floatingInput">Username</label>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-floating">
                <input type="text" name="firstname"
                    class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $firstname; ?>">
                <label for="floatingInput">Firstname</label>
                <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
            </div>
            <div class="form-floating">
                <input type="text" name="lastname"
                    class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $lastname; ?>">
                <label for="floatingInput">Lastname</label>
                <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
            </div>
            <div class="form-floating">
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $password; ?>">
                <label for="floatingPassword">Password</label>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-floating">
                <input type="password" name="confirm_password"
                    class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $confirm_password; ?>">
                <label for="floatingPassword">Confirm Password</label>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit" style="margin-bottom: 10px;">Sign in</button>
            <button type="button" class="btn btn-danger w-100 py-2"
                onclick="window.location.href='userData.php'">Cancel</button>
        </form>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>