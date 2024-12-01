<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $user_id = $_SESSION['user_id'];

    // Delete related event-tags first (if any)
    $stmt = $con->prepare("DELETE FROM event_tags WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();

    // Now delete the event itself
    $stmt = $con->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();

    // Redirect to the index page after deletion
    header('Location: index.php');
    exit();
} else {
    echo "Event ID not specified!";
    exit();
}
?>
