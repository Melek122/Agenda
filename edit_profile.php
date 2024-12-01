<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = $_POST['email'];
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    
    // If password is provided, update it
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET email = ?, username = ?, password = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssi", $new_email, $new_username, $hashed_password, $user_id);
    } else {
        // If no password is provided, just update the email and username
        $query = "UPDATE users SET email = ?, username = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssi", $new_email, $new_username, $user_id);
    }

    $stmt->execute();
    $stmt->close();

    header('Location: profile.php');
}

$query = "SELECT email, username FROM users WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_info = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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

        .form-group label {
            font-size: 18px;
            color: #43506C;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
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

        .btn-back {
            background-color: #3D619B;
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #EF4B4C;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Edit Your Profile</h2>
    <form method="POST" action="edit_profile.php">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user_info['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">New Password (Leave empty if you don't want to change it)</label>
            <input type="password" name="password" class="form-control" placeholder="Enter new password">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>

    <a href="profile.php" class="btn btn-back">Back to Profile</a>
</div>

</body>

</html>
