<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="style.css">
    <link rel="import" href="fonctions.js">
</head>
<body>
    <header>
        <form method="get">
            <legend>Login</legend>
            <input type="text">
            <legend>Mot de passe</legend>
            <input type="password">
            <input type="submit" value="Connexion" name="submitConnect">
        </form>
        <form action="creerCompte.php">
            <button type="submit">Créer un compte</button>
        </form>
    </header>
</body>
</html>