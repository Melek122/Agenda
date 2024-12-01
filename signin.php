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
    <style>
        /* General reset and basic styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #e5e5e5; /* Background color for the page */
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
        }

        /* Form container styling */
        .form-container {
            background-color: white;
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #294D61; /* Primary color for headers */
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
            border-color: #0F969C; /* Accent color on focus */
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #294D61; /* Primary button color */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0C7075; /* Hover effect for the submit button */
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #0F969C; /* Accent color for links */
            text-decoration: none;
        }

        .error {
            color: red;
            margin-top: 20px;
        }

        /* Welcome container styling */
        .welcome-container {
            background-color: #294D61; /* Primary color for the welcome container */
            color: white;
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
            color: #294D61; /* Primary color for button */
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .welcome-container button:hover {
            background-color: #6DA5C0; /* Secondary color for hover effect */
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
        <!-- Formulaire de connexion -->
        <div class="form-container">
            <h2>Se connecter</h2>
            <form action="signin.php" method="POST">
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
                <button type="submit" name="login">Se connecter</button>
            </form>

            <!-- Display error message -->
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <p>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a>.</p>
        </div>

        <!-- Welcome note -->
        <div class="welcome-container">
            <h3>Bienvenue dans l'Agenda !</h3>
            <p>Nous sommes ravis de vous accueillir ! Connectez-vous pour profiter d'une expérience organisée, où vous pouvez facilement gérer vos événements, tâches et rendez-vous.</p>
            <button onclick="window.location.href='signup.php'">Créer un compte</button>
        </div>
    </div>
</body>
</html>
