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

// Helper function to convert dd/mm/yyyy to yyyy-mm-dd format
function convertDateFormat($date) {
    $dateParts = explode('/', $date); // Split date by "/"
    return $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]; // Return in yyyy-mm-dd format
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/list/main.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* General Styles */
        body {
            background-color: #e5e5e5; /* Light gray background */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #072E33; /* Dark gray text color */
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }

        h2 {
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            color: #294D61; /* Dark blue for the title */
            letter-spacing: 1px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #294D61;
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background-color: #0F969C;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #f5624d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #d64535;
            transform: scale(1.05);
        }

        .btn-small {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-edit {
            background-color: #0C7075;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #072E33;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
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
            background-color: #294D61;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table td {
            font-size: 14px;
            color: #555;
        }

        .sign-out-btn {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Agenda</h2>

        <!-- Add New Event Button -->
        <a href="add_event.php" class="btn btn-primary">+ Add New Event</a>

        <!-- Calendar Display -->
        <div id="calendar"></div>

        <!-- Sign out button -->
        <div class="sign-out-btn">
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list/main.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare events for FullCalendar with date conversion
            const events = <?php echo json_encode($events); ?>;
            
            const calendarEvents = events.map(event => {
                const formattedDate = "<?php echo convertDateFormat($event['event_date']); ?>"; // PHP conversion here
                return {
                    title: event.title,
                    start: formattedDate, // Ensure event_date is in yyyy-mm-dd format
                    description: event.description
                };
            });

            // Initialize FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'interaction', 'list'],
                initialView: 'dayGridMonth',
                events: calendarEvents,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                eventClick: function(info) {
                    alert('Event: ' + info.event.title + '\n' + info.event.start.toISOString());
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
