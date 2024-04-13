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

if (isset($_SESSION['brand_err'])) {
    $brand_err = $_SESSION['brand_err'];
    unset($_SESSION['brand_err']); // Clear the session variable
} else {
    $brand_err = ""; 
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
    <link href="styles/order.css" rel="stylesheet">
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
                            <li class="breadcrumb-item active" aria-current="page">Brands</li>
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
                <div class="col-md-8">
                    <div class="card bg-body-secondary position-relative border-primary">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h2 class="card-title mb-0"><b>Brand</b></h2>
                            <div class="d-flex">
                                <div class="me-3">
                                    <a data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="right"
                                        data-bs-content="Add Brand"><button type="button"
                                            class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#addBrand-form">
                                            <svg class="bi" width="30" height="30">
                                                <use xlink:href="#add" />
                                            </svg>
                                        </button></a>
                                </div>
                                <div>
                                    <a href="brand.php" class="btn btn-outline-primary" data-bs-toggle="popover"
                                        data-bs-trigger="hover" data-bs-placement="right" data-bs-content="Refresh">
                                        <svg class="bi" width="32" height="32" fill="currentColor">
                                            <use xlink:href="#refresh" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class='text-center'>
                                        <th>Name</th>
                                        <th>Stocks</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                    include 'config.php';

                    // Pagination
                    $limit = 5; // Number of records per page
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    

                    // Search
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $searchCondition = "WHERE name LIKE :search OR status COLLATE utf8mb4_general_ci LIKE :search";

                    // Calculate total records
                    $stmtTotal = $pdo->prepare("SELECT COUNT(*) as total FROM brands $searchCondition");
                    $stmtTotal->execute(['search' => "%$search%"]);
                    $totalRecords = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

                    // Calculate total pages
                    $limit = 5; // Number of records per page
                    $totalPages = ceil($totalRecords / $limit);

                    // Current page
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;

                    // Calculate offset
                    $offset = ($page - 1) * $limit;

                    // Fetch data with pagination
                    $stmt = $pdo->prepare("SELECT * FROM brands $searchCondition LIMIT $limit OFFSET $offset");
                    $stmt->execute(['search' => "%$search%"]);
                    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($brands) {
                        foreach ($brands as $brand) {

                            if ($brand['stocks'] <= 0) {
                                // If stocks are zero or less, set status to 'unavailable' and button class to 'btn-danger'
                                $brand['status'] = "unavailable";
                                $statusClass = 'btn-danger';
                            } else {
                                // If stocks are greater than zero, use regular logic to set $statusClass
                                $statusClass = ($brand['status'] == 'available') ? 'btn-success' : 'btn-danger';
                            }

                            echo "<tr class='text-center'>";
                            echo "<td><a href='product.php?findProduct={$brand['name']}' style='text-decoration: none; font-weight: bold; color: #fff;'>{$brand['name']}</a></td>";
                            echo "<td>{$brand['stocks']}</td>";
                            echo "<td class='text-center'><a class='btn rounded-pill opacity-75 px-1 py-0 $statusClass' style='font-size: 12px;'>{$brand['status']}</a></td>";
                            echo "<td class='text-center'>
                                    <button type='button' class='btn btn-warning btn-sm px-1 py-0' onclick='openEditModal({$brand['brandID']})'>
                                        <svg class='bi' width='25' height='25' fill='currentColor'>
                                            <use xlink:href='#edit' />
                                        </svg>
                                        Edit
                                    </button>
                                    <a href='action/deleteBrand.php?id={$brand['brandID']}' class='btn btn-danger btn-sm px-1 py-0' onclick='return confirmDelete();'>
                                        <svg class='bi' width='25' height='25' fill='currentColor'>
                                            <use xlink:href='#delete' />
                                        </svg>
                                        Delete
                                    </a>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr class='text-center'><td colspan='8'><b>No brand Found</b></td></tr>";
                    }    
                    ?>

                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php
                                // Previous Page
                                echo "<li class='page-item " . ($page == 1 ? 'disabled' : '') . "'>";
                                echo "<a class='page-link' href='?page=" . ($page - 1) . "' aria-label='Previous'
                                    data-bs-toggle='popover' data-bs-trigger='hover'
                                    data-bs-placement='bottom' data-bs-content='Previous Page'>";
                                echo "<span aria-hidden='true'>&laquo;</span>";
                                echo "</a>";
                                echo "</li>";

                                // Page numbers
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>";
                                    echo "<a class='page-link' href='?page=$i'>$i</a>";
                                    echo "</li>";
                                }

                                // Next Page 
                                echo "<li class='page-item " . ($page == $totalPages || empty($brands) ? 'disabled' : '') . "'>";
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


            <div class="modal fade" id="addBrand-form" tabindex="-1" aria-labelledby="addBrand"
                data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top modal-dialog-scrollable modal-lg">
                    <div class="modal-content bg-body-secondary">
                        <div class="modal-body">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="">
                                        <h5 class="card-title mb-3"><b>Add Brand</b></h5>
                                    </div>
                                    <form action="action/addBrand.php" method="post" id="addBrandForm">
                                        <div class="form-floating">
                                            <input type="text" name="brandName" id="brandName"
                                                class="form-control <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>">
                                            <label for="floatingInput">Brand Name</label>
                                            <span class="invalid-feedback"
                                                id="brandError"><?php echo $brand_err; ?></span>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="addBrand()">Add
                                                Brand</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>



            <div class="modal fade" id="editBrand-form" tabindex="-1" aria-labelledby="editBrand"
                data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top modal-dialog-scrollable modal-lg">
                    <div class="modal-content bg-body-secondary">
                        <div class="modal-body">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="">
                                        <h5 class="card-title mb-3"><b>Edit Brand</b></h5>
                                    </div>
                                    <form action="action/editBrand.php" method="post" id="addEditForm">
                                        <input type="hidden" id="brandID" name="brandID" value="">

                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" id="name" class="form-control" value=""
                                                required>
                                            <label for="floatingInput">Brand Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select name="status" id="status" class="form-select">
                                                <option>~~SELECT~~</option>
                                                <option value="available">
                                                    Available</option>
                                                <option value="unavailable">
                                                    Unavailable</option>
                                            </select>
                                            <label for="status">Status</label>
                                            <span class="invalid-feedback"><?php echo $status_err; ?></span>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Edit Brand</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/successMessage.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>


    <script>
    function openEditModal(brandID) {
        $.ajax({
            url: 'action/fetchBrand.php',
            type: 'GET',
            data: {
                brandID: brandID
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById('brandID').value = data.brandID;
                document.getElementById('name').value = data.name;
                document.getElementById('status').value = data.status;

                $('#editBrand-form').modal('show');
            },
            error: function() {
                console.log('Error fetching brand data.');
            }
        });
    }

    function addBrand() {
        if (validateForm()) {
            document.getElementById("addBrandForm").submit();
        }
    }

    function validateForm() {
        var brandName = document.getElementById("brandName").value;
        var brandErrorSpan = document.getElementById("brandError");

        brandErrorSpan.innerHTML = "";
        document.getElementById("brandName").classList.remove("is-invalid");

        if (brandName.trim() === "") {
            brandErrorSpan.innerHTML = "Please enter a brand name.";
            document.getElementById("brandName").classList.add("is-invalid");
            return false;
        }
        document.getElementById("brandName").classList.remove("is-invalid");
        return true;
    }

    function confirmDelete() {
        return confirm("Are you sure you want to delete this reservation?");
    }
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>

</body>

</html>