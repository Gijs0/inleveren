<?php
// Database-informatie instellen: servernaam, gebruikersnaam, wachtwoord en databasenaam
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "gegevensverzameling";

// Maak verbinding met de MySQL-database met behulp van de bovengenoemde instellingen
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer of de verbinding is gelukt
if ($conn->connect_error) {
    // Als de verbinding mislukt, stop het script en toon een foutmelding
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Haal de 'id' en 'table' waarden op uit de URL-parameters
$id = $_GET['id'];
$table = $_GET['table'];

// Controleer welke tabel gebruikt moet worden: "bezoekers" of "contact"
if ($table == "bezoekers") {
    // SQL-query om een record te verwijderen uit de tabel 'bezoekers' op basis van de 'id'
    $sql = "DELETE FROM bezoekers WHERE id=?";
} else {
    // SQL-query om een record te verwijderen uit de tabel 'contact' op basis van de 'id'
    $sql = "DELETE FROM contact WHERE id=?";
}

// Bereid de SQL-query voor
$stmt = $conn->prepare($sql);

// Koppel de 'id' parameter aan de SQL-query (het type is "i" voor integer)
$stmt->bind_param("i", $id);

// Voer de SQL-query uit
$stmt->execute();

// Controleer of er records zijn verwijderd
if ($stmt->affected_rows > 0) {
    // Toon een succesmelding als er een record is verwijderd
    echo "Gegevens succesvol verwijderd.";
} else {
    // Toon een foutmelding als het verwijderen mislukt
    echo "Fout bij het verwijderen van gegevens.";
}

// Sluit de statement en de databaseverbinding
$stmt->close();
$conn->close();

// Redirect de gebruiker terug naar de gegevenspagina na het verwijderen van het record
header("Location: gegevens.php");
exit(); // Zorg ervoor dat het script stopt na de redirect
?>
