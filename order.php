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
        <symbol id="setting" viewBox="0 0 16 16">
            <path
                d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z" />
            <path
                d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z" />
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
        <symbol id="eye" viewBox="0 0 16 16">
            <path
                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
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
                            <li class="breadcrumb-item active" aria-current="page">Orders</li>
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
                <div class="col">
                    <div class="card position-relative mx-auto bg-body-secondary border-primary">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h2 class="card-title mb-0"><b>Orders</b></h2>
                            <div class="d-flex">
                                <div class="me-3">
                                    <a href="test.php" class="btn btn-outline-primary" data-bs-toggle="popover"
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
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th>Customer Contact</th>
                                        <th>Total Ordered Items</th>
                                        <th>Payment Status</th>
                                        <th>Option</th>
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
                    $searchCondition = "WHERE customerName LIKE :search";

                    // Calculate total records
                    $stmtTotal = $pdo->prepare("SELECT COUNT(*) as total FROM orders $searchCondition");
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
                    $stmt = $pdo->prepare("SELECT * FROM orders $searchCondition LIMIT $limit OFFSET $offset");
                    $stmt->execute(['search' => "%$search%"]);
                    $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if($order){
                    foreach ($order as $order) {

                        $orderID = $order['orderID'];
                        $space = "        ";
                        if ($order['paymentStatus'] === "fullPayment"){
                            $status = "Full Payment";
                            $statusClass = "btn-success";
                        }else if ($order['paymentStatus'] === "advancePayment"){
                            $status = "Advance Payment";
                            $statusClass = "btn-primary";
                        }else if ($order['paymentStatus'] === "partialPayment"){
                            $status = "Partial Payment";
                            $statusClass = "btn-warning";
                        }else if ($order['paymentStatus'] === "noPayment"){
                            $status = "No Payment";
                            $statusClass = "btn-danger";
                        }

                        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS orderCount FROM orderedItems WHERE orderID = $orderID");
                        $stmtTotal->execute();
                        $totalOrderedItems = $stmtTotal->fetch(PDO::FETCH_ASSOC)['orderCount'];


                        echo "<tr class='text-center'>";
                        echo "<td>{$order['orderDate']}</td>";
                        echo "<td>{$order['customerName']}</td>";
                        echo "<td>{$order['customerNumber']}</td>";
                        echo "<td>$totalOrderedItems</td>";
                        echo "<td><a class='btn rounded-pill opacity-75 px-1 py-0 $statusClass' style='font-size: 12px;'>{$status}</a></td>";
                        echo "<td>
                                <div class='dropdown' style='min-width: 200px;'>
                                    <a class='d-flex align-items-center justify-content-center dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'
                                        style='text-decoration: none; font-weight: bold; color: #fff;' href='#'>
                                        <svg class='bi pe-none' width='24' height='24'>
                                            <use xlink:href='#setting' />
                                        </svg>&nbsp; Action
                                    </a>
                                    <ul class='dropdown-menu px-2 border-info'> 
                                        <li><button type='button' class='btn btn-outline-info w-100 mb-1' onclick='openViewOrderModal({$order['orderID']})'>
                                        <svg class='bi pe-none' width='24' height='24'>
                                            <use xlink:href='#eye' />
                                        </svg>&nbsp; 
                                        View Details
                                        </button></li>
                                        <li><button type='button' class='btn btn-outline-info w-100 mb-1' onclick='openEditModal({$order['orderID']})'>
                                        <svg class='bi pe-none' width='24' height='24'>
                                            <use xlink:href='#edit' />
                                        </svg>&nbsp;
                                        Edit Payment
                                        </button></li>
                                        <li><a class='btn btn-outline-info w-100' onclick='return confirmDelete();' href='action/deleteOrder.php?id={$order['orderID']}'>
                                        <svg class='bi pe-none' width='24' height='24'>
                                            <use xlink:href='#delete' />
                                        </svg>&nbsp;
                                        Delete Orders
                                        </a></li>
                                    </ul>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr class='text-center'><td colspan='8'><b>No Order Found</b></td></tr>";
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
                                echo "<li class='page-item " . ($page == $totalPages || empty($order) ? 'disabled' : '') . "'>";
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

            <div class="modal fade" id="editOrder-form" tabindex="-1" aria-labelledby="payment"
                data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top modal-dialog-scrollable modal-lg">
                    <div class="modal-content bg-body-secondary">
                        <div class="modal-body">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="">
                                        <h5 class="card-title mb-3"><b>Edit Payment Order</b></h5>
                                    </div>
                                    <form action="action/editOrder.php" method="post">
                                        <input type="hidden" id="orderID" name="orderID">
                                        <input type="hidden" id="currentPaid" name="currentPaid">

                                        <div class="form-floating mb-3">
                                            <input type="text" name="dueAmount" id="dueAmount" class="form-control"
                                                disabled="true">
                                            <label for="floatingInput">Due Amount</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="paidAmount" id="paidAmount" class="form-control"
                                                autocomplete="off" onkeyup="paidAmount()">
                                            <label for="floatingInput">Paid Amount</label>
                                        </div>

                                        <input type="hidden" id="due" name="due" class="form-control">
                                        <input type="hidden" id="dueValue" name="dueValue" class="form-control">

                                        <div class="form-floating mb-3">
                                            <input type="text" name="change" id="change" class="form-control" value=""
                                                disabled="true">
                                            <label for="floatingInput">Change</label>
                                        </div>

                                        <input type="hidden" id="changeValue" name="changeValue" class="form-control">
                                        <input type="hidden" id="paidTotal" name="paidTotal" class="form-control">

                                        <div class="form-floating mb-3">
                                            <select name="payOption" id="payOption" class="form-select">
                                                <option value="">~~SELECT~~</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="cashIn">Cash In</option>
                                                <option value="creditCard">Credit Card</option>
                                                <option value="cashOnDelivery">Cash On Delivery</option>
                                                <option value="eWallet">E-Wallet</option>
                                            </select>
                                            <label for="floatingInput">Payment Status</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select name="payStatus" id="payStatus" class="form-select">
                                                <option>~~SELECT~~</option>
                                                <option value="fullPayment">Full Payment</option>
                                                <option value="advancePayment">Advance Payment</option>
                                                <option value="partialPayment">Partial Payment</option>
                                                <option value="noPayment">No Payment</option>
                                            </select>
                                            <label for="floatingInput">Payment Option</label>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Edit Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#viewOrder-form">
            </button>

            <div class="modal fade" id="viewOrder-form">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content bg-body-secondary">
                        <div class="modal-body">
                            <div class="mx-auto text-center">
                                <img src="assets/imgs/logo.png" alt="company logo">
                            </div>
                            <div class="row mt-3">
                                <div class="col text-start">
                                    <br>
                                    <p>Address:</p>
                                    <br>
                                    <p>Mobile No:</p>
                                    <p>Email:</p>
                                </div>
                                <div class="col text-end">
                                    <p>Ceboza, Matanao,<br> Davao del Sur, <br>8003</p>
                                    <p>09518184733</p>
                                    <a href="mailto:email@yahoo.com">email@yahoo.com</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-1 text-end">
                                    <p>TO:</p>
                                </div>
                                <div class="col-8 text-start">
                                    <div class="d-flex">
                                        <p>Customer Name: </p>
                                        <span class="ms-2">Raymond Chavez</span>
                                    </div>
                                    <div class="d-flex">
                                        <p>Customer Number: </p>
                                        <span class="ms-2">09518184733</span>
                                    </div>
                                </div>
                                <div class="col text-start">
                                    <div class="d-flex">
                                        <p>Order Code: </p>
                                        <span>1001</span>
                                    </div>
                                    <div class="d-flex">
                                        <p>Date: </p>
                                        <span>02/12/2023</span>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="card-title text-center mb-3"><b>Order List</b></h5>
                                <table class="mb-3">
                                    <thead>
                                        <tr>
                                            <th>Items</th>
                                            <th>Price</th>
                                            <th>quantity</th>
                                            <th>total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Product 1</td>
                                            <td>40 php</td>
                                            <td>10</td>
                                            <td>400</td>
                                        </tr>
                                        <tr>
                                            <td>Product 2</td>
                                            <td>40 php</td>
                                            <td>10</td>
                                            <td>400</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex">
                                            <p>Payment Option: </p>
                                            <span>Cash In</span>
                                        </div>
                                        <div class="d-flex">
                                            <p>Payment Status: </p>
                                            <span>Full Payment</span>
                                        </div>
                                        <div class="d-flex">
                                            <p>Shipping Option: </p>
                                            <span>Shipping</span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <!-- Right-aligned content -->
                                        <div class="row justify-content-end">
                                            <div class="col-5 text-end">
                                                <p>Total Amount: </p>
                                            </div>
                                            <div class="col-2">
                                                <span>800</span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-5 text-end">
                                                <p>Discount: </p>
                                            </div>
                                            <div class="col-2">
                                                <span>20</span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-5 text-end">
                                                <p>Grand Total: </p>
                                            </div>
                                            <div class="col-2">
                                                <span>780</span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-5 text-end">
                                                <p>Paid Amount: </p>
                                            </div>
                                            <div class="col-2">
                                                <span>1000</span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-5 text-end">
                                                <p>Due: </p>
                                            </div>
                                            <div class="col-2">
                                                <span>0</span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-5 text-end">
                                                <p>Change: </p>
                                            </div>
                                            <div class="col-2">
                                                <span>220</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mx-auto text-center">
                                        <img src="assets/imgs/Slogo.png" alt="company logo" style="width: 50px; height: 50px;">
                                        <h5 class="text-info"><b>RDCJ IMS</b></h5>
                                    </div>
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

    <script>
    function openEditModal(orderID) {
        $.ajax({
            url: 'action/fetchOrder.php',
            type: 'GET',
            data: {
                orderID: orderID
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById('orderID').value = data.orderID;
                document.getElementById('dueAmount').value = data.dueAmount;
                document.getElementById('currentPaid').value = data.paidAmount;
                document.getElementById('payOption').value = data.paymentOption;
                document.getElementById('payStatus').value = data.paymentStatus;

                $('#editOrder-form').modal('show');
            },
            error: function() {
                console.log('Error fetching order data.');
            }
        });
    }

    function openViewOrderModal(orderID) {
        $.ajax({
            url: 'action/fetchOrderData.php',
            type: 'GET',
            data: {
                orderID: orderID
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById('orderID').value = data.orderID;
                document.getElementById('dueAmount').value = data.dueAmount;
                document.getElementById('currentPaid').value = data.paidAmount;
                document.getElementById('payOption').value = data.paymentOption;
                document.getElementById('payStatus').value = data.paymentStatus;
                document.getElementById('payStatus').value = data.paymentStatus;
                document.getElementById('payStatus').value = data.paymentStatus;
                document.getElementById('payStatus').value = data.paymentStatus;
                document.getElementById('payStatus').value = data.paymentStatus;
                document.getElementById('payStatus').value = data.paymentStatus;
                document.getElementById('payStatus').value = data.paymentStatus;

                $('#viewOrder-form').modal('show');
            },
            error: function() {
                console.log('Error fetching order data.');
            }
        });
    }

    $(document).ready(function() {
        $("#paidAmount").on('keyup', paidAmount);

        function paidAmount() {
            var dueTotal = parseFloat($("#dueAmount").val());

            if (!isNaN(dueTotal)) {
                var paidAmount = parseFloat($("#paidAmount").val());
                var currentPaid = parseFloat($("#currentPaid")
                    .val()); // Assuming you have a field with id "currentPaid"
                var paidTotal = currentPaid + paidAmount;

                var dueAmount = dueTotal - paidAmount;
                dueAmount = dueAmount.toFixed(2);
                var dueAmountP = Math.max(0, dueAmount);
                var changeAmount = dueAmount < 0 ? Math.abs(dueAmount) : 0;

                $("#due").val(dueAmountP);
                $("#dueValue").val(dueAmountP);
                $("#change").val(changeAmount);
                $("#changeValue").val(changeAmount);
                $("#paidTotal").val(paidTotal);
            } // /if
        } // /paidAmount function
    });


    function confirmDelete() {
        return confirm("Are you sure you want to delete this order?");
    }
    // Initialize Bootstrap tooltips
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>

</body>

</html>