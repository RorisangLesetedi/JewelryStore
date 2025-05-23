<?php
require 'db.php';

// Query to retrieve store information
$query = "SELECT storeID, storeName, Address, Telephone, Location FROM store";

try {
    $stmt = $pdo->query($query);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Store Locator</h1>
        <nav>
            <ul>
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
               <a href="login.php">Login</a>
                <a href="register.php">Sign Up</a>
               <a href="#about-us">About Us</a> |  Logged in as a Guest
            </ul>
        </nav>
    </header>
    <main>
        <div class="store-list">
            <?php if ($stmt->rowCount() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Store Name</th>
                            <th>Address</th>
                            <th>Telephone</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['storeName']); ?></td>
                                <td><?php echo htmlspecialchars($row['Address']); ?></td>
                                <td><?php echo htmlspecialchars($row['Telephone']); ?></td>
                                <td><?php echo htmlspecialchars($row['Location']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No stores found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>