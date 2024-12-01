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

// Capture the search query and sort option
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'event_date'; // Default sort by event date
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order is ascending

// Modify the query to include search and sorting functionality, and filter out past events
$query = "
    SELECT e.*, GROUP_CONCAT(t.tag_name ORDER BY t.tag_name ASC) AS tags, c.name AS category
    FROM events e
    LEFT JOIN event_tags et ON e.id = et.event_id
    LEFT JOIN tags t ON et.tag_id = t.id
    LEFT JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ? AND (e.title LIKE ? OR e.event_date LIKE ?) AND e.event_date >= CURDATE()
    GROUP BY e.id
    ORDER BY $sort_option $sort_order
";
$stmt = $con->prepare($query);
$search_term = "%" . $search_query . "%"; // Wrap search query with % for LIKE
$stmt->bind_param("iss", $user_id, $search_term, $search_term);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Circe:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            background-color: #E9E9EB; /* Light gray background */
            font-family: 'Circe', sans-serif; /* Circe font */
            margin: 0;
            padding: 0;
            color: #43506C; /* Primary text color */
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
            margin-top: 20px;
        }

        h2 {
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            color: #43506C; /* Dark blue for the title */
            letter-spacing: 1px;
        }

        /* Profile Button Styles */
        .btn-profile {
            background-color: #3D619B; /* Deep blue for profile button */
            color: white;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 8px;
            position: absolute;
            top: 20px;
            right: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-profile:hover {
            background-color: #EF4B4C; /* Red hover effect */
            transform: scale(1.05);
        }

        /* Button Styles */
        .btn-primary {
            background-color: #3D619B; /* Deep blue for primary buttons */
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #EF4B4C; /* Red hover effect */
            transform: scale(1.05);
        }

        .btn-edit {
            background-color: #43506C;  /* Matching the palette */
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-edit:hover {
            background-color: #EF4B4C;  /* Hover effect */
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #EF4B4C;  /* Red for delete buttons */
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #43506C;  /* Hover effect */
            transform: scale(1.05);
        }

        /* Table Styles */
        .table {
            margin-top: 20px;
            background-color: #ffffff;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }

        .table th {
            background-color: #43506C; /* Dark blue for headers */
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table td {
            font-size: 14px;
            color: #43506C;
        }

        .logout-container {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-logout {
            background-color: #EF4B4C; /* Red logout button */
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #3D619B; /* Hover effect */
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Profile Button (visible only if logged in) -->
    <a href="profile.php" class="btn-profile">Go to Profile</a>

    <div class="container">
        <!-- Logout Button -->
        <div class="logout-container">
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>

        <h2>Your Agenda</h2>

        <!-- Search Form -->
        <form action="index.php" method="GET" class="mb-4">
            <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <!-- Sort Dropdown -->
        <form action="index.php" method="GET" class="mb-4">
            <select name="sort" class="form-control">
                <option value="event_date" <?php echo ($sort_option == 'event_date') ? 'selected' : ''; ?>>Sort by Date</option>
                <option value="title" <?php echo ($sort_option == 'title') ? 'selected' : ''; ?>>Sort by Title</option>
            </select>

            <select name="order" class="form-control mt-2">
                <option value="ASC" <?php echo ($sort_order == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                <option value="DESC" <?php echo ($sort_order == 'DESC') ? 'selected' : ''; ?>>Descending</option>
            </select>

            <button type="submit" class="btn btn-primary mt-2">Sort</button>
        </form>

        <a href="add_event.php" class="btn btn-primary">+ Add New Event</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Tags</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP code to loop through events -->
                <?php if (!empty($events)) : ?>
                    <?php foreach ($events as $event) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td><?php echo htmlspecialchars($event['tags']); ?></td>
                            <td><?php echo htmlspecialchars($event['category']); ?></td>
                            <td>
                                <a href="edit_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                <a href="delete_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No events found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
