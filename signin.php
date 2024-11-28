

<?php
// Inclure la connexion à la base de données
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si l'email existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie, démarrer la session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Rediriger vers le tableau de bord
        header('Location: index.php');
        exit; // Important de quitter le script après une redirection
    } else {
        echo "<p class='error'>E-mail ou mot de passe incorrect.</p>";
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
        /* Reset de la marge et du padding pour tous les éléments */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Corps de la page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        /* Conteneur avec deux sections */
        .container {
            display: flex;
            width: 80%;
            height: 80%;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Première partie - Formulaire de connexion */
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
            color: #333;
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
            border-color: #0066cc;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #005bb5;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #0066cc;
            text-decoration: none;
        }

        .error {
            color: red;
            margin-top: 20px;
        }

        /* Deuxième partie - Note de bienvenue */
        .welcome-container {
            background-color: #0066cc;
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
            background-color: #ffffff;
            color: #0066cc;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .welcome-container button:hover {
            background-color: #e0e0e0;
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
                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a>.</p>
        </div>

        <!-- Note de bienvenue -->
        <div class="welcome-container">
            <h3>Bienvenue dans l'Agenda !</h3>
            <p>Nous sommes ravis de vous accueillir ! Connectez-vous pour profiter d'une expérience organisée, où vous pouvez facilement gérer vos événements, tâches et rendez-vous.</p>
            <button onclick="window.location.href='signup.php'">Créer un compte</button>
        </div>
    </div>
</body>
</html>
