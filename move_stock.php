<?php
require 'db.php';

session_start();
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $warehouseID = $_POST['warehouse_id'];
    $quantity = $_POST['quantity'];
    $storeID = $_POST['store_id'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE WAREHOUSE SET Quantity = Quantity - :quantity WHERE warehouseID = :warehouseID");
        $stmt->execute(['quantity' => $quantity, 'warehouseID' => $warehouseID]);

        $stmt = $pdo->prepare("UPDATE STORE SET Quantity = Quantity + :quantity WHERE storeID = :storeID");
        $stmt->execute(['quantity' => $quantity, 'storeID' => $storeID]);

        $stmt = $pdo->prepare("INSERT INTO STOCKMOVEMENT (Date, warehouseID, storeID, quantity) VALUES (NOW(), :warehouseID, :storeID, :quantity)");
        $stmt->execute(['warehouseID' => $warehouseID, 'storeID' => $storeID, 'quantity' => $quantity]);

        $pdo->commit();

        $_SESSION['success_message'] = "Stock moved successfully from warehouse to store.";
        header('Location: staff_dashboard.php');
        echo "Stock moved successfully from warehouse to store." ;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to move stock: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM WAREHOUSE");
$warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM STORE");
$stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Move Stock - Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
 <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Welcome to Jackies Jewelry Store</h1>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <a href="staff_dashboard.php">Dashboard</a>
            <a href="report.php">View Report</a>
            <a href="logout.php">Logout</a>  |  Logged in as Staff
        </ul>
    </nav>
<body>
    <fieldset>
        <h1>Move Stock</h1>
        <form method="POST" action="move_stock.php">
            <label for="warehouse_id">Select Warehouse:</label><br>
            <select name="warehouse_id" required>
                <?php foreach ($warehouses as $warehouse): ?>
                    <option value="<?php echo $warehouse['warehouseID']; ?>"><?php echo htmlspecialchars($warehouse['Location']); ?> (Available: <?php echo $warehouse['quantity']; ?>)</option>
                <?php endforeach; ?>
            </select><br>
            <label for="quantity">Quantity to Move:</label><br>
            <input type="number" name="quantity" min="1" required><br>
            <label for="store_id">Select Store:</label><br>
            <select name="store_id" required>
                <?php foreach ($stores as $store): ?>
                    <option value="<?php echo $store['storeID']; ?>"><?php echo htmlspecialchars($store['storeName']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Move Stock</button>
        </form>
    </fieldset>
</body>
</html>