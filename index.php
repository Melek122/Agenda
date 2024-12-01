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
    <style id="theme-style">
        /* Default Light Theme */
        body {
            background-color: #f5f7fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }

        h2 {
            color: #333;
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            background-image: linear-gradient(to left, #ff5c8d, #6a82fb);
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
        }

        .btn-primary {
            background-color: #6a82fb;
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 10px 20px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background-color: #ff5c8d;
            transform: scale(1.05);
        }

        .table {
            margin-top: 20px;
            background-color: #ffffff;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: #6a82fb;
            color: white;
        }

        .table td {
            color: #555;
        }

        .btn-toggle-theme {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #6a82fb;
            border: none;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-toggle-theme:hover {
            background-color: #ff5c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Agenda</h2>

        <button class="btn-toggle-theme" id="toggleTheme">Switch to Dark Theme</button>
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

    <script>
        // JavaScript for Theme Toggle
        const toggleButton = document.getElementById('toggleTheme');
        const themeStyle = document.getElementById('theme-style');

        const darkTheme = `
            body {
                background-color: #121212;
                color: #e0e0e0;
            }

            .container {
                background-color: #1e1e1e;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            }

            h2 {
                background-image: linear-gradient(to right, #ff7e5f, #feb47b);
            }

            .btn-primary, .btn-toggle-theme {
                background-color: #ff7e5f;
            }

            .btn-primary:hover, .btn-toggle-theme:hover {
                background-color: #feb47b;
            }

            .table {
                background-color: #212121;
            }

            .table th {
                background-color: #424242;
            }

            .table td {
                color: #b0bec5;
            }
        `;

        toggleButton.addEventListener('click', () => {
            if (themeStyle.innerHTML === darkTheme) {
                themeStyle.innerHTML = ''; // Reset to Light Theme
                toggleButton.textContent = 'Switch to Dark Theme';
            } else {
                themeStyle.innerHTML = darkTheme; // Apply Dark Theme
                toggleButton.textContent = 'Switch to Light Theme';
            }
        });
    </script>
</body>
</html>
