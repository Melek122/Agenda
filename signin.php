<?php
// Include the database connection file
require_once 'db.php'; // Ensure db.php is included for the MySQLi connection

// Start the session
session_start();

// Handle the Sign-In Process
if (isset($_POST['login'])) {
    // Get the email and password from the form
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    // Check if the email exists in the database
    $checkUserQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $checkUserQuery);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the user record
        $user = mysqli_fetch_assoc($result);
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables and redirect to index page
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: index.php"); // Redirect to the main page (index.php)
            exit();
        } else {
            // If password is incorrect
            $error = "Incorrect password!";
        }
    } else {
        // If email not found
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            width: 80%;
            height: 80%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            background-color: white;
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #43506C; /* Primary text color */
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3D619B; /* Input focus color */
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #3D619B; /* Button background */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color: #EF4B4C; /* Hover background */
            transform: translateY(-2px);
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #EF4B4C; /* Accent link color */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: #EF4B4C; /* Error message color */
            margin-top: 20px;
        }

        .welcome-container {
            background-color: #43506C; /* Dark container background */
            color: white;
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .welcome-container h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .welcome-container p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .welcome-container button {
            padding: 12px 25px;
            background-color: white;
            color: #43506C; /* Text on welcome button */
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .welcome-container button:hover {
            background-color: #EF4B4C; /* Button hover background */
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
                width: 100%;
            }

            .form-container,
            .welcome-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <form action="signin.php" method="POST">
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
                <button type="submit" name="login">Login</button>
            </form>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <p>Don't have an account yet ? <a href="signup.php">sign-up now</a>.</p>
        </div>
        <div class="welcome-container">
            <h3>Welcome to the Agenda !</h3>
            <p>Welcome to our web app! Log in to enjoy an organized experience where you can easily manage your events, tasks, and appointments.</p>
            <button onclick="window.location.href='signup.php'">Sign-up Now</button>
        </div>
    </div>
</body>
</html>
