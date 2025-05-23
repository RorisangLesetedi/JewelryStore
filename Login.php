<?php
session_start();
if (!isset($_SESSION)) {
    echo "Session is not active.";
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("
            SELECT customerID AS userID, Username, Password, 'customer' AS userType 
            FROM CUSTOMER 
            WHERE Username = :username1
            UNION ALL
            SELECT StaffID AS userID, Username, Password, 'staff' AS userType 
            FROM STAFF 
            WHERE Username = :username2
        ");

        //bind parameter and execute
        $stmt->bindParam(':username1', $username);
        $stmt->bindParam(':username2', $username);
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['Password'])) {
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['userType'] = $user['userType'];

                // Redirect based on user type
                if ($user['userType'] === 'customer') {
                    header('Location: dashboard.php');
                } elseif ($user['userType'] === 'staff') {
                    header('Location: staff_dashboard.php');
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found. Please register.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
     <header>
        <img src="banner1.jpg" alt="Jewelry Store Banner">
        <h1>Welcome to Jackies Jewelry Store</h1>
        <nav>
            <ul>
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="stores.php">Stores</a>
                <a href="index.php">About Us</a>
                <a href="register.php">Sign Up</a>  |  Logged in as a Guest
            </ul>
        </nav>
    </header>
    <fieldset>
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
        <label>New customer?</label><br>
        <button onclick="window.location.href='register.php';">Sign up</button>
    </fieldset>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
