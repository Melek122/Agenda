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
        /* Base Styles */
        body, .container {
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary, .btn-toggle-theme {
            transition: background 0.3s ease, transform 0.3s ease;
        }

        h2 {
            transition: color 0.3s ease;
        }

        .table {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-edit, .btn-delete, .btn-danger {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Light Theme */
        body.light-theme {
            background-color: #f8f9fa;
            color: #333;
        }

        .container.light-theme {
            background-color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2.light-theme {
            color: #333;
        }

        .btn-primary.light-theme, .btn-toggle-theme.light-theme {
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
        }

        .btn-primary.light-theme:hover, .btn-toggle-theme.light-theme:hover {
            background: linear-gradient(to right, #fc5c7d, #6a82fb);
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
            background-color: #121212;
            color: #e0e0e0;
        }

        .container.dark-theme {
            background-color: #1e1e1e;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        h2.dark-theme {
            color: #ffffff;
        }

        .btn-primary.dark-theme, .btn-toggle-theme.dark-theme {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
        }

        .btn-primary.dark-theme:hover, .btn-toggle-theme.dark-theme:hover {
            background: linear-gradient(to right, #feb47b, #ff7e5f);
        }

        .table.dark-theme {
            background-color: #212121;
        }

        .table.dark-theme th {
            background-color: #424242;
            color: white;
        }

        .table.dark-theme td {
            color: #b0bec5;
        }

        .btn-edit.dark-theme {
            background-color: #26a69a;
        }

        .btn-edit.dark-theme:hover {
            background-color: #00897b;
        }

        .btn-delete.dark-theme {
            background-color: #ef5350;
        }

        .btn-delete.dark-theme:hover {
            background-color: #d32f2f;
        }

        .btn-danger.dark-theme {
            background-color: #ef5350;
        }

        .btn-danger.dark-theme:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body class="light-theme">
    <div class="container light-theme">
        <h2>Your Agenda</h2>

        <button class="btn-toggle-theme light-theme" id="toggleTheme">Switch to Dark Theme</button>
        <a href="add_event.php" class="btn btn-primary light-theme">+ Add New Event</a>

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
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-small btn-edit light-theme">Edit</a>
                                <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-small btn-delete light-theme" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
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
            <a href="logout.php" class="btn btn-danger light-theme">Sign Out</a>
        </div>
    </div>

    <script>
        // JavaScript for Theme Toggle
        const toggleButton = document.getElementById('toggleTheme');
        const body = document.body;
        const container = document.querySelector('.container');
        const elementsToToggle = document.querySelectorAll(
            '.btn-primary, .btn-toggle-theme, .table, .btn-edit, .btn-delete, .btn-danger, h2'
        );

        toggleButton.addEventListener('click', () => {
            body.classList.toggle('dark-theme');
            body.classList.toggle('light-theme');
            container.classList.toggle('dark-theme');
            container.classList.toggle('light-theme');
            
            elementsToToggle.forEach(element => {
                element.classList.toggle('dark-theme');
                element.classList.toggle('light-theme');
            });

            toggleButton.textContent = body.classList.contains('dark-theme')
                ? 'Switch to Light Theme'
                : 'Switch to Dark Theme';
        });
    </script>
</body>
</html>
