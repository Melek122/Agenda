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
    $tags = $_POST['tags'];  // Get tags from form input

    // Insert event into the database
    $sql = "INSERT INTO events (user_id, title, description, event_date) VALUES ('$user_id', '$title', '$description', '$event_date')";
    if ($con->query($sql) === TRUE) {
        $event_id = $con->insert_id; // Get the ID of the inserted event

        // Process tags
        $tags_array = array_map('trim', explode(',', $tags));  // Split tags by comma and trim whitespace
        foreach ($tags_array as $tag_name) {
            // Check if the tag already exists
            $tag_sql = "SELECT id FROM tags WHERE tag_name = '$tag_name'";
            $tag_result = $con->query($tag_sql);
            if ($tag_result->num_rows > 0) {
                $tag = $tag_result->fetch_assoc();
                $tag_id = $tag['id'];
            } else {
                // If tag doesn't exist, insert new tag
                $insert_tag_sql = "INSERT INTO tags (tag_name) VALUES ('$tag_name')";
                if ($con->query($insert_tag_sql) === TRUE) {
                    $tag_id = $con->insert_id; // Get the newly inserted tag ID
                }
            }

            // Link the tag to the event
            $link_tag_sql = "INSERT INTO event_tags (event_id, tag_id) VALUES ('$event_id', '$tag_id')";
            $con->query($link_tag_sql);
        }

        // Redirect to index.php after adding the event
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
    <link href="https://fonts.googleapis.com/css2?family=Circe:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Circe', sans-serif;
            background-color: #E9E9EB;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #43506C;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #43506C;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group input, 
        .form-group textarea {
            border-radius: 8px;
            border: 2px solid #3D619B;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            margin-bottom: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus, 
        .form-group textarea:focus {
            border-color: #EF4B4C;
            box-shadow: 0 0 8px rgba(239, 75, 76, 0.6);
            outline: none;
        }

        button[type="submit"] {
            background-color: #3D619B;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 15px;
        }

        button[type="submit"]:hover {
            background-color: #EF4B4C;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #43506C;
            color: white;
            text-align: center;
            padding: 14px;
            font-size: 16px;
            border-radius: 8px;
            display: inline-block;
            text-decoration: none;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #3D619B;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #888;
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
            <div class="form-group">
                <input type="text" name="tags" class="form-control" placeholder="Enter tags (comma separated)" required>
            </div>
            <button type="submit" class="btn">Add Event</button>
            <a href="index.php" class="btn-secondary">Back to Home</a>
        </form>
    </div>
</body>
</html>
