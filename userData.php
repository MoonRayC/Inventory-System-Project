<?php
session_start();

 if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('location: login.php');
    exit();
} 

include 'config.php';

$userType = $_SESSION['user_type'];

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
    <link rel="stylesheet" href="styles/sideIcon.css">
</head>

<body>

    <div id="successMessage" class="alert alert-success alert-dismissible" role="alert"
        style='position: fixed; top: 20px; right: 20px; z-index: 9999; width:25rem;' hidden>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="add" viewBox="0 0 16 16">
            <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
        </symbol>
        <symbol id="house-door-fill" viewBox="0 0 16 16">
            <path
                d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
        </symbol>
        <symbol id="refresh" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
            <path
                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
        </symbol>
        <symbol id="delete" viewBox="0 0 16 16">
            <path
                d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
        </symbol>
        <symbol id="edit" viewBox="0 0 16 16">
            <path
                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
            <path fill-rule="evenodd"
                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
        </symbol>
    </svg>
    <?php include "includes/bgtheme.php" ?>

    <main class="d-flex flex-nowrap">
        <?php include "includes/sidebar.php" ?>


        <div class="b-example-divider b-example-vr"></div>
        <div class="container">
            <nav class="navbar bg-body">
                <div class="container-fluid">

                    <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="link-body-emphasis" href="index.php">
                                    <svg class="bi" width="16" height="16">
                                        <use xlink:href="#house-door-fill"></use>
                                    </svg>
                                    <span class="visually-hidden">Home</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User Data</li>
                        </ol>
                    </nav>

                    <form class="d-flex" role="search">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control mr-2"
                                placeholder="Search by name">
                        </div>
                        <button type="submit" class="btn btn-outline-success">Search</button>
                    </form>
                </div>
            </nav>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card bg-body-secondary position-relative border-primary">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h2 class="card-title mb-0"><b>User Data</b></h2>
                            <div class="d-flex">
                                <div class="me-3">
                                    <a href="addAdmin.php" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-placement="right" data-bs-content="Add Admin Account"><button
                                            class="btn btn-outline-primary">
                                            <svg class="bi" width="30" height="30">
                                                <use xlink:href="#add" />
                                            </svg>
                                        </button></a>
                                </div>
                                <div>
                                    <a href="userData.php" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-placement="right" data-bs-content="Refresh"><button
                                            class="btn btn-outline-primary">
                                            <svg class="bi" width="32" height="32">
                                                <use xlink:href="#refresh" />
                                            </svg>
                                        </button></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                                include 'config.php';

                                $search = isset($_GET['search']) ? $_GET['search'] : '';
                                $searchCondition = "WHERE firstname LIKE :search OR lastname LIKE :search";

                                $stmtTotal = $pdo->prepare("
                                    SELECT SUM(total_count) AS total_records
                                    FROM (
                                        SELECT COUNT(*) AS total_count FROM admins $searchCondition
                                        UNION ALL
                                        SELECT COUNT(*) AS total_count FROM users $searchCondition
                                    ) AS combined
                                ");

                                $stmtTotal->execute(['search' => "%$search%"]);
                                $totalRecords = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total_records'];

                                $limit = 5; 
                                $totalPages = ceil($totalRecords / $limit);
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                $offset = ($page - 1) * $limit;

                                $stmt = $pdo->prepare("
                                    (SELECT * FROM admins $searchCondition)
                                    UNION ALL
                                    (SELECT * FROM users $searchCondition)
                                    LIMIT :limit OFFSET :offset
                                ");

                                $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                                $stmt->execute();
                                $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class='text-center'>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Facebook Account</th>
                                        <th>X Account</th>
                                        <th>User Type</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    
                                if($userData){
                                    foreach ($userData as $row) {
                                        if($row['userType'] == 1){
                                            $user = "Admin";
                                        }else if($row['userType'] == 2){
                                            $user = "Employee";
                                        }else{
                                            $user = "User not defined";
                                        }
                                        echo "<tr class='text-center'>";
                                        echo "<td>" . $row['firstname'] . ' ' . $row['lastname'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['phoneNumber'] . "</td>";
                                        echo "<td>" . $row['address'] . "</td>";
                                        echo "<td>" . $row['fbAcc'] . "</td>";
                                        echo "<td>" . $row['xAcc'] . "</td>";
                                        echo "<td>" . $user . "</td>";

                                        echo "<td>";
                                        if ($row['status'] == 'active' && $row['adminID'] != 1) {
                                            echo "<button class='btn btn-danger deactivate-btn' data-admin-id='{$row['adminID']}' data-user-type='{$row['userType']}'>Deactivate</button>";
                                        } elseif ($row['status'] == 'inactive') {
                                            echo "<button class='btn btn-success activate-btn' data-admin-id='{$row['adminID']}' data-user-type='{$row['userType']}'>Activate</button>";
                                        } else {
                                            echo "<button class='btn btn-secondary' disabled>Not Allowed</button>";
                                        }
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr class='text-center'><td colspan='12'><b>No User found</b></td></tr>";
                                } 
                                ?>

                                </tbody>
                            </table>

                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php
                                     
                                        echo "<li class='page-item " . ($page == 1 ? 'disabled' : '') . "'>";
                                        echo "<a class='page-link' href='?page=" . ($page - 1) . "' aria-label='Previous'
                                                data-bs-toggle='popover' data-bs-trigger='hover'
                                                data-bs-placement='bottom' data-bs-content='Previous Page'>";
                                        echo "<span aria-hidden='true'>&laquo;</span>";
                                        echo "</a>";
                                        echo "</li>";

                                        for ($i = 1; $i <= $totalPages; $i++) {
                                            echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>";
                                            echo "<a class='page-link' href='?page=$i'>$i</a>";
                                            echo "</li>";
                                        }

                                        echo "<li class='page-item " . ($page == $totalPages || empty($userData) ? 'disabled' : '') . "'>";
                                        echo "<a class='page-link' href='?page=" . ($page + 1) . "' aria-label='Next'
                                                data-bs-toggle='popover' data-bs-trigger='hover'
                                                data-bs-placement='bottom' data-bs-content='Next Page'>";
                                        echo "<span aria-hidden='true'>&raquo;</span>";
                                        echo "</a>";
                                        echo "</li>";
                                    ?>
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/successMessage.js"></script>
    <script src="assets/js/popper.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.deactivate-btn').click(function() {
            var adminID = $(this).data('admin-id');
            var userType = $(this).data('user-type');

            $.ajax({
                type: 'POST',
                url: 'action/userDeactivate.php',
                data: {
                    adminID: adminID,
                    userType: userType
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error communicating with the server');
                }
            });
        });
    });

    $(document).ready(function() {
        $('.activate-btn').click(function() {
            var adminID = $(this).data('admin-id');
            var userType = $(this).data('user-type');

            $.ajax({
                type: 'POST',
                url: 'action/userActivate.php',
                data: {
                    adminID: adminID,
                    userType: userType
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error communicating with the server');
                }
            });
        });
    });

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>

</body>

</html>