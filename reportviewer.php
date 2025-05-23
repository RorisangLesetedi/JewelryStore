<?php
// Include necessary files
include 'DataAccess.php';
include 'ReportClass.php';


$dataAccess = new DataAccess();
$report = new ReportClass($dataAccess);


if (isset($_POST['reportType'])) {
    $reportType = $_POST['reportType'];
    $data = [];

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
    $report->displayReport($data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Viewer</title>
</head>
<body>
    <h1>Report Viewer</h1>
    <form method="post">
        <label for="reportType">Select Report:</label>
        <select name="reportType" id="reportType">
            <option value="customerInsights">Customer Insights</option>
            <option value="salesTrends">Sales Trends</option>
            <option value="topSellingItems">Top Selling Items</option>
            
        </select>
        <button type="submit">View Report</button>
    </form>
</body>
</html>