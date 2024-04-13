<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $orderID = trim($_POST['orderID']);
    $dueAmount = trim($_POST['dueValue']);
    $changeAmount = trim($_POST['changeValue']);
    $paidAmount = trim($_POST['paidTotal']);
    $payOption = trim($_POST['payOption']);
    $payStatus = trim($_POST['payStatus']);

    $updateOrdersSql = 'UPDATE orders SET dueAmount = :dueAmount, changeAmount = :changeAmount, paidAmount = :paidAmount, paymentOption = :payOption, paymentStatus = :payStatus WHERE orderID = :orderID';

    $updateOrderStmt = $pdo->prepare($updateOrdersSql);
    $updateOrderStmt->bindParam(':dueAmount', $dueAmount, PDO::PARAM_INT);
    $updateOrderStmt->bindParam(':changeAmount', $changeAmount, PDO::PARAM_INT);
    $updateOrderStmt->bindParam(':paidAmount', $paidAmount, PDO::PARAM_INT);
    $updateOrderStmt->bindParam(':payOption', $payOption, PDO::PARAM_STR); // Assuming it's a string, adjust if it's an integer
    $updateOrderStmt->bindParam(':payStatus', $payStatus, PDO::PARAM_STR);
    $updateOrderStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);

    $updateSuccess = $updateOrderStmt->execute();

    if ($updateSuccess) {
        header("location: http://localhost/WebIMS/order.php?success=2");
        exit();
    } else {
        header("location: http://localhost/WebIMS/order.php?success=3");
        exit();
    }
}
?>
