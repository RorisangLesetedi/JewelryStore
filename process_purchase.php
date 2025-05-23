<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

$storeID = $_POST['store_id'];
$productID = $_POST['product_id'];
$quantity = $_POST['quantity'];
$cost = $_POST['cost'];

if (empty($quantity) || $quantity <= 0) {
    $_SESSION['successMessage'] = "Quantity must be a positive number.";
    header('Location: purchase.php');
    exit;
}

if (empty($cost) || $cost < 0) {
    $_SESSION['successMessage'] = "Cost must be a non-negative number.";
    header('Location: purchase.php');
    exit;
}

$amount = $cost * $quantity;

if (empty($amount) || $amount < 0) {
    $_SESSION['successMessage'] = "Amount must be a non-negative number.";
    header('Location: purchase.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT Amount FROM PRODUCT WHERE productID = ?");
    $stmt->execute([$productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['Amount'] >= $quantity) {
        $stmt = $pdo->prepare("INSERT INTO transaction (customerID, storeID, productID, Quantity, Cost, Amount, tranid, trandate, trantime) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$_SESSION['userID'], $storeID, $productID, $quantity, $cost, $amount, $tranid]);

        $stmt = $pdo->prepare("UPDATE PRODUCT SET Amount = Amount - ? WHERE productID = ?");
        $stmt->execute([$quantity, $productID]);

        $pdo->commit();

        $_SESSION['successMessage'] = "Purchase successful! Thank you for your order.";
    } else {
        throw new Exception("Insufficient stock available.");
    }
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['successMessage'] = "An error occurred during the purchase: " . $e->getMessage();
}

header('Location: purchase.php');
exit;
?>