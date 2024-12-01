<?php
session_start();
require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// Check if the event ID is provided in the URL
if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']); // Sanitize input
    $user_id = $_SESSION['user_id'];       // Get user ID from session

    // Prepare the SQL statement to delete the event
    $stmt = $con->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error deleting the event. Please try again.";
    }
} else {
    echo "Event ID not specified or invalid!";
}
?>
