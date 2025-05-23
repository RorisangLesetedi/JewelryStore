<?php
session_start();
require 'db.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'customer') {
    header('Location: login.php');
    exit;
}

// Retrieve customer details
$customerID = $_SESSION['userID'];
$stmt = $pdo->prepare("SELECT * FROM CUSTOMER WHERE customerID = :customerID");
$stmt->execute(['customerID' => $customerID]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "Error: Customer details not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
         <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Jackies Jewelry Store</h1>
        <nav>
                <ul>
                     <a href="purchase.php">Purchase</a>
                    <a href="logout.php">Logout</a>  |  Logged in as a Customer
                </ul>
        </nav>
    </header>
    <fieldset>
        <main>
            <h1>Welcome, <?php echo htmlspecialchars($customer['Firstname']); ?></h1>
            <h2>Your Details</h2>
            <p>Name: <?php echo htmlspecialchars($customer['Firstname'] . ' ' . $customer['Lastname']); ?></p>
            <p>Address: <?php echo htmlspecialchars($customer['Address']); ?></p>
            <p>Telephone: <?php echo htmlspecialchars($customer['Telephone']); ?></p>
        </main>
    </fieldset>
</body>
</html>