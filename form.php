<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"> <!-- Stel de karaktercodering in op UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Zorgt voor responsief ontwerp -->
    <title>Formulier</title> <!-- Titel van de webpagina -->
    <link rel="stylesheet" href="../css/form.css"> <!-- Koppel het CSS-bestand voor styling van het formulier (controleer of het pad correct is) -->

    <?php
    // Databaseverbinding
    $servername = "localhost"; // Database servernaam
    $username = "root"; // Database gebruikersnaam
    $password = "root"; // Database wachtwoord
    $dbname = "gegevensverzameling"; // Database naam

    $conn = null; // Initialiseer de verbindingsvariabele

    try {
        // Maak verbinding met de database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Controleer de verbinding
        if ($conn->connect_error) {
            throw new Exception("Verbinding mislukt: " . $conn->connect_error); // Gooi een uitzondering bij verbindingsfout
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Controleer of het formulier is verzonden
            if (isset($_POST['naam']) && isset($_POST['email']) && !empty($_POST['naam']) && !empty($_POST['email'])) {
                
                // Beveilig de invoer met htmlspecialchars om XSS-aanvallen te voorkomen
                $naam = htmlspecialchars($_POST['naam'], ENT_QUOTES, 'UTF-8');
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

                // SQL-query om de gegevens op te slaan
                $sql = "INSERT INTO bezoekers (naam, email) VALUES (?, ?)";
                $stmt = $conn->prepare($sql); // Bereid de SQL-query voor

                if (!$stmt) {
                    throw new Exception("Fout bij voorbereiden van de query: " . $conn->error); // Gooi een uitzondering als de query niet kan worden voorbereid
                }

                // Beveilig de query door gebruik te maken van prepared statements
                $stmt->bind_param("ss", $naam, $email);

                if ($stmt->execute()) { // Voer de query uit
                    $message = "Bedankt voor uw bericht! We nemen zo snel mogelijk contact met u op.";
                } else {
                    throw new Exception("Fout bij uitvoeren van de query: " . $stmt->error); // Gooi een uitzondering bij een fout in de query-uitvoering
                }

                $stmt->close(); // Sluit de prepared statement
            } else {
                throw new Exception("Vul alle velden in!"); // Gooi een uitzondering als niet alle velden zijn ingevuld
            }
        }
    } catch (Exception $e) {
        $message = $e->getMessage(); // Bewaar de foutmelding in een variabele
    } finally {
        if ($conn instanceof mysqli) {
            $conn->close(); // Sluit de databaseverbinding
        }
    }
    ?>
    <link rel="stylesheet" href="../css/modal.css"> <!-- Voeg een link toe naar de CSS voor de modal (controleer of het pad correct is) -->
</head>
<body>
    <!-- Navigatiebalk -->
    <div class="navbar">
        <a href="../index.html">Home</a> <!-- Link naar de homepagina -->
        <a href="form.php" class="active">Form</a> <!-- Link naar de formulierpagina, gemarkeerd als actief -->
        <a href="contact.php">Contact</a> <!-- Link naar de contactpagina -->
        <a href="gegevens.php">Gegevens</a> <!-- Link naar de gegevenspagina -->
    </div>

    <!-- Formuliercontainer -->
    <div class="contact-container">
        <div class="logo-container">
            <img src="../afb/logo.webp" alt="Logo" class="logo"> <!-- Logo afbeelding -->
        </div>
        <div class="form-container">
            <h2 class="text-center mb-4">Meld je aan voor onze nieuwsbrief en mis geen enkele aanbieding!</h2> <!-- Koptekst voor het formulier -->
            <form id="contactForm" method="POST" action="form.php"> <!-- Begin van het formulier -->
                <input type="text" id="naam" name="naam" placeholder="Voer uw naam in" required> <!-- Naamveld -->
                <input type="email" id="email" name="email" placeholder="Voer uw e-mail in" required> <!-- E-mailveld -->
                <button type="submit">Verzenden</button> <!-- Verzendknop -->
            </form>
        </div>
    </div>

    <!-- De modale pop-up -->
    <div id="myModal" class="modal"> <!-- Begin van de modal -->
        <div class="modal-content"> <!-- Inhoud van de modal -->
            <span class="close">&times;</span> <!-- Sluitknop voor de modal -->
            <p id="modalMessage" class="modal-message"></p> <!-- Element voor de modal boodschap -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => { // Wacht tot de DOM is geladen
            <?php if (!empty($message)): ?>
                var modal = document.getElementById("myModal"); // Verkrijg de modale container
                var span = document.getElementsByClassName("close")[0]; // Verkrijg de sluitknop
                var modalMessage = document.getElementById("modalMessage"); // Verkrijg het element voor de modal boodschap

                modal.style.display = "block"; // Maak de modal zichtbaar
                modalMessage.textContent = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>"; // Zet de boodschap in de modal

                span.onclick = function() {
                    modal.style.display = "none"; // Verberg de modal bij klikken op de sluitknop
                }

                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none"; // Verberg de modal bij klikken buiten de modal
                    }
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>
