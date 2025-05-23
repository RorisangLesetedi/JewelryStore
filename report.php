
<!DOCTYPE html>
<html lang="en">
<head>
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
            <a href="move_stock.php">Move Stock</a>
            <a href="logout.php">Logout</a>  |  Logged in as Staff
        </ul>
    </nav>
    <h1>Report Viewer</h1>
    <form method="post">
        <label for="reportType">Select Report:</label><br>
        <select name="reportType" id="reportType">
            <option value="" disabled selected>--------</option>
            <option value="customerInsights">Customer Insights</option>
            <option value="salesTrends">Sales Trends</option>
            <option value="topSellingItems">Top Selling Items</option>
            <option value="CostOfGoods">Cost Of Goods</option>
            <option value="NetSales">Net Sales</option>
            <option value="InventoryLevels">Inventory Levels</option>
            <option value="StorePerformance">Store Performance</option>
            
        </select>
        <button type="submit">View Report</button>
    </form>
</body>
</html>

<?php
include 'DataAccess.php';
include 'ReportClass.php';

$host = 'localhost';
$db = 'jewelrydb'; 
$user = 'root';
$pass = '';

$dataAccess = new DataAccess($host, $db, $user, $pass);

$report = new ReportClass($dataAccess);

if (isset($_POST['reportType'])) {
    $reportType = $_POST['reportType'];
    $data = [];
    $reportTitle = "";

    switch ($reportType) {
    case 'customerInsights':
        $data = $report->getCustomerInsights();
        break;
    case 'salesTrends':
        $data = $report->getSalesTrends();
        break;
    case 'topSellingItems':
        $data = $report->getTopSellingItems();
        break;
    case 'CostOfGoods':
        $data = $report->getCostOfGoods(); 
        break;
    case 'NetSales':
        $data = $report->getNetSales();
        break;
    case 'InventoryLevels':
        $data = $report->getInventoryLevels();
        break;
    case 'StorePerformance':
        $data = $report->getStorePerformance();
        break;
}
    // Display the report
    $report->displayReport($data, $reportTitle);
}
?>
