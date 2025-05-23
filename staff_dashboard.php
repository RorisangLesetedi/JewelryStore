<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
    header('Location: login.php');
    exit;
}

// Retrieve staff details
$staffID = $_SESSION['userID'];
$stmt = $pdo->prepare("SELECT * FROM STAFF WHERE staffID = :staffID");
$stmt->execute(['staffID' => $staffID]);
$staff = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$staff) {
    echo "Error: Staff details not found.";
    exit;
}

// Retrieve store name based on staff's storeID
$storeName = "Unknown"; // Default value if no store is found
if ($staff['storeID']) {
    $storeStmt = $pdo->prepare("SELECT storeName FROM STORE WHERE storeID = :storeID");
    $storeStmt->execute(['storeID' => $staff['storeID']]);
    $store = $storeStmt->fetch(PDO::FETCH_ASSOC);

    if ($store) {
        $storeName = $store['storeName'];
    }
}

// Success message
$successMessage = '';
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Jackies Jewelry Store</h1>
    </header>
</head>
<body>
    <main>
        <fieldset>
            <h1>Welcome, <?php echo htmlspecialchars($staff['Firstname']); ?></h1>
            <nav>
                <ul>
                    <a href="report.php">View Report</a>
                    <a href="move_stock.php">Move Stock</a>
                    <a href="logout.php">Logout</a>  |  Logged in as Staff
                </ul>
            </nav>
            <h2>Your Details</h2>
            <p>Name: <?php echo htmlspecialchars($staff['Firstname'] . ' ' . $staff['Lastname']); ?></p>
            <p>Position: <?php echo htmlspecialchars($staff['Position']); ?></p>
            <p>Workplace: <?php echo htmlspecialchars($storeName); ?></p>

            <?php if ($successMessage): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </main>
</body>
</html>