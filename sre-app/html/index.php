<?php
// Chemin du fichier secret
$secret_file = '/run/secrets/mysql_root_password';

// Vérifie si le fichier de secret existe et lit son contenu
if (file_exists($secret_file)) {
    $password = trim(file_get_contents($secret_file)); // Supprime les espaces ou nouvelles lignes
} else {
    die("<h1>Erreur : Le fichier secret pour le mot de passe root est introuvable.</h1>");
}

// Informations de connexion
$servername = "db"; // Service Docker pour MariaDB
$username = "root";
$dbname = "sre-database"; // Changez si nécessaire

// Établir une connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("<h1>Connexion échouée : " . $conn->connect_error . "</h1>");
}

// Requête pour récupérer des enregistrements
$sql = "SELECT * FROM `sre-table`"; // Modifiez le nom de la table si nécessaire
$result = $conn->query($sql);

// Vérifiez si la requête a réussi
if (!$result) {
    die("<h1>Erreur dans la requête : " . $conn->error . "</h1>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Users</title>
    <!-- Ajoutez du style ici -->
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            line-height: 1.6;
        }

        h1, h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .records {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .record-item {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .no-records {
            color: #f44336;
            text-align: center;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #4CAF50;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #45a049;
        }
    </style>

</head>
<body>

<?php
// Display records if available
if ($result->num_rows > 0) {
    echo "<h1>Users from the Database</h1>";
    echo "<div class='records'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='record-item'>";
        echo "<p><strong>ID:</strong> " . $row["id"] . " | <strong>Name:</strong> " . $row["name"] . "</p>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<h2 class='no-records'>No Records Found</h2>";
}

// Close the database connection
$conn->close();
?>

<div style="text-align: center;">
    <a href="https://web.localhost/">Back to Home</a>
</div>

</body>
</html>

