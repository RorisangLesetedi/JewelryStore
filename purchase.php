<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

// Fetch products from the database
$stmtProducts = $pdo->query("SELECT productID, productName, Cost, Amount FROM PRODUCT");
$products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

// Fetch stores from the database
$stmtStores = $pdo->query("SELECT storeID, storeName FROM STORE");
$stores = $stmtStores->fetchAll(PDO::FETCH_ASSOC);

// Retrieve success message from session
$successMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : '';
unset($_SESSION['successMessage']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Products - Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Jackies Jewelry Store</h1>
        <nav>
            <ul>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>  |  Logged in as a Customer
            </ul>
        </nav>
    </header>
    <script>
        function updateCostAndTotal() {
            const productSelect = document.querySelector('select[name="product_id"]');
            const quantityInput = document.querySelector('input[name="quantity"]');
            const costInput = document.querySelector('input[name="cost"]');
            const totalDisplay = document.querySelector('input[name="total"]');

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const cost = parseFloat(selectedOption.dataset.cost);
            const quantity = parseInt(quantityInput.value, 10) || 0;

            // Update cost input
            costInput.value = cost ? cost.toFixed(2) : '0.00';

            // Calculate total
            const total = cost * quantity;
            totalDisplay.value = total ? total.toFixed(2) : '0.00';
        }

        function updateQuantity() {
            const quantityInput = document.querySelector('input[name="quantity"]');
            const productSelect = document.querySelector('select[name="product_id"]');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const maxQuantity = parseInt(selectedOption.dataset.amount, 10);

            // Ensure quantity does not exceed available stock
            if (parseInt(quantityInput.value, 10) > maxQuantity) {
                quantityInput.value = maxQuantity;
            }

            updateCostAndTotal();
        }
    </script>
</head>
<body>
    <form method="POST" action="process_purchase.php">
        <fieldset>
            <legend>Purchase Products</legend>

            <?php if ($successMessage): ?>
                <div class="success-message" style="color: green; font-weight: bold;">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <label for="store_id">Select Store:</label><br>
            <select name="store_id" id="store_id" required>
                <option value="" disabled selected>Select a store</option>
                <?php foreach ($stores as $store): ?>
                    <option value="<?php echo $store['storeID']; ?>">
                        <?php echo htmlspecialchars($store['storeName']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="product_id">Select Product:</label><br>
            <select name="product_id" id="product_id" onchange="updateCostAndTotal()" required>
                <option value="" disabled selected>Select a product</option <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['productID']; ?>" 
                            data-cost="<?php echo $product['Cost']; ?>" 
                            data-amount="<?php echo $product['Amount']; ?>">
                        <?php echo htmlspecialchars($product['productName']); ?> - Available: <?php echo $product['Amount']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="quantity">Quantity:</label><br>
            <input type="number" name="quantity" id="quantity" min="1" oninput="updateQuantity()" required><br>

            <label for="cost">Cost per Item:</label><br>
            <input type="text" name="cost" id="cost" readonly><br>

            <label for="total">Total Amount:</label><br>
            <input type="text" name="total" id="total" readonly><br>

            <button type="submit">Purchase</button>
        </fieldset>
    </form>
</body>
</html>