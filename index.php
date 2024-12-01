<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
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
    <title>Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            background-color: #f4f4f9; /* Default for Dark Theme */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            transition: all 0.3s ease;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            transition: all 0.3s ease;
        }

        h2 {
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            color: #294D61;
            letter-spacing: 1px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #294D61;
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background-color: #0F969C;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #f5624d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #d64535;
            transform: scale(1.05);
        }

        .btn-small {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-edit {
            background-color: #0C7075;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #072E33;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        /* Table Styles */
        .table {
            margin-top: 20px;
            background-color: #ffffff;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }

        .table th {
            background-color: #294D61;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table td {
            font-size: 14px;
            color: #555;
        }

        .sign-out-btn {
            margin-top: 20px;
            text-align: center;
        }

        /* Theme Switcher */
        .theme-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .theme-switcher button {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            font-weight: bold;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Dark Theme Styles */
        body.dark {
            background-color: #121212;
            color: #e0e0e0;
        }

        body.dark .container {
            background-color: #1e1e1e;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        }

        body.dark h2 {
            color: #ff7e5f;
        }

        body.dark .btn-primary {
            background-color: #ff7e5f;
        }

        body.dark .btn-primary:hover {
            background-color: #feb47b;
        }

        body.dark .table th {
            background-color: #333;
            color: #e0e0e0;
        }

        body.dark .table td {
            color: #b0bec5;
        }

        /* Light Theme Styles */
        body.light {
            background-color: #e5e5e5; /* Light gray background */
            color: #333;
        }

        body.light .container {
            background-color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        body.light h2 {
            color: #294D61;
        }

        body.light .btn-primary {
            background-color: #294D61;
        }

        body.light .btn-primary:hover {
            background-color: #0F969C;
        }

        body.light .btn-danger {
            background-color: #f5624d;
        }

        body.light .btn-danger:hover {
            background-color: #d64535;
        }

        body.light .table th {
            background-color: #294D61;
            color: white;
        }

        body.light .table td {
            color: #555;
        }
    </style>
</head>
<body class="light">
    <div class="theme-switcher">
        <button id="theme-toggle" class="dark" onclick="switchTheme()">D</button>
    </div>

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
                <!-- PHP code to loop through events -->
                <?php if (!empty($events)) : ?>
                    <?php foreach ($events as $event) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td>
                                <!-- Edit Button -->
                                <a href="edit_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-small btn-edit">Edit</a>

                                <!-- Delete Button -->
                                <a href="delete_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-small btn-delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No events found. Click "Add New Event" to create one.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Sign out button -->
        <div class="sign-out-btn">
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </div>
    </div>

    <script>
        function switchTheme() {
            const body = document.body;
            const themeButton = document.getElementById('theme-toggle');

            // Toggle the body class to switch between light and dark themes
            if (body.classList.contains('light')) {
                body.classList.remove('light');
                body.classList.add('dark');
                themeButton.textContent = 'L';
            } else {
                body.classList.remove('dark');
                body.classList.add('light');
                themeButton.textContent = 'D';
            }
        }
    </script>
</body>
</html>
