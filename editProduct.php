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

// Fetch category data from the database
$userType = $_SESSION['user_type'];
$productID = $_GET['id'];

$stmtBrands = $pdo->prepare("SELECT brandID, name FROM brands"); 
$stmtBrands->execute();
$brandData = $stmtBrands->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$stmtCategories = $pdo->prepare("SELECT categoryID, name FROM categories");
$stmtCategories->execute();
$categoryData = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT
    c.name AS category, 
    b.name AS brand,
    c.categoryID AS categoriesID,
    b.brandID AS brandsID,
    p.name, 
    p.price, 
    p.stocks, 
    p.supplier, 
    p.status 
    FROM products p
    LEFT JOIN categories c ON p.categoryID = c.categoryID
    LEFT JOIN brands b ON p.brandID = b.brandID
    WHERE productID = :productID");

$stmt->bindParam(":productID", $productID);
$stmt->execute();
$productData = $stmt->fetch(PDO::FETCH_ASSOC);

// Define variables and initialize with category data
$categoriesID = $productData['categoriesID'];
$brandsID = $productData['brandsID'];
$productName = $productData['name'];
$brand = $productData['brand'];
$category = $productData['category'];
$price = $productData['price'];
$stocks = $productData['stocks'];
$supplierName = $productData['supplier'];
$status = $productData['status'];

// Define variables for error messages
$productName = $brand = $category = $price = $stocks = $supplierName = $status = '';
$productName_err = $brand_err = $category_err  = $price_err = $stocks_err = $supplierName_err = $status_err = '';

