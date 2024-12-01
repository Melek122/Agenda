<?php
// Include the database connection
require_once 'db.php';  // Make sure the path to db.php is correct

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get the form data
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Hash the password before saving it (for security reasons)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user into the database
    $query = "INSERT INTO users (email, password) VALUES ('$email', '$hashedPassword')";

    // Execute the query
    if (mysqli_query($con, $query)) {
        echo "User successfully registered!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
            <h2>Inscription</h2>
            <form action="signup.php" method="POST">
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
                <button type="submit">S'inscrire</button>
            </form>
            <p>Vous avez déjà un compte ? <a href="signin.php">Connectez-vous ici</a>.</p>
        </div>
    </div>
</body>
</html>
