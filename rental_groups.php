<!-- rental_groups.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rental Groups</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<h1 class="center">Rental Groups</h1>
		<div class="image-container">
			<img src="Kingston_Image.jpg" alt="Image Description" class="center-image">
		</div>
		
    <!-- Display Rental Groups -->
    <form action="" method="post">
        <label for="group">Choose a Rental Group:</label>
        <select name="group" id="group">
            <?php
            // Include database connection
            include 'connectdb.php';

            // Retrieve all rental groups
            $query = "SELECT * FROM RentalGroup";
            $stmt = $connection->query($query);

            // Display rental groups in dropdown menu
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['groupCode'] . "'>" . $row['groupCode'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="selectGroup" value="Show Details">
    </form>

    <?php
    // Check if form is submitted to display rental group details
    if (isset($_POST["selectGroup"])) {
        // Get the selected group code
        $selectedGroup = $_POST['group'];
        
        // Retrieve renters of the selected group
        $query = "SELECT r.*, CONCAT_WS(' ', p.fName, p.lname) AS fullName 
                  FROM Renter r 
                  LEFT JOIN Person p ON r.renterID = p.ID 
                  WHERE r.groupCode = :groupCode";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':groupCode', $selectedGroup);
        $stmt->execute();

        // Display renters and their preferences
        echo "<h2>Renters of Group $selectedGroup:</h2>";
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>" . $row['fullName'] . "</li>";
        }
        echo "</ul>";

        // Retrieve rental group details
        $query = "SELECT * FROM RentalGroup WHERE groupCode = :groupCode";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':groupCode', $selectedGroup);
        $stmt->execute();
        $rentalGroup = $stmt->fetch(PDO::FETCH_ASSOC);

        // Display rental group details in form for updating
        ?>
        <h2>Update Rental Group Preferences</h2>
        <form action="" method="post">
            <label for="propertyType">Property Type:</label>
            <select name="propertyType" id="propertyType">
                <option value="house" <?php if ($rentalGroup['propertyType'] == 'house') echo 'selected'; ?>>House</option>
                <option value="apartment" <?php if ($rentalGroup['propertyType'] == 'apartment') echo 'selected'; ?>>Apartment</option>
                <option value="room" <?php if ($rentalGroup['propertyType'] == 'room') echo 'selected'; ?>>Room</option>
            </select><br>
            <label for="numBedrooms">Number of Bedrooms:</label>
            <input type="number" name="numBedrooms" id="numBedrooms" value="<?php echo $rentalGroup['numBedrooms']; ?>"><br>
            <label for="numBathrooms">Number of Bathrooms:</label>
            <input type="number" name="numBathrooms" id="numBathrooms" value="<?php echo $rentalGroup['numBathrooms']; ?>"><br>
            <label for="parking">Parking:</label>
            <select name="parking" id="parking">
                <option value="yes" <?php if ($rentalGroup['parking'] == 'yes') echo 'selected'; ?>>Yes</option>
                <option value="no" <?php if ($rentalGroup['parking'] == 'no') echo 'selected'; ?>>No</option>
            </select><br>
            <label for="laundry">Laundry:</label>
            <select name="laundry" id="laundry">
                <option value="yes" <?php if ($rentalGroup['laundry'] == 'yes') echo 'selected'; ?>>Yes</option>
                <option value="no" <?php if ($rentalGroup['laundry'] == 'no') echo 'selected'; ?>>No</option>
            </select><br>
            <label for="max_monthly_rent">Max Monthly Rent:</label>
            <input type="text" name="max_monthly_rent" id="max_monthly_rent" value="<?php echo $rentalGroup['max_monthly_rent']; ?>"><br>
            <label for="accessibility">Accessibility:</label>
            <select name="accessibility" id="accessibility">
                <option value="yes" <?php if ($rentalGroup['accessibility'] == 'yes') echo 'selected'; ?>>Yes</option>
                <option value="no" <?php if ($rentalGroup['accessibility'] == 'no') echo 'selected'; ?>>No</option>
            </select><br>
            <input type="hidden" name="selectedGroup" value="<?php echo $selectedGroup; ?>">
            <input type="submit" name="updatePreferences" value="Update Preferences">
        </form>
        <?php
    }

    // Check if form is submitted to update preferences
    if (isset($_POST["updatePreferences"])) {
        // Get the selected group code and updated preferences
        $selectedGroup = $_POST['selectedGroup'];
        $propertyType = $_POST['propertyType'];
        $numBedrooms = $_POST['numBedrooms'];
        $numBathrooms = $_POST['numBathrooms'];
        $parking = $_POST['parking'];
        $laundry = $_POST['laundry'];
        $max_monthly_rent = $_POST['max_monthly_rent'];
        $accessibility = $_POST['accessibility'];

        // Update rental group preferences in the database
        $query = "UPDATE RentalGroup SET propertyType = :propertyType, numBedrooms = :numBedrooms, numBathrooms = :numBathrooms, parking = :parking, laundry = :laundry, max_monthly_rent = :max_monthly_rent, accessibility = :accessibility WHERE groupCode = :groupCode";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':propertyType', $propertyType);
        $stmt->bindParam(':numBedrooms', $numBedrooms);
        $stmt->bindParam(':numBathrooms', $numBathrooms);
        $stmt->bindParam(':parking', $parking);
        $stmt->bindParam(':laundry', $laundry);
        $stmt->bindParam(':max_monthly_rent', $max_monthly_rent);
        $stmt->bindParam(':accessibility', $accessibility);
        $stmt->bindParam(':groupCode', $selectedGroup);
        $stmt->execute();

        // Display success message
        echo "<p>Rental group preferences updated successfully.</p>";
    }

    // Close database connection
    $connection = null; // Close the connection
    ?>
	
	<p><a href="rental.php" class="button">Back Home</a></p>
	
</body>
</html>
