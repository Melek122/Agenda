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
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }

        h2 {
            color: #333;
            font-size: 36px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .btn-primary, .btn-toggle-theme {
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
            border: none;
            border-radius: 50px;
            color: #fff;
            padding: 10px 20px;
            text-transform: uppercase;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-primary:hover, .btn-toggle-theme:hover {
            background: linear-gradient(to right, #fc5c7d, #6a82fb);
            transform: translateY(-2px);
        }

        .table {
            margin-top: 20px;
            background-color: #ffffff;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }

        .table th {
            background-color: #6a82fb;
            color: white;
            font-weight: bold;
        }

        .table td {
            color: #555;
            font-size: 14px;
        }

        .btn-edit {
            background-color: #4caf50;
            color: white;
            border-radius: 50px;
            font-size: 12px;
            padding: 5px 15px;
        }

        .btn-edit:hover {
            background-color: #388e3c;
        }

        .btn-delete {
            background-color: #e53935;
            color: white;
            border-radius: 50px;
            font-size: 12px;
            padding: 5px 15px;
        }

        .btn-delete:hover {
            background-color: #c62828;
        }

        .btn-danger {
            background-color: #ff5252;
            border-radius: 50px;
            padding: 10px 20px;
        }

        .btn-danger:hover {
            background-color: #ff1744;
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
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            }

            h2 {
                color: #ffffff;
            }

            .btn-primary, .btn-toggle-theme {
                background: linear-gradient(to right, #ff7e5f, #feb47b);
                color: white;
            }

            .btn-primary:hover, .btn-toggle-theme:hover {
                background: linear-gradient(to right, #feb47b, #ff7e5f);
            }

            .table {
                background-color: #212121;
            }

            .table th {
                background-color: #424242;
                color: white;
            }

            .table td {
                color: #b0bec5;
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
