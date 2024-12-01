<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Generate username from the first part of the email
    $username = explode('@', $email)[0];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $query = "INSERT INTO users (email, password, username) VALUES (?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $email, $hashed_password, $username);
    $stmt->execute();
    $stmt->close();

    // Redirect to the login page after successful registration
    header('Location: signin.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Circe:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Circe', sans-serif;
            background-color: #E9E9EB; /* Light background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #43506C; /* Primary text color */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .form-container {
            background-color: white;
            padding: 40px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            text-align: center;
            color: #43506C; /* Primary color for the title */
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #3D619B; /* Border color */
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #EF4B4C; /* Accent color for focus */
            outline: none;
            box-shadow: 0 0 8px rgba(239, 75, 76, 0.6);
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #3D619B; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #EF4B4C; /* Hover color */
            transform: translateY(-2px);
        }

        p {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #43506C; /* Text color */
        }

        a {
            color: #EF4B4C; /* Link color */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: #EF4B4C; /* Error message color */
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Sign Up</h2>
            <form action="signup.php" method="POST">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="signin.php">Log in here</a>.</p>
        </div>
    </div>
</body>

</html>
