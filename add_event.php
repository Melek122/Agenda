<?php
session_start();
require 'db.php';  // Include database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// Handle adding new events when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($con, $_POST['title']);  // Sanitize title input
    $description = mysqli_real_escape_string($con, $_POST['description']);  // Sanitize description input
    $event_date = $_POST['event_date'];  // Get event date from form

    // Insert event into the database
    $sql = "INSERT INTO events (user_id, title, description, event_date) VALUES ('$user_id', '$title', '$description', '$event_date')";

    // Execute the query
    if ($con->query($sql) === TRUE) {
        // If insertion is successful, redirect to index.php
        header('Location: index.php');
        exit();
    } else {
        // If there is an error in the insertion, show an error message
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e5e5e5; /* Light gray background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #294D61; /* Dark blue */
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            background-image: linear-gradient(to left, #0F969C, #6DA5C0);
            color: transparent;
            background-clip: text;
        }

        /* Form input and textarea styling */
        .form-group input, .form-group textarea {
            border-radius: 10px;
            border: 2px solid #ddd;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #0F969C; /* Accent color on focus */
            box-shadow: 0 0 8px rgba(15, 150, 156, 0.6);
            outline: none;
        }

        button[type="submit"] {
            background-color: #0F969C; /* Accent color */
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
            background-color: #294D61; /* Darker blue */
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #072E33; /* Dark teal */
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
            background-color: #05161A; /* Darkest teal */
        }

        /* Placeholder text styling */
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