// Check if the form is submitted for updating category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data and update the category 
    if (empty(trim($_POST['productName']))) {
        $name_err = 'Please Enter a Product Name.';
    } else {
        $productName = trim($_POST['productName']);
    }

    if (empty(trim($_POST['brand']))) {
        $brand_err = 'Please Enter Product Brand.';
    } else {
        $brand = trim($_POST['brand']);
    }

    if (empty(trim($_POST['category']))) {
        $category_err = 'Please Enter Product Category.';
    } else {
        $category = trim($_POST['category']);
    }

    if (empty(trim($_POST['price']))) {
        $price_err = 'Please Enter a price.'; 
    } else {
        $price = trim($_POST['price']);
    }

    if (empty(trim($_POST['stocks']))) {
        $stocks_err = 'Please Enter stock value.';
    } else {
        $stocks = trim($_POST['stocks']);
    }

    if (empty(trim($_POST['supplierName']))) {
        $supplierName_err = 'Please Enter Supplier Name.';
    } else {
        $supplierName = trim($_POST['supplierName']);
    }

    if (empty(trim($_POST['status']))) {
        $status_err = 'Please Enter Product Status.';
    } else {
        $status = trim($_POST['status']);
    }

    $stmt = $pdo->prepare("SELECT * FROM brands WHERE brandID = :brand");
    $stmt->bindParam(":brand", $brand, PDO::PARAM_INT);
    $stmt->execute();
    $brandData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch category data from the database based on categoryID
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE categoryID = :category");
    $stmt->bindParam(":category", $category, PDO::PARAM_INT);
    $stmt->execute();
    $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

    $updateProductSql = 'UPDATE products SET name = :productName, 
                                            brandID = :brand, 
                                            categoryID = :category, 
                                            price = :price, 
                                            stocks = :stocks, 
                                            supplier = :supplierName, 
                                            status = :status 
                                            WHERE productID = :productID';
    $updateProductStmt = $pdo->prepare($updateProductSql);
    $updateProductStmt->bindParam(':productName', $productName, PDO::PARAM_STR);
    $updateProductStmt->bindParam(':brand', $brand, PDO::PARAM_INT);
    $updateProductStmt->bindParam(':category', $category, PDO::PARAM_INT);
    $updateProductStmt->bindParam(':price', $price, PDO::PARAM_INT);
    $updateProductStmt->bindParam(':stocks', $stocks, PDO::PARAM_INT);
    $updateProductStmt->bindParam(':supplierName', $supplierName, PDO::PARAM_STR);
    $updateProductStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateProductStmt->bindParam(':productID', $productID, PDO::PARAM_STR);
    
    // Execute the update and check for success
    $updateSuccess = $updateProductStmt->execute();

    if ($updateSuccess) {

        $totalBrandStocks = (int)$brandData['stocks'] + (int)$stocks;
        $totalCategoryStocks = (int)$categoryData['stocks'] + (int)$stocks;
    
        // Determine status based on stocks
        $brandStatus = ($stocks >= 1) ? "available" : "unavailable";
        $categoryStatus = ($stocks >= 1) ? "available" : "unavailable";

        // Update brand stocks and status
        $updateBrandStocksSql = 'UPDATE brands SET stocks = :totalBrandStocks, status = :brandStatus WHERE brandID = :brand';
        $updateBrandStocksStmt = $pdo->prepare($updateBrandStocksSql);
        $updateBrandStocksStmt->bindParam(':totalBrandStocks', $totalBrandStocks, PDO::PARAM_INT);
        $updateBrandStocksStmt->bindParam(':brandStatus', $brandStatus, PDO::PARAM_STR);
        $updateBrandStocksStmt->bindParam(':brand', $brand, PDO::PARAM_INT);
        $updateBrandStocksStmt->execute();

        // Update category stocks and status
        $updateCategoriesStocksSql = 'UPDATE categories SET stocks = :totalCategoryStocks, status = :categoryStatus WHERE categoryID = :category';
        $updateCategoriesStocksStmt = $pdo->prepare($updateCategoriesStocksSql);
        $updateCategoriesStocksStmt->bindParam(':totalCategoryStocks', $totalCategoryStocks, PDO::PARAM_INT);
        $updateCategoriesStocksStmt->bindParam(':categoryStatus', $categoryStatus, PDO::PARAM_STR);
        $updateCategoriesStocksStmt->bindParam(':category', $category, PDO::PARAM_INT);
        $updateCategoriesStocksStmt->execute();

        header("location: product.php?productID=$productID&success=2");
        exit();
    } else {
        header("location: product.php?productID=$productID&success=3");
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
                            <li class="breadcrumb-item"><a href="product.php">Product</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
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
                    <h5 class="card-title">Add Category</h5>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $productID); ?>"
                        method="post">

                        <div class="form-floating">
                            <input type="text" name="productName" id="productName"
                                class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $productData['name']; ?>">
                            <label for="floatingInput">Product Name</label>
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="brand" id="brand"
                                        class="form-select <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>">
                                        <?php if (!empty($productData)) : ?>
                                        <option value="<?php echo $productData['brandsID']; ?>" class="bg-success">
                                            <?php echo $productData['brand']; ?></option>
                                        <?php endif; ?>
                                        <?php foreach ($brandData as $brandOption) : ?>
                                        <option value="<?php echo $brandOption['brandID']; ?>">
                                            <?php echo $brandOption['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="brand">Brand</label>
                                    <span class="invalid-feedback"><?php echo $Brand_err; ?></span>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="category" id="category"
                                        class="form-select <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                                        <?php if (!empty($productData)) : ?>
                                        <option value="<?php echo $productData['categoriesID']; ?>" class="bg-success">
                                            <?php echo $productData['category']; ?></option>
                                        <?php endif; ?>
                                        <?php foreach ($categoryData as $categoryOption) : ?>
                                        <option value="<?php echo $categoryOption['categoryID']; ?>">
                                            <?php echo $categoryOption['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="category">Category</label>
                                    <span class="invalid-feedback"><?php echo $category_err; ?></span>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="price" id="price"
                                        class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo isset($productData['price']) ? $productData['price'] : ''; ?>">
                                    <label for="floatingInput">Price</label>
                                    <span class="invalid-feedback"><?php echo $price_err; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="stocks" id="stocks"
                                        class="form-control <?php echo (!empty($stocks_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo isset($productData['stocks']) ? $productData['stocks'] : ''; ?>">
                                    <label for="floatingInput">Stocks</label>
                                    <span class="invalid-feedback"><?php echo $stocks_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="text" name="supplierName" id="supplierName"
                                class="form-control <?php echo (!empty($supplierName_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo isset($productData['supplier']) ? $productData['supplier'] : ''; ?>">
                            <label for="floatingInput">Supplier Name</label>
                            <span class="invalid-feedback"><?php echo $supplierName_err; ?></span>
                        </div>

                        <div class="form-floating">
                            <select name="status" id="status"
                                class="form-select <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>">
                                <option>~~SELECT~~</option>
                                <option value="available"
                                    <?php echo (isset($productData['status']) && $productData['status'] === 'available') ? 'selected' : ''; ?>>
                                    Available
                                </option>
                                <option value="unavailable"
                                    <?php echo (isset($productData['status']) && $productData['status'] === 'unavailable') ? 'selected' : ''; ?>>
                                    Unavailable
                                </option>
                            </select>
                            <label for="status">Status</label>
                            <span class="invalid-feedback"><?php echo $status_err; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-start">
                                <button type="submit" class="btn btn-primary me-2">Edit Product</button>
                                <a href="product.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>




        </div>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sidebars.js"></script>

    <script>
    // Initialize Bootstrap tooltips
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>

</body>

</html>