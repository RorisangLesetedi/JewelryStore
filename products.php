<?php
session_start();

$products = [
    [
        'productName' => 'Gold Necklace',
        'description' => 'Elegant gold necklace with intricate design.',
        'productType' => 'Jewelry',
        'cost' => 1500.00,
        'image' => 'gold_necklace.jpeg',
        'sizeClass' => 'small-image'
    ],
    [
        'productName' => 'Diamond Ring',
        'description' => 'Stunning diamond ring with a brilliant cut.',
        'productType' => 'Jewelry',
        'cost' => 2500.00,
        'image' => 'diamond_ring.jpeg',
        'sizeClass' => 'small-image'
    ],
    [
        'productName' => 'Silver Bracelet',
        'description' => 'Stylish silver bracelet for everyday wear.',
        'productType' => 'Jewelry',
        'cost' => 800.00,
        'image' => 'silver_bracelet.jpg',
        'sizeClass' => 'small-image'
    ],
    [
        'productName' => 'Pearl Earrings',
        'description' => 'Classic pearl earrings for formal occasions.',
        'productType' => 'Jewelry',
        'cost' => 1200.00,
        'image' => 'pearl_earrings.jpeg',
        'sizeClass' => 'small-image'
    ],
    [
        'productName' => 'Ruby Pendant',
        'description' => 'Beautiful ruby pendant set in gold.',
        'productType' => 'Jewelry',
        'cost' => 1800.00,
        'image' => 'ruby_pendant.jpeg',
        'sizeClass' => 'small-image'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleDetails(id) {
            const details = document.getElementById(id);
            details.classList.toggle('hidden');
        }
    </script>
</head>
<body>
    <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Our Products</h1>
        <nav>
            <ul>
                 <a href="index.php">Home</a> 
                <a href="stores.php">Stores</a>
                <a href="login.php">Login</a>
                <a href="register.php">Sign Up</a>
                <a href="#index.php">About Us</a>  |  Logged in as a Guest
            </ul>
        </nav>
    </header>
    <main>
        <div class="product-list">
            <?php foreach($products as $index => $product): ?>
            <fieldset class="product-fieldset">
                <legend><?php echo htmlspecialchars($product['productName']); ?></legend>
                <div class="product-info">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" class="product-image <?php echo $product['sizeClass']; ?>">
                    <div class="product-details">
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($product['productType']); ?></p>
                        <p><strong>Cost:</strong> $<?php echo number_format($product['cost'], 2); ?></p>
                        <a href="login.php" class="purchase-button">Purchase</a>
                    </div>
                </div>
            </fieldset>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>