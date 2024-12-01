<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the event details
    $stmt = $con->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Event not found!";
        exit();
    }

    $event = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission to update event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];

    $stmt = $con->prepare("UPDATE events SET title = ?, description = ?, event_date = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $event_date, $event_id);
    $stmt->execute();

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .form-group input::placeholder, .form-group textarea::placeholder {
            color: #aaa;
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
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </form>
    </div>
</body>
</html>
