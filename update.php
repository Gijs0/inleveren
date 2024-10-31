<?php
// Verbinding met de database maken
$servername = "localhost"; // Naam van de server
$username = "root"; // Gebruikersnaam voor de database
$password = "root"; // Wachtwoord voor de database
$dbname = "gegevensverzameling"; // Naam van de database

// Maak een nieuwe verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer of de verbinding is geslaagd
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error); // Stop als de verbinding mislukt
}

// Controleer of het verzoek via een POST-methode komt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // Haal het id op uit het formulier
    $table = $_POST['table']; // Haal de tabelnaam op uit het formulier
    $naam = $_POST['naam']; // Haal de naam op uit het formulier
    $email = $_POST['email']; // Haal het e-mailadres op uit het formulier

    // Controleer of de gegevens van de "bezoekers"-tabel komen
    if ($table == "bezoekers") {
        $sql = "UPDATE bezoekers SET naam=?, email=? WHERE id=?"; // SQL-query om gegevens bij te werken voor bezoekers
    } else {
        $bericht = $_POST['bericht']; // Haal het bericht op als het formulier voor "contact" is
        $sql = "UPDATE contact SET naam=?, email=?, bericht=? WHERE id=?"; // SQL-query om gegevens bij te werken voor contact
    }

    // Bereid de SQL-statement voor
    $stmt = $conn->prepare($sql);

    // Bind de parameters voor de "bezoekers"-tabel
    if ($table == "bezoekers") {
        $stmt->bind_param("ssi", $naam, $email, $id); // Bind de naam, e-mail, en id aan de query voor bezoekers
    } else {
        // Bind de parameters voor de "contact"-tabel
        $stmt->bind_param("sssi", $naam, $email, $bericht, $id); // Bind de naam, e-mail, bericht, en id aan de query voor contact
    }

    // Voer de query uit
    $stmt->execute();

    // Controleer of er rijen zijn bijgewerkt
    if ($stmt->affected_rows > 0) {
        // Bijwerken succesvol, redirect naar gegevens.php
        header("Location: gegevens.php"); // Stuur de gebruiker terug naar de gegevenspagina
        exit(); // Stop verdere scriptuitvoering
    } else {
        echo "Fout bij het bijwerken van gegevens."; // Geef een foutmelding als het bijwerken mislukt
    }

    $stmt->close(); // Sluit de statement
} else {
    // Haal het id en de tabelnaam op via de GET-methode (bij laden van de update-pagina)
    $id = $_GET['id'];
    $table = $_GET['table'];

    // Controleer welke tabel moet worden opgehaald
    if ($table == "bezoekers") {
        $sql = "SELECT naam, email FROM bezoekers WHERE id=?"; // SQL-query om gegevens van bezoekers op te halen
    } else {
        $sql = "SELECT naam, email, bericht FROM contact WHERE id=?"; // SQL-query om gegevens van contact op te halen
    }

    // Bereid de SQL-statement voor
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind het id aan de query
    $stmt->execute(); // Voer de query uit

    // Bind de resultaten afhankelijk van de tabel
    if ($table == "bezoekers") {
        $stmt->bind_result($naam, $email); // Bind naam en e-mail aan variabelen voor bezoekers
    } else {
        $stmt->bind_result($naam, $email, $bericht); // Bind naam, e-mail, en bericht aan variabelen voor contact
    }

    $stmt->fetch(); // Haal de resultaten op
    $stmt->close(); // Sluit de statement
}

$conn->close(); // Sluit de databaseverbinding
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"> <!-- Instellen van tekencodering -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Voor een responsief ontwerp -->
    <title>Choco Coco BBC</title> <!-- Titel van de pagina -->
    <link rel="stylesheet" href="../css/update.css"> <!-- Koppelen van de externe CSS-stylesheet -->
</head>
<body>
    <div class="navbar">
        <a href="../index.html">Home</a> <!-- Link naar de homepagina -->
        <a href="form.php">Form</a> <!-- Link naar de formulierpagina -->
        <a href="contact.php">Contact</a> <!-- Link naar de contactpagina -->
        <a href="gegevens.php" class="active">Gegevens</a> <!-- Link naar de gegevenspagina, markeer als actief -->
    </div>

    <div class="update-container">
        <h2>Update Gegevens</h2> <!-- Titel van de update-sectie -->
        <form method="post"> <!-- Formulier om gegevens bij te werken -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"> <!-- Verberg het id in het formulier -->
            <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>"> <!-- Verberg de tabelnaam in het formulier -->
            Naam: <input type="text" name="naam" value="<?php echo htmlspecialchars($naam); ?>"><br> <!-- Invoerveld voor de naam -->
            E-mail: <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"><br> <!-- Invoerveld voor e-mail -->
            <?php if ($table == "contact"): ?> <!-- Controleer of het formulier voor de "contact"-tabel is -->
                Bericht: <textarea name="bericht"><?php echo htmlspecialchars($bericht); ?></textarea><br> <!-- Tekstvak voor het bericht -->
            <?php endif; ?>
            <input type="submit" value="Bijwerken"> <!-- Verzendknop voor het formulier -->
        </form>
    </div>
</body>
</html>
