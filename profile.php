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

// Fetch user profile information (e.g., username, email)
$query = "SELECT email FROM users WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_info = $user_result->fetch_assoc();

// Fetch the number of events in each category
$category_query = "
    SELECT c.name AS category_name, COUNT(e.id) AS event_count
    FROM events e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
    GROUP BY c.name
";
$stmt = $con->prepare($category_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$category_result = $stmt->get_result();

// Fetch past and upcoming events
$past_events_query = "
    SELECT * FROM events 
    WHERE user_id = ? AND event_date < CURDATE()
    ORDER BY event_date DESC
";
$stmt = $con->prepare($past_events_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$past_events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$upcoming_events_query = "
    SELECT * FROM events 
    WHERE user_id = ? AND event_date >= CURDATE()
    ORDER BY event_date ASC
";
$stmt = $con->prepare($upcoming_events_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$upcoming_events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Close statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Your Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Circe:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #E9E9EB;
            font-family: 'Circe', sans-serif;
            color: #43506C;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 950px;
            margin: 30px auto;
        }

        h2 {
            font-size: 36px;
            color: #3D619B;
            text-align: center;
            margin-bottom: 40px;
        }

        .profile-info,
        .category-count,
        .event-list {
            margin-bottom: 30px;
        }

        h4 {
            font-size: 28px;
            color: #43506C;
        }

        p {
            font-size: 16px;
        }

        .btn-primary {
            background-color: #EF4B4C;
            color: white;
            border-radius: 5px;
            padding: 10px 25px;
            text-decoration: none;
        }

        .btn-primary:hover {
            background-color: #3D619B;
        }

        .event-list ul {
            list-style: none;
            padding-left: 0;
        }

        .event-list li {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .event-list li strong {
            font-size: 18px;
        }

        .event-list li em {
            font-size: 14px;
            color: #6c757d;
        }

        .event-list li .btn-primary {
            background-color: #3D619B;
            color: white;
        }

        .event-list li .btn-primary:hover {
            background-color: #EF4B4C;
        }

        .category-count p {
            font-size: 18px;
            color: #3D619B;
        }

        .category-count strong {
            font-size: 20px;
        }

        /* Back Button Styles */
        .btn-back {
            background-color: #3D619B;
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #EF4B4C;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user_info['email']); ?>!</h2>

        <!-- Profile Information -->
        <div class="profile-info mb-4">
            <h4>Profile Information</h4>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        </div>

        <!-- Event Categories and Count -->
        <div class="category-count">
            <h4>Event Categories</h4>
            <?php while ($category = $category_result->fetch_assoc()): ?>
                <p><strong><?php echo htmlspecialchars($category['category_name']); ?>:</strong>
                    <?php echo $category['event_count']; ?> events</p>
            <?php endwhile; ?>
        </div>

        <!-- Upcoming Events -->
        <div class="event-list">
            <h4>Upcoming Events</h4>
            <?php if (count($upcoming_events) > 0): ?>
                <ul>
                    <?php foreach ($upcoming_events as $event): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($event['title']); ?></strong><br>
                            <em><?php echo htmlspecialchars($event['event_date']); ?></em><br>
                            <a href="event_details.php?event_id=<?php echo $event['id']; ?>"
                                class="btn btn-primary btn-sm mt-2">View Event</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You don't have any upcoming events.</p>
            <?php endif; ?>
        </div>

        <!-- Past Events -->
        <div class="event-list">
            <h4>Past Events</h4>
            <?php if (count($past_events) > 0): ?>
                <ul>
                    <?php foreach ($past_events as $event): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($event['title']); ?></strong><br>
                            <em><?php echo htmlspecialchars($event['event_date']); ?></em><br>
                            <a href="event_details.php?event_id=<?php echo $event['id']; ?>"
                                class="btn btn-primary btn-sm mt-2">View Event</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You don't have any past events.</p>
            <?php endif; ?>
        </div>

        <!-- Back to Agenda Button -->
        <a href="index.php" class="btn btn-back">Back to Agenda</a>

    </div>

</body>

</html>
