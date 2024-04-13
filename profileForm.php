<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('location: login.php');
    exit();
}

// Include the database connection file if needed
include 'config.php';

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
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RDCJ IMS]</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/sideIcon.css">
    <link href="styles/indexStyle.css" rel="stylesheet">
</head>

<body>

<div id="successMessage" class="alert alert-success alert-dismissible" role="alert"
        style='position: fixed; top: 20px; right: 20px; z-index: 9999;' hidden>
        Profile Updated Successfully
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="editsvg" viewBox="0 0 16 16">
            <path
                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
            <path fill-rule="evenodd"
                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
        </symbol>
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
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>

                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </nav>
            <div class="profile-container position-relative">
                <a href="profileedit.php" class="position-absolute top-0 end-0 m-3 text-decoration-none">
                    <svg class="bi" width="25" height="25" fill="currentColor">
                        <use xlink:href="#editsvg" />
                    </svg>
                </a>
                <img src="assets/imgs/roundpic.png" alt="Profile Picture" class="profile-pic img-fluid">
                <h2><?php echo (isset($userData['firstname']) ? $userData['firstname'] : ' ') . ' ' . (isset($userData['lastname']) ? $userData['lastname'] : ' '); ?>
                </h2>
                <hr>

                <?php if ($userType == 1): ?>
                <div>Admin</div>
                <?php elseif ($userType == 2): ?>
                <div>Employee</div>
                <?php endif; ?>
                <div class="user-info">
                    <div><strong>Username:</strong>
                        <?php echo isset($userData['username']) ? $userData['username'] : ' '; ?></div>
                    <div><strong>Email:</strong> <?php echo isset($userData['email']) ? $userData['email'] : ' '; ?>
                    </div>
                    <div><strong>Phone Number:</strong>
                        <?php echo isset($userData['phoneNumber']) ? $userData['phoneNumber'] : ' '; ?></div>
                    <div><strong>Address:</strong>
                        <?php echo isset($userData['address']) ? $userData['address'] : ' '; ?></div>
                    <div><strong>Facebook Account:</strong>
                        <?php echo isset($userData['fbAcc']) ? $userData['fbAcc'] : ' '; ?></div>
                    <div><strong>X Account:</strong> <?php echo isset($userData['xAcc']) ? $userData['xAcc'] : ' '; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sidebars.js"></script>

    <script>
    const urlParams = new URLSearchParams(window.location.search);
    const editSuccess = urlParams.get('editSuccess');
    const successMessage = document.getElementById('successMessage');

    // If deleteSuccess is present and equals 1, show the success message for 2 seconds
    if (editSuccess === '1') {
        successMessage.textContent = 'Profile Updated Successfully';
        successMessage.removeAttribute('hidden');
        setTimeout(() => {
            successMessage.setAttribute('hidden', true);
        }, 2000);
    }
    </script> 
</body>

</html>