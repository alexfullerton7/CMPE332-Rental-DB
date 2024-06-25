<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>rental</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1 class="center">Fullerton Property Management</h1>
	<h3 class="center">Serving Kingston and the Islands since 2024</h3>
	<div class="image-container">
        <img src="Kingston_Image.jpg" alt="Image Description" class="center-image">
	<p class="center"><a href="rental_groups.php" class="button">Rental Groups</a></p>

    <h2>Our Properties</h2>
	<!-- Properties Table -->
    <table border="1">
        <tr>
            <th>Property ID</th>
            <th>Owner(s)</th>
            <th>Manager</th>
        </tr>
        <?php
        // Include database connection
        include 'connectdb.php';

        // Query to fetch property id along with owners and manager
        $query = "
            SELECT p.propertyID, GROUP_CONCAT(DISTINCT CONCAT_WS(' ', pe.fName, pe.lname)) AS owners, CONCAT_WS(' ', pm.fName, pm.lname) AS manager
            FROM Property p
            LEFT JOIN OwnerProperty op ON p.propertyID = op.propertyID
            LEFT JOIN Person pe ON op.ownerID = pe.ID
            LEFT JOIN Person pm ON p.managerID = pm.ID
            GROUP BY p.propertyID
        ";

        $stmt = $connection->query($query);

        // Output data of each row
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row["propertyID"] . "</td>";
            echo "<td>" . $row["owners"] . "</td>";
            echo "<td>" . $row["manager"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

<?php
// Calculate average monthly rent for each category of rental unit from Property table
$avgRentQuery = "SELECT propertyType, AVG(rent) AS avgRent FROM Property GROUP BY propertyType";
$avgRentStmt = $connection->query($avgRentQuery);
$avgRents = $avgRentStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Average Monthly Rent Table -->
<h2>Average Monthly Rent</h2>
<table border="1">
    <tr>
        <th>House</th>
        <th>Apartment</th>
        <th>Room</th>
    </tr>
    <tr>
        <td><?php echo "$" . number_format($avgRents[0]['avgRent'], 2); ?></td>
        <td><?php echo "$" . number_format($avgRents[1]['avgRent'], 2); ?></td>
        <td><?php echo "$" . number_format($avgRents[2]['avgRent'], 2); ?></td>
    </tr>
</table>

    
	<?php
    // Close database connection
    $connection = null;
    ?>
	
	<p class="center"> Contact us: <a href="mailto:your-email@example.com">18awf1@queensu.ca</a></p>
</body>
</html>
