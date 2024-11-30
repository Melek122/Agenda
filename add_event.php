<?php
session_start();
require 'db.php'; // Include the database connection file

// Check if the user is logged in; if not, redirect to signin.php
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle adding new events when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data and sanitize it
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $event_date = $_POST['event_date']; // Assuming 'event_date' is in a valid format (YYYY-MM-DD)

    // Prepare the SQL query to insert the new event into the database
    $stmt = $pdo->prepare("INSERT INTO events (user_id, title, description, event_date) VALUES (:user_id, :title, :description, :event_date)");

    // Execute the query with the provided values
    $stmt->execute([
        'user_id' => $user_id, 
        'title' => $title, 
        'description' => $description, 
        'event_date' => $event_date
    ]);

    // Redirect the user back to the main page (index.php) after successfully adding the event
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <style>
        /* Your existing styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(135deg, #ff5c8d, #6a82fb);
            animation: fadeIn 0.8s ease-out;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            animation: slideIn 1s ease-out;
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
            animation: textAnimate 2s ease-out infinite;
        }

        /* Animation for title */
        @keyframes textAnimate {
            0% { background-position: -500% 0; }
            50% { background-position: 500% 0; }
            100% { background-position: -500% 0; }
        }

        .form-group input, .form-group textarea {
            border-radius: 10px;
            border: 2px solid #ddd;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #6a82fb;
            box-shadow: 0 0 8px rgba(106, 130, 251, 0.6);
            outline: none;
        }

        button[type="submit"] {
            background-color: #6a82fb;
            color: #fff;
            border: none;
            padding: 14px;
            font-size: 16px;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #ff5c8d;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #444;
            color: #fff;
            padding: 14px;
            width: 100%;
            font-size: 16px;
            border-radius: 10px;
            text-align: center;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #333;
        }

        /* Animation keyframes */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideIn {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .form-group input::placeholder, .form-group textarea::placeholder {
            color: #aaa;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Event</h2>
        <form action="add_event.php" method="POST">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Event Title" required>
            </div>
            <div class="form-group">
                <input type="date" name="event_date" class="form-control" required>
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control" placeholder="Event Description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </form>
    </div>
</body>
</html>
<?php
session_start();
require 'db.php'; // Include the database connection file

// Check if the user is logged in; if not, redirect to signin.php
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle adding new events when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data and sanitize it
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $event_date = $_POST['event_date']; // Assuming 'event_date' is in a valid format (YYYY-MM-DD)

    // Prepare the SQL query to insert the new event into the database
    $stmt = $pdo->prepare("INSERT INTO events (user_id, title, description, event_date) VALUES (:user_id, :title, :description, :event_date)");

    // Execute the query with the provided values
    $stmt->execute([
        'user_id' => $user_id, 
        'title' => $title, 
        'description' => $description, 
        'event_date' => $event_date
    ]);

    // Redirect the user back to the main page (index.php) after successfully adding the event
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <style>
        /* Your existing styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(135deg, #ff5c8d, #6a82fb);
            animation: fadeIn 0.8s ease-out;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            animation: slideIn 1s ease-out;
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
            animation: textAnimate 2s ease-out infinite;
        }

        /* Animation for title */
        @keyframes textAnimate {
            0% { background-position: -500% 0; }
            50% { background-position: 500% 0; }
            100% { background-position: -500% 0; }
        }

        .form-group input, .form-group textarea {
            border-radius: 10px;
            border: 2px solid #ddd;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #6a82fb;
            box-shadow: 0 0 8px rgba(106, 130, 251, 0.6);
            outline: none;
        }

        button[type="submit"] {
            background-color: #6a82fb;
            color: #fff;
            border: none;
            padding: 14px;
            font-size: 16px;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #ff5c8d;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #444;
            color: #fff;
            padding: 14px;
            width: 100%;
            font-size: 16px;
            border-radius: 10px;
            text-align: center;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #333;
        }

        /* Animation keyframes */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideIn {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .form-group input::placeholder, .form-group textarea::placeholder {
            color: #aaa;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Event</h2>
        <form action="add_event.php" method="POST">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Event Title" required>
            </div>
            <div class="form-group">
                <input type="date" name="event_date" class="form-control" required>
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control" placeholder="Event Description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </form>
    </div>
</body>
</html>
