<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'faker_sql';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$limit = 10000; 


$sql = "SELECT nom, prenom, date_de_naissance, ville, adresse, profession FROM Personne LIMIT ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $limit);
$stmt->execute();


$result = $stmt->get_result();


echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Liste des Personnes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Liste des Personnes</h1>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date de Naissance</th>
            <th>Ville</th>
            <th>Adresse</th>
            <th>Profession</th>
        </tr>";


while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['nom']) . "</td>
            <td>" . htmlspecialchars($row['prenom']) . "</td>
            <td>" . htmlspecialchars($row['date_de_naissance']) . "</td>
            <td>" . htmlspecialchars($row['ville']) . "</td>
            <td>" . htmlspecialchars($row['adresse']) . "</td>
            <td>" . htmlspecialchars($row['profession']) . "</td>
          </tr>";
}


echo "</table>
</body>
</html>";


$stmt->close();
$conn->close();
?>
