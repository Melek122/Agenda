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
    <title>Your Professional Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #e0e0e0;
        }

        .container {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            max-width: 900px;
            width: 100%;
        }

        h2 {
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            background-image: linear-gradient(to right, #ff7e5f, #feb47b);
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
        }

        .btn-primary {
            background-color: #ff7e5f;
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 10px 20px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background-color: #feb47b;
            transform: scale(1.05);
        }

        .btn-small {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-edit {
            background-color: #29b6f6;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #0288d1;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #ef5350;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }

        .table {
            margin-top: 20px;
            background-color: #212121;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
            border: 1px solid #424242;
        }

        .table th {
            background-color: #424242;
            color: #e0e0e0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table td {
            font-size: 14px;
            color: #b0bec5;
        }

        .sign-out-btn {
            text-align: center;
            margin-top: 20px;
        }

        .btn-danger {
            background-color: #ef5350;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Agenda</h2>

        <a href="add_event.php" class="btn btn-primary">+ Add New Event</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($events): ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-small btn-edit">Edit</a>
                                <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-small btn-delete" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No events found. Start by adding one!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="sign-out-btn">
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </div>
    </div>
</body>
</html>
