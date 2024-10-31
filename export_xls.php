<?php
// Databaseconfiguratie
$servername = "localhost"; // Naam van de server waar de database draait
$username = "root"; // Gebruikersnaam voor toegang tot de database
$password = "root"; // Wachtwoord voor de database gebruiker
$dbname = "gegevensverzameling"; // Naam van de database waarmee we verbinding maken

// Maak verbinding met de database met behulp van de mysqli klasse
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding met de database
if ($conn->connect_error) {
    // Stop het script en geef een foutmelding als de verbinding mislukt
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Zet de headers voor het Excel-bestand
header("Content-Type: application/vnd.ms-excel"); // Geef aan dat het een Excel-bestand is
header("Content-Disposition: attachment; filename=gegevens_overzicht.xls"); // Geef de naam van het bestand op bij het downloaden
header("Pragma: no-cache"); // Voorkom caching van het bestand
header("Expires: 0"); // Zorg ervoor dat het bestand onmiddellijk verloopt

// Begin met de HTML-output voor het Excel-bestand
echo "<table border='1'> <!-- Start een tabel met een rand -->
        <thead>
            <tr>
                <th>Naam</th> <!-- Kolom voor naam -->
                <th>E-mail</th> <!-- Kolom voor e-mail -->
                <th>Bericht</th> <!-- Kolom voor bericht -->
            </tr>
        </thead>
        <tbody>";

// Haal gegevens op uit de bezoekers tabel
$sql = "SELECT naam, email FROM bezoekers"; // SQL-query om naam en e-mail van bezoekers op te halen
$result = $conn->query($sql); // Voer de query uit en sla het resultaat op

// Controleer of er resultaten zijn
if ($result->num_rows > 0) {
    // Loop door de resultaten en voeg elke rij toe aan de HTML-tabel
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['naam']) . "</td> <!-- Weergave van de naam -->
                <td>" . htmlspecialchars($row['email']) . "</td> <!-- Weergave van de e-mail -->
              </tr>";
    }
}

// Haal gegevens op uit de contact tabel
$sql = "SELECT naam, email, bericht FROM contact"; // SQL-query om naam, e-mail en bericht van contactpersonen op te halen
$result = $conn->query($sql); // Voer de query uit en sla het resultaat op

// Controleer of er resultaten zijn
if ($result->num_rows > 0) {
    // Loop door de resultaten en voeg elke rij toe aan de HTML-tabel
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['naam']) . "</td> <!-- Weergave van de naam -->
                <td>" . htmlspecialchars($row['email']) . "</td> <!-- Weergave van de e-mail -->
                <td>" . htmlspecialchars($row['bericht']) . "</td> <!-- Weergave van het bericht -->
              </tr>";
    }
}

// Sluit de HTML-tabel af
echo "</tbody>
    </table>";

// Sluit de databaseverbinding
$conn->close(); // Verbreek de verbinding met de database
exit; // Stop verdere uitvoering van het script
?>
