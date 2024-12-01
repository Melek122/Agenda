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
        /* Base Styling */
        body, .container {
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-theme-switch {
            font-size: 14px;
            padding: 5px 12px;
            border-radius: 20px;
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
            border: none;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            position: absolute;
            top: 20px;
            right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-theme-switch:hover {
            background: linear-gradient(to right, #fc5c7d, #6a82fb);
            transform: scale(1.1);
        }

        h2 {
            transition: color 0.3s ease;
        }

        .table {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Light Theme */
        body.light-theme {
            background-color: #f9fafb;
            color: #333;
        }

        .container.light-theme {
            background-color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2.light-theme {
            color: #333;
        }

        .table.light-theme {
            background-color: #ffffff;
        }

        .table.light-theme th {
            background-color: #6a82fb;
            color: white;
        }

        .table.light-theme td {
            color: #555;
        }

        /* Dark Theme */
        body.dark-theme {
            background-color: #181818;
            color: #e0e0e0;
        }

        .container.dark-theme {
            background-color: #1f1f1f;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        h2.dark-theme {
            color: #ffffff;
        }

        .table.dark-theme {
            background-color: #1f1f1f;
        }

        .table.dark-theme th {
            background-color: #424242;
            color: white;
        }

        .table.dark-theme td {
            color: #b0bec5;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
            border: none;
            border-radius: 6px;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #fc5c7d, #6a82fb);
            transform: scale(1.05);
        }

        .btn-edit {
            background-color: #26a69a;
        }

        .btn-edit:hover {
            background-color: #00897b;
        }

        .btn-delete {
            background-color: #ef5350;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .btn-danger {
            background-color: #ef5350;
        }

        .btn-danger:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body class="light-theme">
    <div class="container light-theme">
        <button class="btn-theme-switch" id="themeSwitch">L</button>
        <h2>Your Agenda</h2>

        <a href="add_event.php" class="btn btn-primary">+ Add New Event</a>

        <table class="table table-bordered light-theme">
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
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
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

    <script>
        // Theme switcher script
        const themeSwitchButton = document.getElementById('themeSwitch');
        const body = document.body;
        const container = document.querySelector('.container');
        const elementsToToggle = document.querySelectorAll('.table, .btn-primary, h2');

        themeSwitchButton.addEventListener('click', () => {
            const isDarkMode = body.classList.toggle('dark-theme');
            body.classList.toggle('light-theme');
            container.classList.toggle('dark-theme');
            container.classList.toggle('light-theme');

            elementsToToggle.forEach(element => {
                element.classList.toggle('dark-theme');
                element.classList.toggle('light-theme');
            });

            themeSwitchButton.textContent = isDarkMode ? 'D' : 'L';
        });
    </script>
</body>
</html>

