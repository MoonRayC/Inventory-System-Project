<?php
include 'config.php';

session_start();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('location: login.php');
    exit();
}

$userType = $_SESSION['user_type'];
$userTypeID = $_SESSION['userid'];

if ($userType == 1) {
    $employee = 'admin_' . strval($userTypeID);
} elseif ($userType == 2) {
    $employee = 'user_' . strval($userTypeID);
}

function isNullOrEmptyArray($array) {
    return empty($array) || count(array_filter($array, 'strlen')) === 0;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $employee = trim($_POST['employee']);

    if (empty(trim($_POST['orderDate']))) {
        $orderDate_err = 'Please Enter a Date.';
    } else {
        $orderDate = trim($_POST['orderDate']);
    }

    if (empty(trim($_POST['customerName']))) {
        $customerName_err = 'Please Enter the Name of the Customer.';
    } else {
        $customerName = trim($_POST['customerName']);
    }

    if (empty(trim($_POST['customerNumber']))) {
        $customerNumber_err = 'Please Enter the Number of the Customer.';
    } else {
        $customerNumber = trim($_POST['customerNumber']);
    }

    foreach ($_POST['productName'] as $x => $value) {
        $productName[$x] = trim($value);
    }

    foreach ($_POST['priceValue'] as $x => $value) {
        $price[$x] = trim($value);
    }

    foreach ($_POST['quantity'] as $x => $value) {
        $quantity[$x] = trim($value);
    }

    foreach ($_POST['totalValue'] as $x => $value) {
        $total[$x] = trim($value);
    }

    $subTotal = trim($_POST['subTotalValue']);

    if ($_POST['discount'] === '' || trim($_POST['discount']) === '') {
        $discount_err = 'Please Enter a Discount Value.';
    } else {
        $discount = trim($_POST['discount']);
    }    

    $grandTotal = trim($_POST['grandTotalValue']);

    if ($_POST['paid'] === '' || trim($_POST['paid']) === '') {
        $paid_err = 'Please Enter a Money Amount.';
    } else {
        $paid = trim($_POST['paid']); 
    }

    $due = trim($_POST['dueValue']);

    $change = trim($_POST['changeValue']);

    if (empty(trim($_POST['payOption']))) {
        $payOption_err = 'Please Enter a Payment Option.';
    } else {
        $payOption = trim($_POST['payOption']);

    } if (empty(trim($_POST['payStatus']))) {
        $payStatus_err = 'Please Enter a Payment Status.';
    } else {
        $payStatus = trim($_POST['payStatus']);

    } if (empty(trim($_POST['method']))) {
        $method_err = 'Please Enter a Shipping Method.';
    } else {
        $method = trim($_POST['method']);
    }

    if (empty($orderDate_err) && empty($customerName_err) && empty($customerNumber_err) && empty($discount_err) && empty($paid_err) && empty($payOption_err) && empty($method_err))
    {

        $addOrderSql = 'INSERT INTO orders (orderDate, customerName, customerNumber, totalAmount, discount, grandTotal, paidAmount, dueAmount, changeAmount, paymentOption, paymentStatus, shippingMethod, employeeID) 
                    VALUES (:orderDate, :customerName, :customerNumber, :subTotal, :discount, :grandTotal, :paid, :due, :change, :payType, :payStatus, :method, :employee)';
        $addOrderStmt = $pdo->prepare($addOrderSql);
        $addOrderStmt->bindParam(':orderDate', $orderDate, PDO::PARAM_STR);
        $addOrderStmt->bindParam(':customerName', $customerName, PDO::PARAM_STR);
        $addOrderStmt->bindParam(':customerNumber', $customerNumber, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':subTotal', $subTotal, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':discount', $discount, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':grandTotal', $grandTotal, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':paid', $paid, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':due', $due, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':change', $change, PDO::PARAM_INT);
        $addOrderStmt->bindParam(':payType', $payOption, PDO::PARAM_STR);
        $addOrderStmt->bindParam(':payStatus', $payStatus, PDO::PARAM_STR);
        $addOrderStmt->bindParam(':method', $method, PDO::PARAM_STR);
        $addOrderStmt->bindParam(':employee', $employee, PDO::PARAM_STR);

        $orderSuccess = $addOrderStmt->execute();

        if ($orderSuccess) {
            // Assuming $orderId contains the ID of the recently inserted order
            $orderId = $pdo->lastInsertId();
        
            foreach ($_POST['productName'] as $x => $productName) {
                $productID = $_POST['productName'][$x];
                $price = $_POST['priceValue'][$x];
                $quantity = $_POST['quantity'][$x];
                $total = $_POST['totalValue'][$x];

                $insertOrderedItemSql = 'INSERT INTO orderedItems (orderID, productID, price, quantity, total) VALUES (:orderID, :productID, :price, :quantity, :total)';
                $insertOrderedItemStmt = $pdo->prepare($insertOrderedItemSql);
                $insertOrderedItemStmt->bindParam(':orderID', $orderId, PDO::PARAM_INT);
                $insertOrderedItemStmt->bindParam(':productID', $productID, PDO::PARAM_INT);
                $insertOrderedItemStmt->bindParam(':price', $price, PDO::PARAM_INT);
                $insertOrderedItemStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $insertOrderedItemStmt->bindParam(':total', $total, PDO::PARAM_INT);
                $insertOrderedItemStmt->execute();

                $productStmt = $pdo->prepare("SELECT * FROM products WHERE productID = :productID");
                $productStmt->bindParam(":productID", $productID, PDO::PARAM_INT);
                $productStmt->execute();
                $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

                $totalProductStocks = $productData['stocks'] - $quantity;
                
                $productStatus = ($totalProductStocks >= 1) ? "available" : "unavailable";
        
                $updateProductsStocksSql = 'UPDATE products SET stocks = :totalProductStocks, status = :productStatus WHERE productID = :productName';
                $updateProductsStocksStmt = $pdo->prepare($updateProductsStocksSql);
                $updateProductsStocksStmt->bindParam(':totalProductStocks', $totalProductStocks, PDO::PARAM_INT);
                $updateProductsStocksStmt->bindParam(':productStatus', $productStatus, PDO::PARAM_STR);
                $updateProductsStocksStmt->bindParam(':productName', $productName, PDO::PARAM_INT);
                $updateProductsStocksStmt->execute();

                $selectProductSql = 'SELECT * FROM products WHERE productID = :productId';
                $selectProductStmt = $pdo->prepare($selectProductSql);
                $selectProductStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
                $selectProductStmt->execute();
                $updatedProductData = $selectProductStmt->fetch(PDO::FETCH_ASSOC);

                if ($updatedProductData) {

                    $categoryId = $updatedProductData['categoryID'];
                    $brandId = $updatedProductData['brandID'];
                    
                    $brandStmt = $pdo->prepare("SELECT * FROM brands WHERE brandID = :brandID");
                    $brandStmt->bindParam(":productID", $brandID, PDO::PARAM_INT);
                    $brandStmt->execute();
                    $brandData = $brandStmt->fetch(PDO::FETCH_ASSOC);

                    $categoryStmt = $pdo->prepare("SELECT * FROM categories WHERE categoryID = :categoryID");
                    $categoryStmt->bindParam(":productID", $categoryID, PDO::PARAM_INT);
                    $categoryStmt->execute();
                    $categoryData = $categoryStmt->fetch(PDO::FETCH_ASSOC);

                    $totalBrandStocks = $brandData['stocks'] - $quantity;
                    $totalCategoryStocks = $categoryData['stocks'] - $quantity;

                    $brandStatus = ($totalBrandStocks >= 1) ? "available" : "unavailable";
                    $categoryStatus = ($totalCategoryStocks >= 1) ? "available" : "unavailable";

                    $updateBrandStocksSql = 'UPDATE brands SET stocks = :totalBrandStocks, status = :brandStatus WHERE brandID = :brandID';
                    $updateBrandStocksStmt = $pdo->prepare($updateBrandStocksSql);
                    $updateBrandStocksStmt->bindParam(':totalBrandStocks', $totalBrandStocks, PDO::PARAM_INT);
                    $updateBrandStocksStmt->bindParam(':brandStatus', $brandStatus, PDO::PARAM_STR);
                    $updateBrandStocksStmt->bindParam(':brandID', $brandID, PDO::PARAM_INT);
                    $updateBrandStocksStmt->execute();
                    
                    $updateCategoriesStocksSql = 'UPDATE categories SET stocks = :totalCategoryStocks, status = :categoryStatus WHERE categoryID = :categoryID';
                    $updateCategoriesStocksStmt = $pdo->prepare($updateCategoriesStocksSql);
                    $updateCategoriesStocksStmt->bindParam(':totalCategoryStocks', $totalCategoryStocks, PDO::PARAM_INT);
                    $updateCategoriesStocksStmt->bindParam(':categoryStatus', $categoryStatus, PDO::PARAM_STR);
                    $updateCategoriesStocksStmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
                    $updateCategoriesStocksStmt->execute(); 
                }
            }
            header("location: index.php?success=6");
            exit();
        } else {
                header("location: index.php?success=7");
                exit();
        }
    }
}
?>


