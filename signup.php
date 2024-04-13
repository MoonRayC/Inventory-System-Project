<?php
// Include the database connection file
include 'config.php';

// Define variables and initialize with empty values
$username = $firstname = $lastname = $email = $phoneNumber = $password = $confirm_password = '';
$username_err = $firstname_err = $lastname_err = $email_err = $phoneNumber_err = $password_err = $confirm_password_err = '';

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

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate phone number
    if (empty(trim($_POST['phoneNumber']))) {
        $phoneNumber_err = 'Please enter your phone number.';
    } else {
        $phoneNumber = trim($_POST['phoneNumber']);
    }

    // Validate password
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
    if (empty($username_err) && empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($phoneNumber_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = 'INSERT INTO users (username, firstname, lastname, email, phoneNumber, password, status, userType) 
                    VALUES (:username, :firstname, :lastname, :email, :phoneNumber, :password, "inactive", 2)';

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':firstname', $param_firstname, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            $stmt->bindParam(':phoneNumber', $param_phoneNumber, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_username = $username;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_email = $email;
            $param_phoneNumber = $phoneNumber;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header('location: login.php');
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
    <link rel="stylesheet" href="styles/sideIcon.css">
    <link href="styles/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <?php include "includes/bgtheme.php" ?>

    <main class="form-signin w-100 m-auto" style="max-width: 450px;">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-4 text-center">
                <img class="mx-auto" src="assets/imgs/logo.png" alt="" width="200" height="100">
            </div>
            <h1 class="h3 mb-3 fw-normal text-center">REGISTER ACCOUNT</h1>
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
                <input type="email" name="email"
                    class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $email; ?>">
                <label for="floatingInput">Email</label>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-floating">
                <input type="text" name="phoneNumber"
                    class="form-control <?php echo (!empty($phoneNumber_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $phoneNumber; ?>">
                <label for="floatingInput">Phone Number</label>
                <span class="invalid-feedback"><?php echo $phoneNumber_err; ?></span>
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
                onclick="window.location.href='login.php'">Cancel</button>
        </form>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>