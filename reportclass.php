<?php
class ReportClass {
    private $da;

    public function __construct($dataAccess) {
        $this->da = $dataAccess;
    }

    // Customer Insights Report
    public function getCustomerInsights() {
        $query = "
            SELECT 
                c.customerID,
                c.Firstname,
                c.Lastname,
                c.Telephone,
                COUNT(t.tranID) AS TotalPurchases,
                SUM(t.Amount) AS TotalSpent
            FROM 
                CUSTOMER c
            LEFT JOIN 
                TRANSACTION t ON c.customerID = t.customerID
            GROUP BY 
                c.customerID, c.Firstname, c.Lastname, c.Telephone;
        ";
        return $this->da->GetData($query);
    }

    // Sales Trends Report
    public function getSalesTrends() {
        $query = "
            SELECT 
                DATE_FORMAT(t.tranDate, '%Y-%m') AS Month,
                SUM(t.Amount) AS TotalSales
            FROM 
                TRANSACTION t
            GROUP BY 
                Month
            ORDER BY 
                Month;
        ";
        return $this->da->GetData($query);
    }

    // Top Selling Items Report
    public function getTopSellingItems() {
        $query = "
            SELECT 
                p.productName,
                SUM(t.Quantity) AS TotalSold,
                SUM(t.Amount) AS TotalRevenue
            FROM 
                TRANSACTION t
            JOIN 
                PRODUCT p ON t.productID = p.productID
            GROUP BY 
                p.productName
            ORDER BY 
                TotalSold DESC
            LIMIT 10;
        ";
        return $this->da->GetData($query);
    }

    // Cost of Goods Report
    public function getCostOfGoods() {
        $query = "
            SELECT 
                SUM(t.Cost) AS TotalCOGS
            FROM 
                TRANSACTION t;
        ";
        return $this->da->GetData($query);
    }

    // Gross Profit Report
    public function getGrossProfit() {
        $query = "
            SELECT 
                (SUM(t.Amount) - SUM(t.Cost)) AS GrossProfit
            FROM 
                TRANSACTION t;
        ";
        return $this->da->GetData($query);
    }

    // Total Sales Report
    public function getTotalSales() {
        $query = "
            SELECT 
                SUM(t.Amount) AS TotalSales
            FROM 
                TRANSACTION t;
        ";
        return $this->da->GetData($query);
    }

    // Net Sales Report
    public function getNetSales() {
        $query = "
            SELECT 
                SUM(t.Amount) AS NetSales
            FROM 
                TRANSACTION t;
        ";
        return $this->da->GetData($query);
    }

    // Inventory Levels Report
    public function getInventoryLevels() {
        $query = "
            SELECT 
                p.productName,
                w.Quantity AS CurrentStock
            FROM 
                PRODUCT p
            JOIN 
                WAREHOUSE w ON p.productID = w.productID;
        ";
        return $this->da->GetData($query);
    }

    // Store Performance Report
    public function getStorePerformance() {
        $query = "
            SELECT 
                s.storeName,
                COUNT(t.tranID) AS TotalTransactions,
                SUM(t.Amount) AS TotalSales
            FROM 
                STORE s
            LEFT JOIN 
                TRANSACTION t ON s.storeID = t.storeID
            GROUP BY 
                s.storeName;
        ";
        return $this->da->GetData($query);
    }

    // Method to display results in a table
    public function displayReport($data, $reportTitle) {
    // Display the report title
    echo "<h2>" . htmlspecialchars($reportTitle) . "</h2>";

    if (empty($data)) {
        echo "<p>No data available for this report.</p>";
        return;
    }

    echo "<table border='1'>";
    echo "<tr>";
    foreach (array_keys($data[0]) as $header) {
        echo "<th>" . htmlspecialchars($header) . "</th>";
    }
    echo "</tr>";

    foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
}
?>