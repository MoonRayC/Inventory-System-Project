<?php
// Include the necessary files (config.php and any other required files)
include 'config.php';

// Start the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('location: login.php');
    exit();
}

// Fetch user data from the database
$userType = $_SESSION['user_type'];
$userid = $_SESSION['userid'];

if($userType == 1){
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE adminid = :userid"); 
} else if($userType == 2){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE userid = :userid");
}
$stmt->bindParam(":userid", $userid);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Define variables and initialize with user data
$username = $userData['username'];
$firstname = $userData['firstname'];
$lastname = $userData['lastname'];
$email = $userData['email'];
$phoneNumber = $userData['phoneNumber'];
$fbAcc = $userData['fbAcc'];
$xAcc = $userData['xAcc'];

// Define variables for error messages
$username_err = $firstname_err = $lastname_err = $email_err = $phoneNumber_err = $fbAcc_err = $xAcc_err = $password_err = '';

// Check if the form is submitted for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data and update the user's profile
    $enteredPassword = trim($_POST['oldpassword']);

    // Validate the entered password
    $storedPassword = $userData['password'];
    if (empty($enteredPassword)) {
        $password_err = 'Enter Password to Save Changes';
    } elseif (!password_verify($enteredPassword, $storedPassword)) {
        $password_err = 'Incorrect old password';
    } else {
        // Password is correct, proceed with the update
        // Update profile details
        $username = trim($_POST['username']);
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $phoneNumber = trim($_POST['phoneNumber']);
        $fbAcc = trim($_POST['fbAccount']);
        $xAcc = trim($_POST['XAccount']);

        if($userType == 1){
            $updateProfileSql = 'UPDATE admins SET username = :username, firstname = :firstname, lastname = :lastname, email = :email, phoneNumber = :phoneNumber, fbAcc = :fbAcc, xAcc = :xAcc WHERE adminID = :userid';
        } else if($userType == 2){
            $updateProfileSql = 'UPDATE users SET username = :username, firstname = :firstname, lastname = :lastname, email = :email, phoneNumber = :phoneNumber, fbAcc = :fbAcc, xAcc = :xAcc WHERE userID = :userid';
        }
        $updateProfileStmt = $pdo->prepare($updateProfileSql);
        $updateProfileStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':fbAcc', $fbAcc, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':xAcc', $xAcc, PDO::PARAM_STR);
        $updateProfileStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $updateProfileStmt->execute();

        // Check if a new password is provided
        $newPassword = trim($_POST['newpassword']);
        if (!empty($newPassword)) {
            // Hash and update the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Add your database update logic here to update the password
            $updatePasswordSql = 'UPDATE users SET password = :password WHERE userID = :userid';
            if($userType == 1){
                $updatePasswordSql = 'UPDATE admins SET password = :password WHERE adminID = :userid';
            } else if($userType == 2){
                $updatePasswordSql = 'UPDATE users SET password = :password WHERE userID = :userid';
            }
            $updatePasswordStmt = $pdo->prepare($updatePasswordSql);
            $updatePasswordStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $updatePasswordStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $updatePasswordStmt->execute();
        }

        // Redirect to the profile page or any other desired page after updating
        header("location: profileForm.php?userID=$userId&editSuccess=1");
        exit();
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RDCJ IMS</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">



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
    
    <link href="styles/indexStyle.css" rel="stylesheet">
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="house-door-fill" viewBox="0 0 16 16">
            <path
                d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
        </symbol>
    </svg>
    <?php include "includes/bgtheme.php" ?>

    <main class="d-flex flex-nowrap">
        <?php include "includes/sidebar.php" ?>


        <div class="b-example-divider b-example-vr"></div>
        <div class="container">
            <nav class="navbar bg-body">
                <div class="container-fluid">

                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="link-body-emphasis" href="index.php">
                                    <svg class="bi" width="16" height="16">
                                        <use xlink:href="#house-door-fill"></use>
                                    </svg>
                                    <span class="visually-hidden">Home</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="profileForm.php">Profile</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                        </ol>
                    </nav>

                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Profile</h5>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                        <div class="form-floating">
                            <input type="text" name="username" id="username"
                                class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $userData['username']; ?>">
                            <label for="floatingInput">Username</label>
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="firstname" id="firstname"
                                        class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $userData['firstname']; ?>">
                                    <label for="floatingInput">Firstname</label>
                                    <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="lastname" id="lastname"
                                        class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $userData['lastname']; ?>">
                                    <label for="floatingInput">Lastname</label>
                                    <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="email" id="email"
                                        class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $userData['email']; ?>">
                                    <label for="floatingInput">Email</label>
                                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="phoneNumber" id="phoneNumber"
                                        class="form-control <?php echo (!empty($phoneNumber_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $userData['phoneNumber']; ?>">
                                    <label for="floatingInput">Phone Number</label>
                                    <span class="invalid-feedback"><?php echo $phoneNumber_err; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="fbAccount" id="fbAccount"
                                        class="form-control <?php echo (!empty($fbAcc_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $userData['fbAcc']; ?>">
                                    <label for="floatingInput">Facebook Account</label>
                                    <span class="invalid-feedback"><?php echo $fbAcc_err; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="XAccount" id="XAccount"
                                        class="form-control <?php echo (!empty($xAcc_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $userData['xAcc']; ?>">
                                    <label for="floatingInput">X Account</label>
                                    <span class="invalid-feedback"><?php echo $xAcc_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" name="newpassword" id="newpassword" class="form-control">
                                    <label for="floatingInput">New Password</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" name="confirmpassword" id="confirmpassword"
                                        class="form-control">
                                    <label for="floatingInput">Confirm Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="password" name="oldpassword" id="oldpassword"
                                class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <label for="floatingInput">Password</label>
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-start">
                                <button type="submit" class="btn btn-primary" style="margin-right: 20px;">Update Profile</button>
                                <a href="profileForm.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>




        </div>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sidebars.js"></script>
</body>

</html>