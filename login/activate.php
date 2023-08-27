<?php
ob_start();
session_start();

// Set timezone
date_default_timezone_set('Europe/London');

// Database connection constants
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'db');

try {
	// Create PDO connection
	$db = new PDO("mysql:host=" . DBHOST . ";charset=utf8mb4;dbname=" . DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	// Show error
	echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
	exit;
}

// Check if the activation link is clicked
if (isset($_GET['x']) && isset($_GET['y'])) {
	$memberID = $_GET['x'];
	$active = $_GET['y'];

	// Perform account activation
	try {
		$stmt = $db->prepare("UPDATE members SET active = 'Yes' WHERE memberID = :memberID AND active = :active");
		$stmt->execute(array(
			':memberID' => $memberID,
			':active' => $active
		));

		// Check if the row was updated
		if ($stmt->rowCount() == 1) {
			// Account activated successfully, redirect to login page
			header('Location: login.php?action=active');
			exit;
		} else {
			// Account activation failed
			echo "Your account could not be activated.";
		}
	} catch (PDOException $e) {
		// Show error if any database-related error occurs
		echo "Error: " . $e->getMessage();
	}
} else {
	// Invalid activation link
	echo "Invalid activation link.";
}
