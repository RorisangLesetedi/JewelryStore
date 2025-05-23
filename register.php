<?php
session_start();
require 'db.php';

$successMessage = ''; 
$errorMessage = ''; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the date of birth
    $selectedDate = new DateTime($dob);
    $today = new DateTime();

    if ($selectedDate > $today) {
        $errorMessage = 'Date of birth cannot be in the future.';
    } else {
        // Insert new customer into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO CUSTOMER (Firstname, Lastname, Address, Telephone, DOB, Gender, Username, Password) 
            VALUES (:firstname, :lastname, :address, :telephone, :dob, :gender, :username, :password)
        ");
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'address' => $address,
            'telephone' => $telephone,
            'dob' => $dob,
            'gender' => $gender,
            'username' => $username,
            'password' => $hashedPassword
        ]);

        $successMessage = 'Successfully registered! You can now log in.';
        
        header('Location: register.php?success=1');
        exit;
    }
}

if (isset($_GET['success'])) {
    $successMessage = 'Successfully registered! You can now log in.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Jewelry Store</title>
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
                <a href="login.php">Login</a>
                <a href="index.php">About Us</a>  |  Logged in as a Guest
            </ul>
        </nav>
    </header>
    <fieldset> 
        <?php if ($successMessage): ?>
            <p style="color: green; font-weight: bold;"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <p style="color: red; font-weight: bold;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        
        <h1>Register</h1>
        <form method="POST" action="register.php">
            <label for="firstname">First Name:</label><br>
            <input type="text" name="firstname" required value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>">
            <br>
            <label for="lastname">Last Name:</label><br>
            <input type="text" name="lastname" required value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>">
            <br>
            <label for="address">Address:</label><br>
            <input type="text" name="address" required value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>">
            <br>
            <label for="telephone">Telephone:</label><br>
            <input type="text" name="telephone" required value="<?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?>">
            <br>
            <label for="dob">Date of Birth:</label><br>
            <input type="date" name="dob" required value="<?php echo isset($dob) ? htmlspecialchars($dob) : ''; ?>">
            <br>
            <label for="gender">Gender:</label><br>
            <select name ="gender" required>
                <option value="M" <?php echo (isset($gender) && $gender == 'M') ? 'selected' : ''; ?>>Male</option>
                <option value="F" <?php echo (isset($gender) && $gender == 'F') ? 'selected' : ''; ?>>Female</option>
            </select>
            <br>
            <label for="username">Username:</label><br>
            <input type="text" name="username" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            <br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" required>
            <br>
            <button type="submit">Register</button>
        </form>
    </fieldset>
</body>
</html>