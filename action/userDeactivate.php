<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request is a POST request

    // Assuming you have a function to sanitize and validate input, you should implement it here
    $adminID = $_POST['adminID'];
    $userType = $_POST['userType'];

    // Perform the deactivation based on userType
    if ($userType == 1) {
        $stmt = $pdo->prepare("UPDATE admins SET status = 'inactive' WHERE adminID = :adminID");
    } elseif ($userType == 2) {
        $stmt = $pdo->prepare("UPDATE users SET status = 'inactive' WHERE userid = :adminID");
    }

    $stmt->bindParam(':adminID', $adminID, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deactivated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deactivating user']);
    }

    // Ensure that no further code is executed after this block to prevent additional output
    exit();
}
?>
