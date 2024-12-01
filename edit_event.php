<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the event ID is provided
if (!isset($_GET['event_id'])) {
    echo "No event ID provided.";
    exit();
}

$event_id = $_GET['event_id'];

// Fetch the event details for the given event ID
$stmt = $con->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $event_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Event not found or access denied.";
    exit();
}

$event = $result->fetch_assoc();
$stmt->close();

// Handle form submission to update the event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $event_date = $_POST['event_date'];
    $tags = $_POST['tags'];

    // Update the event in the database
    $update_sql = "UPDATE events SET title = ?, description = ?, event_date = ? WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("sssii", $title, $description, $event_date, $event_id, $user_id);

    if ($stmt->execute()) {
        // Clear existing tags for the event
        $clear_tags_sql = "DELETE FROM event_tags WHERE event_id = ?";
        $clear_stmt = $con->prepare($clear_tags_sql);
        $clear_stmt->bind_param("i", $event_id);
        $clear_stmt->execute();
        $clear_stmt->close();

        // Add updated tags
        $tags_array = array_map('trim', explode(',', $tags));
        foreach ($tags_array as $tag_name) {
            // Check if the tag exists
            $tag_sql = "SELECT id FROM tags WHERE tag_name = ?";
            $tag_stmt = $con->prepare($tag_sql);
            $tag_stmt->bind_param("s", $tag_name);
            $tag_stmt->execute();
            $tag_result = $tag_stmt->get_result();

            if ($tag_result->num_rows > 0) {
                $tag = $tag_result->fetch_assoc();
                $tag_id = $tag['id'];
            } else {
                // Insert new tag
                $insert_tag_sql = "INSERT INTO tags (tag_name) VALUES (?)";
                $insert_tag_stmt = $con->prepare($insert_tag_sql);
                $insert_tag_stmt->bind_param("s", $tag_name);
                $insert_tag_stmt->execute();
                $tag_id = $insert_tag_stmt->insert_id;
                $insert_tag_stmt->close();
            }

            $tag_stmt->close();

            // Link the tag to the event
            $link_tag_sql = "INSERT INTO event_tags (event_id, tag_id) VALUES (?, ?)";
            $link_tag_stmt = $con->prepare($link_tag_sql);
            $link_tag_stmt->bind_param("ii", $event_id, $tag_id);
            $link_tag_stmt->execute();
            $link_tag_stmt->close();
        }

        header('Location: index.php');
        exit();
    } else {
        echo "Error updating event: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Event</title>
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
        <h2>Edit Event</h2>
        <form action="edit_event.php?event_id=<?php echo $event_id; ?>" method="POST">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Event Title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>
            <div class="form-group">
                <input type="date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control" placeholder="Event Description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="form-group">
                <input type="text" name="tags" class="form-control" placeholder="Enter tags (comma separated)" value="<?php 
                    $tag_query = "SELECT t.tag_name FROM tags t JOIN event_tags et ON t.id = et.tag_id WHERE et.event_id = $event_id";
                    $tag_result = $con->query($tag_query);
                    $tags = [];
                    while ($tag_row = $tag_result->fetch_assoc()) {
                        $tags[] = $tag_row['tag_name'];
                    }
                    echo htmlspecialchars(implode(', ', $tags));
                ?>" required>
            </div>
            <button type="submit" class="btn">Update Event</button>
            <a href="index.php" class="btn-secondary">Back to Home</a>
        </form>
    </div>
</body>
</html>