<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="assets/js/color-modes.js"></script>

    <script>
    var ProductData = <?php echo json_encode($ProductData); ?>;
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RDCJ IMS</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/sideIcon.css">
    <link href="styles/order.css" rel="stylesheet">
</head>

<body>

    <div id="successMessage" class="alert alert-success alert-dismissible" role="alert"
        style='position: fixed; top: 20px; right: 20px; z-index: 9999; width:25rem;' hidden>
    </div>

    <div id="failedMessage" class="alert alert-danger alert-dismissible" role="alert"
        style='position: fixed; top: 20px; right: 20px; z-index: 9999; width:25rem;' hidden>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="add" viewBox="0 0 16 16">
            <path
                d="M8 0c-.176 0-.35.006-.523.017l.064.998a7.117 7.117 0 0 1 .918 0l.064-.998A8.113 8.113 0 0 0 8 0M6.44.152c-.346.069-.684.16-1.012.27l.321.948c.287-.098.582-.177.884-.237L6.44.153zm4.132.271a7.946 7.946 0 0 0-1.011-.27l-.194.98c.302.06.597.14.884.237l.321-.947zm1.873.925a8 8 0 0 0-.906-.524l-.443.896c.275.136.54.29.793.459l.556-.831zM4.46.824c-.314.155-.616.33-.905.524l.556.83a7.07 7.07 0 0 1 .793-.458zM2.725 1.985c-.262.23-.51.478-.74.74l.752.66c.202-.23.418-.446.648-.648l-.66-.752zm11.29.74a8.058 8.058 0 0 0-.74-.74l-.66.752c.23.202.447.418.648.648l.752-.66m1.161 1.735a7.98 7.98 0 0 0-.524-.905l-.83.556c.169.253.322.518.458.793l.896-.443zM1.348 3.555c-.194.289-.37.591-.524.906l.896.443c.136-.275.29-.54.459-.793l-.831-.556zM.423 5.428a7.945 7.945 0 0 0-.27 1.011l.98.194c.06-.302.14-.597.237-.884l-.947-.321zM15.848 6.44a7.943 7.943 0 0 0-.27-1.012l-.948.321c.098.287.177.582.237.884l.98-.194zM.017 7.477a8.113 8.113 0 0 0 0 1.046l.998-.064a7.117 7.117 0 0 1 0-.918l-.998-.064zM16 8a8.1 8.1 0 0 0-.017-.523l-.998.064a7.11 7.11 0 0 1 0 .918l.998.064A8.1 8.1 0 0 0 16 8M.152 9.56c.069.346.16.684.27 1.012l.948-.321a6.944 6.944 0 0 1-.237-.884l-.98.194zm15.425 1.012c.112-.328.202-.666.27-1.011l-.98-.194c-.06.302-.14.597-.237.884l.947.321zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a6.999 6.999 0 0 1-.458-.793l-.896.443zm13.828.905c.194-.289.37-.591.524-.906l-.896-.443c-.136.275-.29.54-.459.793l.831.556zm-12.667.83c.23.262.478.51.74.74l.66-.752a7.047 7.047 0 0 1-.648-.648l-.752.66zm11.29.74c.262-.23.51-.478.74-.74l-.752-.66c-.201.23-.418.447-.648.648l.66.752m-1.735 1.161c.314-.155.616-.33.905-.524l-.556-.83a7.07 7.07 0 0 1-.793.458l.443.896zm-7.985-.524c.289.194.591.37.906.524l.443-.896a6.998 6.998 0 0 1-.793-.459l-.556.831zm1.873.925c.328.112.666.202 1.011.27l.194-.98a6.953 6.953 0 0 1-.884-.237l-.321.947zm4.132.271a7.944 7.944 0 0 0 1.012-.27l-.321-.948a6.954 6.954 0 0 1-.884.237l.194.98zm-2.083.135a8.1 8.1 0 0 0 1.046 0l-.064-.998a7.11 7.11 0 0 1-.918 0l-.064.998zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
        </symbol>
        <symbol id="delete" viewBox="0 0 16 16">
            <path
                d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
        </symbol>
        <symbol id="payment" viewBox="0 0 16 16">
            <path
                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
            <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
        </symbol>
    </svg>
    <?php include "includes/bgtheme.php" ?>

    <main class="d-flex flex-nowrap">
        <?php include "includes/sidebar.php" ?>


        <div class="b-example-divider b-example-vr"></div>
        <div class="container">

            <div class="card mx-auto bg-body-secondary border-primary my-5">
                <h1 class="card-title text-center"><b>Welcome To RDCJ Inventory Management System</b></h1>
            </div>

            <div class="card mx-auto bg-body-secondary border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center">Order Product</h5>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <input type="hidden" id="employee" name="employee" value="<?php echo $employee; ?>">

                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text">Order Date:</span>
                                <input type="date"
                                    class="form-control <?php echo (!empty($orderDate_err)) ? 'is-invalid' : ''; ?>"
                                    id="orderDate" name="orderDate">
                                <span class="invalid-feedback text-center"><?php echo $orderDate_err ?? ''; ?></span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text">Customer Name:</span>
                                <input type="text"
                                    class="form-control <?php echo (!empty($customerName_err)) ? 'is-invalid' : ''; ?>"
                                    id="customerName" name="customerName">
                                <span class="invalid-feedback text-center"><?php echo $customerName_err; ?></span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon3">Customer Number:</span>
                                <input type="number"
                                    class="form-control <?php echo (!empty($customerNumber_err)) ? 'is-invalid' : ''; ?>"
                                    id="customerNumber" name="customerNumber">
                                <span class="invalid-feedback text-center"><?php echo $customerNumber_err; ?></span>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="row justify-content-center" id="productTable">
                                <div class="col">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="width:40%;">Product</th>
                                                <th style="width:10%;">Price</th>
                                                <th style="width:20%;">Available Stocks</th>
                                                <th style="width:10%;">Quantity</th>
                                                <th style="width:20%;">Total</th>
                                                <th style="width:10%;"></th>
                                                <th style="width:10%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider text-center">
                                            <?php 
                                            $arrayNumber = 0;
                                            for($x = 1; $x < 4; $x++) { ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="productName[]"
                                                            id="productName<?php echo $x; ?>"
                                                            onchange="getProductData(<?php echo $x; ?>)">
                                                            <option value="">~~SELECT~~</option>
                                                            <?php
                                                            $productSql = "SELECT * FROM products WHERE status = 'available' AND stocks != 0";
                                                            $stmt = $pdo->query($productSql);
                                                            
                                                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                            foreach ($products as $row) {
                                                                echo "<option value='" . $row['productID'] . "' id='changeProduct" . $row['productID'] . "' $selectedProductID>" . $row['name'] . "</option>";
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>

                                                </td>
                                                <td>
                                                    <input type="text" name="price[]" id="price<?php echo $x; ?>"
                                                        autocomplete="off" disabled="true" class="form-control" />
                                                    <input type="hidden" name="priceValue[]"
                                                        id="priceValue<?php echo $x; ?>" autocomplete="off"
                                                        class="form-control" />
                                                </td>
                                                <td style="padding-left:20px;">
                                                    <div class="form-group">
                                                        <p id="stocks<?php echo $x; ?>"></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" name="quantity[]"
                                                            id="quantity<?php echo $x; ?>"
                                                            onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off"
                                                            class="form-control" min="1" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="total[]" id="total<?php echo $x; ?>"
                                                        autocomplete="off" class="form-control" disabled="true" />
                                                    <input type="hidden" name="totalValue[]"
                                                        id="totalValue<?php echo $x; ?>" autocomplete="off"
                                                        class="form-control" />
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default" onclick="addRow()"
                                                        id="addRowBtn">
                                                        <svg width="20" height="20" fill="currentColor">
                                                            <use xlink:href="#add" />
                                                        </svg>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default removeProductRowBtn"
                                                        id="removeProductRowBtn"
                                                        onclick="removeProductRow(<?php echo $x; ?>)">
                                                        <svg width='20' height='20' fill='currentColor'>
                                                            <use xlink:href='#delete' />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                                $arrayNumber++;
                                                }
                                                ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-grid gap-2 col-3 mx-auto">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#payment-form">
                                    <svg class='bi' width='20' height='20' fill='currentColor'>
                                        <use xlink:href='#payment' />
                                    </svg> <b>Pay</b>
                                </button>
                            </div>

                            <div class="modal fade" id="payment-form" tabindex="-1" aria-labelledby="payment"
                                data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top modal-dialog-scrollable modal-lg">
                                    <div class="modal-content bg-body-secondary">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="payment">Payment</h1>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text" for="subTotal">Total
                                                                        Amount:</span>
                                                                    <input type="text" id="subTotal" name="subTotal"
                                                                        disabled="true" class="form-control">
                                                                    <input type="hidden" class="form-control"
                                                                        id="subTotalValue" name="subTotalValue" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"
                                                                        for="discount">Discount:</span>
                                                                    <input type="number" id="discount" name="discount"
                                                                        onkeyup="discountFunc()" autocomplete="off"
                                                                        class="form-control <?php echo (!empty($discount_err)) ? 'is-invalid' : ''; ?>">
                                                                    <span
                                                                        class="invalid-feedback text-center"><?php echo $discount_err; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"
                                                                        for="grandTotal">Grand Total:</span>
                                                                    <input type="text" class="form-control"
                                                                        id="grandTotal" name="grandTotal"
                                                                        id="grandTotal" name="grandTotal"
                                                                        disabled="true" />
                                                                    <input type="hidden" class="form-control"
                                                                        id="grandTotalValue" name="grandTotalValue">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text" for="paidAmount">Paid
                                                                        Amount:</span>
                                                                    <input type="text"
                                                                        class="form-control <?php echo (!empty($paid_err)) ? 'is-invalid' : ''; ?>"
                                                                        id="paid" name="paid" autocomplete="off"
                                                                        onkeyup="paidAmount()">
                                                                    <span
                                                                        class="invalid-feedback text-center"><?php echo $paid_err; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text" for="dueAmount">Due
                                                                        Amount:</span>
                                                                    <input type="text" class="form-control" id="due"
                                                                        name="due" disabled="true">
                                                                    <input type="hidden" class="form-control"
                                                                        id="dueValue" name="dueValue">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"
                                                                        for="changeMoney">Change:</span>
                                                                    <input type="text" class="form-control" id="change"
                                                                        name="change" disabled="true">
                                                                    <input type="hidden" class="form-control"
                                                                        name="changeValue" id="changeValue">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="payment mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon3">Payment
                                                                Option:</span>
                                                            <select name="payOption" id="payOption"
                                                                class="form-select <?php echo (!empty($payOption_err)) ? 'is-invalid' : ''; ?>">
                                                                <option value="">~~SELECT~~</option>
                                                                <option value="cheque">Cheque</option>
                                                                <option value="cashIn">Cash In</option>
                                                                <option value="creditCard">Credit Card</option>
                                                                <option value="cashOnDelivery">Cash On Delivery</option>
                                                                <option value="eWallet">E-Wallet</option>
                                                            </select>
                                                            <span
                                                                class="invalid-feedback text-center"><?php echo $payOption_err; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="payment mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon3">Payment
                                                                Status:</span>
                                                            <select name="payStatus" id="payStatus"
                                                                class="form-select <?php echo (!empty($payStatus_err)) ? 'is-invalid' : ''; ?>">
                                                                <option value="">~~SELECT~~</option>
                                                                <option value="fullPayment">Full Payment</option>
                                                                <option value="advancePayment">Advance Payment
                                                                </option>
                                                                <option value="partialPayment">Partial Payment</option>
                                                                <option value="noPayment">No Payment</option>
                                                            </select>
                                                            <span
                                                                class="invalid-feedback text-center"><?php echo $payStatus_err; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="payment mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon3">Shipping
                                                                Method:</span>
                                                            <select name="method" id="method"
                                                                class="form-select <?php echo (!empty($method_err)) ? 'is-invalid' : ''; ?>">
                                                                <option value="">~~SELECT~~</option>
                                                                <option value="shipping">Shipping</option>
                                                                <option value="selfPickUp">Self Pick Up</option>
                                                            </select>
                                                            <span
                                                                class="invalid-feedback text-center"><?php echo $method_err; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="assets/js/popper.min.js"></script>
    <script src="scripts/successMessage.js"></script>
    <script src="scripts/order.js"></script>
    <script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>
</body>

</html>