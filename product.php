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
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
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
                    <div class="card position-relative mx-auto bg-body-secondary border-primary">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h2 class="card-title mb-0"><b>Product</b></h2>
                            <div class="d-flex">
                                <div class="me-3">
                                    <a href="addProduct.php" data-bs-toggle="popover"
                                        data-bs-trigger="hover" data-bs-placement="right"
                                        data-bs-content="Add Category"><button class="btn btn-outline-primary">
                                            <svg class="bi" width="30" height="30">
                                                <use xlink:href="#add" />
                                            </svg>
                                        </button></a>
                                </div>
                                <div>
                                    <a href="product.php" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-placement="right" data-bs-content="Refresh"><button class="btn btn-outline-primary">
                                        <svg class="bi" width="32" height="32">
                                            <use xlink:href="#refresh" />
                                        </svg>
                                    </button></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class='text-center'>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stocks</th>
                                        <th>Supplier</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    // Retrieve the category name from the URL
                                    $findProduct = isset($_GET['findProduct']) ? $_GET['findProduct'] : '';

                                    // Set the search term to the category name if available
                                    $pickedProduct = $findProduct;
                                    // Pagination
                                    $limit = 5; // Number of records per page
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $offset = ($page - 1) * $limit;

                                    $searchbar = isset($_GET['search']) ? $_GET['search'] : '';
                                    if (!empty($pickedProduct)) {
                                        $search = $pickedProduct;
                                    } else {
                                        $search = $searchbar;
                                    }
                                    $searchCondition = "WHERE p.name LIKE :search OR p.status COLLATE utf8mb4_general_ci LIKE :search
                                                        OR c.name LIKE :search OR b.name LIKE :search OR p.supplier LIKE :search";

                                    // Calculate total records
                                    $stmtTotal = $pdo->prepare("SELECT COUNT(*) as total 
                                                                FROM products p
                                                                LEFT JOIN categories c ON p.categoryID = c.categoryID
                                                                LEFT JOIN brands b ON p.brandID = b.brandID
                                                                $searchCondition");
                                    $stmtTotal->execute(['search' => "%$search%"]);
                                    $totalRecords = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

                                    // Calculate total pages
                                    $totalPages = ceil($totalRecords / $limit);
 
                                    // Fetch data with pagination and search
                                    $stmt = $pdo->prepare("SELECT 
                                                            p.productID, 
                                                            c.name AS category, 
                                                            b.name AS brand, 
                                                            p.name, 
                                                            p.price, 
                                                            p.stocks, 
                                                            p.supplier, 
                                                            p.status 
                                                        FROM products p
                                                        LEFT JOIN categories c ON p.categoryID = c.categoryID
                                                        LEFT JOIN brands b ON p.brandID = b.brandID
                                                        $searchCondition
                                                        LIMIT $limit OFFSET $offset");
                                    $stmt->execute(['search' => "%$search%"]);
                                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if($products){
                                    foreach ($products as $product) {
                                    if ($product['stocks'] <= 0) {
                                        // If stocks are zero or less, set status to 'unavailable' and button class to 'btn-danger'
                                        $product['status'] = "unavailable";
                                        $statusClass = 'btn-danger';
                                    } else {
                                        // If stocks are greater than zero, use regular logic to set $statusClass
                                        $statusClass = ($product['status'] == 'available') ? 'btn-success' : 'btn-danger';
                                    }

                                    echo "<tr class='text-center'>";
                                    echo "<td>{$product['name']}</td>";
                                    echo "<td>{$product['brand']}</td>";
                                    echo "<td class='text-center'>{$product['category']}</td>";
                                    echo "<td class='text-center'>{$product['price']}</td>";
                                    echo "<td>{$product['stocks']}</td>";
                                    echo "<td>{$product['supplier']}</td>";
                                    echo "<td class='text-center'><a class='btn rounded-pill opacity-75 px-1 py-0 $statusClass' style='font-size: 12px;'>{$product['status']}</a></td>";
                                    echo "<td class='text-center'><a href='editProduct.php?id={$product['productID']}' class='btn btn-warning btn-sm px-1 py-0'>
                                                <svg class='bi' width='25' height='25' fill='currentColor'>
                                                    <use xlink:href='#edit' />
                                                </svg>
                                                Edit
                                            </a>
                                            <a href='action/deleteProduct.php?id={$product['productID']}' class='btn btn-danger btn-sm px-1 py-0' onclick='return confirmDelete();'>
                                                <svg class='bi' width='25' height='25' fill='currentColor'>
                                                    <use xlink:href='#delete' />
                                                </svg>
                                                Delete
                                            </a>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr class='text-center'><td colspan='8'><b>No Product Found</b></td></tr>";
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
                                    echo "<a class='page-link' href='?page=" . ($page - 1) . "&search=$search&findProduct=$pickedProduct' aria-label='Previous'
                                            data-bs-toggle='popover' data-bs-trigger='hover'
                                            data-bs-placement='bottom' data-bs-content='Previous Page'>";
                                    echo "<span aria-hidden='true'>&laquo;</span>";
                                    echo "</a>";
                                    echo "</li>"; 

                                    // Page numbers
                                    for ($i = 1; $i <= $totalPages; $i++) {
                                        echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>";
                                        echo "<a class='page-link' href='?page=$i&search=$search&findProduct=$pickedProduct'>$i</a>";
                                        echo "</li>";
                                    }

                                    // Next Page
                                    echo "<li class='page-item " . ($page == $totalPages || empty($products) ? 'disabled' : '') . "'>";
                                    echo "<a class='page-link' href='?page=" . ($page - 1) . "&search=$search&findProduct=$pickedProduct' aria-label='Next'
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
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/successMessage.js"></script>
    <script src="assets/js/popper.min.js"></script>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this reservation?");
    }
    // Initialize Bootstrap tooltips
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>

</body>

</html>