<?php
session_start();

// Include the database connection file (assuming db.php contains MySQLi connection setup)
require_once 'db.php'; // Include the MySQLi connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php'); // Redirect to signin page if user is not logged in
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Query to fetch user events from the database using MySQLi
$query = "SELECT * FROM events WHERE user_id = ? ORDER BY event_date";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id); // Bind user_id as an integer
$stmt->execute();

// Get the results
$result = $stmt->get_result();

// Fetch all the events
$events = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Professional Agenda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 900px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            text-transform: uppercase;
            font-size: 2.5rem;
        }

        .btn-primary, .btn-danger {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover, .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .table th {
            background-color: #6a82fb;
            color: white;
        }

        .table td, .table th {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .sign-out-btn {
            text-align: center;
            margin-top: 15px;
        }

        .empty-message {
            text-align: center;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Agenda</h2>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="add_event.php" class="btn btn-primary">Add New Event</a>
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </div>
        <h3 class="text-center mb-4">Events Calendar</h3>
        <?php if ($events): ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-message">You have no events yet. Add your first event!</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
