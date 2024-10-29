<?php
require 'vendor/autoload.php';

use MongoDB\Client;

try {
    $client = new Client("mongodb://localhost:27017");

    $database = $client->Faker_data;
    $collection = $database->Personne;

    
    $documents = $collection->find([], ['limit' => 10000]);

   
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

    
    foreach ($documents as $document) {
        echo "<tr>
                <td>" . htmlspecialchars($document['nom']) . "</td>
                <td>" . htmlspecialchars($document['prenom']) . "</td>
                <td>" . htmlspecialchars($document['date_de_naissance']->toDateTime()->format('Y-m-d')) . "</td>
                <td>" . htmlspecialchars($document['ville']) . "</td>
                <td>" . htmlspecialchars($document['adresse']) . "</td>
                <td>" . htmlspecialchars($document['profession']) . "</td>
              </tr>";
    }

    
    echo "</table>
    </body>
    </html>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
