<?php
// Laad de autoloader voor de Dompdf bibliotheek
require '../vendor/autoload.php';

// Gebruik de Dompdf namespace
use Dompdf\Dompdf;

// Maak een nieuw Dompdf object aan
$dompdf = new Dompdf();

// Verbind met de database
$servername = "localhost"; // Servernaam
$username = "root"; // Database gebruikersnaam
$password = "root"; // Database wachtwoord
$dbname = "gegevensverzameling"; // Database naam

// Maak een nieuwe verbinding met de MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer of de verbinding succesvol is
if ($conn->connect_error) {
    // Stop het script en geef een foutmelding als de verbinding mislukt
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Bouw de HTML-inhoud voor de PDF
$html = '<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"> <!-- Charset instellen op UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>Opgeslagen Gegevens</title> <!-- Titel van de PDF -->
    <style>
        body {
            font-family: Arial, sans-serif; // Lettertype voor de body
            margin: 20px; // Marges rondom de body
        }
        h1, h2, h3 {
            text-align: center; // Centreer de koppen
        }
        .data-table {
            width: 100%; // Tabel breedte op 100%
            border-collapse: collapse; // Vermijd dubbele randen
            margin-bottom: 20px; // Onder marge voor de tabel
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd; // Grijze rand voor cellen
            padding: 8px; // Padding in cellen
            text-align: left; // Align tekst naar links
        }
        .data-table th {
            background-color: #f2f2f2; // Achtergrondkleur voor header
        }
    </style>
</head>
<body>';

// Voeg een titel en subtitel toe aan de HTML
$html .= '<h1>Opgeslagen Gegevens</h1>';
$html .= '<h2>Nieuwsbrief Aanmeldingen</h2>';

// Bouw de tabel voor nieuwsbrief aanmeldingen
$html .= '<table class="data-table">
            <thead>
                <tr>
                    <th>Naam</th> <!-- Kolom voor naam -->
                    <th>E-mail</th> <!-- Kolom voor e-mail -->
                </tr>
            </thead>
            <tbody>';

// Probeer de gegevens op te halen voor de nieuwsbrief aanmeldingen
try {
    $sql = "SELECT naam, email FROM bezoekers"; // SQL-query om naam en email van bezoekers op te halen
    $result = $conn->query($sql); // Voer de query uit en sla het resultaat op

    // Controleer of er resultaten zijn
    if ($result->num_rows > 0) {
        // Loop door de resultaten en voeg elke rij toe aan de HTML
        while ($row = $result->fetch_assoc()) {
            $html .= "<tr>
                        <td>" . htmlspecialchars($row['naam']) . "</td> <!-- Naam weergeven -->
                        <td>" . htmlspecialchars($row['email']) . "</td> <!-- E-mail weergeven -->
                      </tr>";
        }
    } else {
        // Als er geen gegevens zijn, geef een boodschap weer
        $html .= "<tr><td colspan='2'>Geen gegevens gevonden</td></tr>";
    }
} catch (Exception $e) {
    // Als er een fout optreedt, geef een foutmelding weer in de tabel
    $html .= "<tr><td colspan='2'>Fout: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}

// Sluit de tabel voor nieuwsbrief aanmeldingen af
$html .= '</tbody></table>';

// Voeg een nieuwe sectie toe voor contact berichten
$html .= '<h2>Contact Berichten</h2>';
$html .= '<table class="data-table">
            <thead>
                <tr>
                    <th>Naam</th> <!-- Kolom voor naam -->
                    <th>E-mail</th> <!-- Kolom voor e-mail -->
                    <th>Bericht</th> <!-- Kolom voor bericht -->
                </tr>
            </thead>
            <tbody>';

// Probeer de gegevens op te halen voor de contactberichten
try {
    $sql = "SELECT naam, email, bericht FROM contact"; // SQL-query om naam, email en bericht van contact op te halen
    $result = $conn->query($sql); // Voer de query uit en sla het resultaat op

    // Controleer of er resultaten zijn
    if ($result->num_rows > 0) {
        // Loop door de resultaten en voeg elke rij toe aan de HTML
        while ($row = $result->fetch_assoc()) {
            $html .= "<tr>
                        <td>" . htmlspecialchars($row['naam']) . "</td> <!-- Naam weergeven -->
                        <td>" . htmlspecialchars($row['email']) . "</td> <!-- E-mail weergeven -->
                        <td>" . htmlspecialchars($row['bericht']) . "</td> <!-- Bericht weergeven -->
                      </tr>";
        }
    } else {
        // Als er geen gegevens zijn, geef een boodschap weer
        $html .= "<tr><td colspan='3'>Geen gegevens gevonden</td></tr>";
    }
} catch (Exception $e) {
    // Als er een fout optreedt, geef een foutmelding weer in de tabel
    $html .= "<tr><td colspan='3'>Fout: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}

// Sluit de tabel voor contactberichten af
$html .= '</tbody></table>';
$html .= '</body></html>'; // Sluit de HTML-pagina af

// Laad de HTML-inhoud in Dompdf
$dompdf->loadHtml($html);

// Stel het papierformaat in op A4 in portrait
$dompdf->setPaper('A4', 'portrait');

// Genereer de PDF vanuit de HTML-inhoud
$dompdf->render();

// Geef de PDF weer in de browser; als je "Attachment" op true zet, wordt het gedownload
$dompdf->stream("gegevens_overzicht.pdf", ["Attachment" => false]);

// Sluit de databaseverbinding
$conn->close();
?>
