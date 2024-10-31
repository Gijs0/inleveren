     <!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"> <!-- Karakterset instellen op UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsieve weergave instellen -->
    <title>Opgeslagen Gegevens</title> <!-- Titel van de pagina -->
    <link rel="stylesheet" href="../css/gegevens.css"> <!-- CSS-bestand koppelen -->
</head>
<body>
    <div class="navbar"> <!-- Navigatiebalk -->
        <a href="../index.html">Home</a> <!-- Link naar de homepagina -->
        <a href="form.php">Form</a> <!-- Link naar de form pagina -->
        <a href="contact.php">Contact</a> <!-- Link naar de contactpagina -->
        <a href="gegevens.php" class="active">Gegevens</a> <!-- Actieve link naar de gegevenspagina -->
    </div>

    <div class="table-container"> <!-- Container voor tabellen -->
        <h1 class="text-center mb-4">Opgeslagen Gegevens</h1> <!-- Titel voor opgeslagen gegevens -->
        <br> 
        <h2 class="text-center mb-4">Back-up</h2> <!-- Titel voor opgeslagen gegevens -->
        <form method="post" style="display: flex; gap: 10px; justify-content: center; margin-bottom: 20px;">
            <button type="submit" name="backup" class="backup-button">Exporteer naar CSV</button>
            <button type="submit" name="export_pdf" class="backup-button" formaction="export_pdf.php">Exporteer naar PDF</button>
            <button type="submit" name="export_xls" class="backup-button" formaction="export_xls.php">Exporteer naar XLS</button>
        </form>


        <h3 class="text-center mb-4">Nieuwsbrief Aanmeldingen</h3> <!-- Titel voor nieuwsbrief aanmeldingen -->
        <table class="data-table"> <!-- Tabel voor bezoekersgegevens -->
            <thead>
                <tr>
                    <th>Naam</th> <!-- Kolom voor naam -->
                    <th>E-mail</th> <!-- Kolom voor e-mail -->
                    <th>Acties</th> <!-- Kolom voor acties (bewerken/verwijderen) -->
                </tr>
            </thead>
            <tbody>
                <?php
                error_reporting(E_ALL); // Foutmeldingen inschakelen
                ini_set('display_errors', 1); // Foutmeldingen weergeven

                $servername = "localhost"; // Naam van de server
                $username = "root"; // Gebruikersnaam voor database
                $password = "root"; // Wachtwoord voor database
                $dbname = "gegevensverzameling"; // Naam van de database

                $conn = new mysqli($servername, $username, $password, $dbname); // Verbinding maken met database

                if ($conn->connect_error) { // Check voor verbindingsfout
                    die("Verbinding mislukt: " . $conn->connect_error); // Verbindingsfout weergeven
                }

                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Error reporting inschakelen voor MySQLi

                try {
                    $sql = "SELECT id, naam, email FROM bezoekers"; // SQL-query om gegevens op te halen
                    $result = $conn->query($sql); // Voer query uit

                    if ($result === false) { // Als query fout is gegaan
                        throw new Exception("Fout bij uitvoeren van de query: " . $conn->error); // Fout weergeven
                    }

                    if ($result->num_rows > 0) { // Als er rijen zijn
                        while ($row = $result->fetch_assoc()) { // Rijen doorlopen
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['naam']) . "</td> <!-- Naam veilig weergeven -->
                                    <td>" . htmlspecialchars($row['email']) . "</td> <!-- E-mail veilig weergeven -->
                                    <td>
                                        <a href='update.php?id=" . $row['id'] . "&table=bezoekers'>Bewerken</a> <!-- Bewerken link -->
                                        <a href='delete.php?id=" . $row['id'] . "&table=bezoekers'>Verwijderen</a> <!-- Verwijderen link -->
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Geen gegevens gevonden</td></tr>"; // Als er geen gegevens zijn
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='3'>Fout: " . htmlspecialchars($e->getMessage()) . "</td></tr>"; // Foutmelding
                }
                ?>
            </tbody>
        </table>

        <h3 class="text-center mb-4">Contact Berichten</h3> <!-- Titel voor contactberichten -->
        <table class="data-table"> <!-- Tabel voor contactgegevens -->
            <thead>
                <tr>
                    <th>Naam</th> <!-- Kolom voor naam -->
                    <th>E-mail</th> <!-- Kolom voor e-mail -->
                    <th>Bericht</th> <!-- Kolom voor bericht -->
                    <th>Acties</th> <!-- Kolom voor acties (bewerken/verwijderen) -->
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $conn = new mysqli($servername, $username, $password, $dbname); // Nieuwe database verbinding maken

                    if ($conn->connect_error) { // Check voor verbindingsfout
                        throw new Exception("Verbinding mislukt: " . $conn->connect_error); // Verbindingsfout weergeven
                    }

                    $sql = "SELECT id, naam, email, bericht FROM contact"; // SQL-query om contactgegevens op te halen
                    $result = $conn->query($sql); // Query uitvoeren

                    if ($result === false) { // Als query fout gaat
                        throw new Exception("Fout bij uitvoeren van de query: " . $conn->error); // Foutmelding weergeven
                    }

                    if ($result->num_rows > 0) { // Als er rijen zijn
                        while ($row = $result->fetch_assoc()) { // Rijen doorlopen
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['naam']) . "</td> <!-- Naam veilig weergeven -->
                                    <td>" . htmlspecialchars($row['email']) . "</td> <!-- E-mail veilig weergeven -->
                                    <td>" . htmlspecialchars($row['bericht']) . "</td> <!-- Bericht veilig weergeven -->
                                    <td>
                                        <a href='update.php?id=" . $row['id'] . "&table=contact'>Bewerken</a> <!-- Bewerken link -->
                                        <a href='delete.php?id=" . $row['id'] . "&table=contact'>Verwijderen</a> <!-- Verwijderen link -->
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Geen gegevens gevonden</td></tr>"; // Geen gegevens gevonden
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='4'>Fout: " . htmlspecialchars($e->getMessage()) . "</td></tr>"; // Foutmelding
                } finally {
                    if ($conn instanceof mysqli) { // Als er een verbinding is
                        $conn->close(); // Sluit verbinding
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    if (isset($_POST['backup'])) { // Als de back-up knop is ingedrukt
        $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.csv'; // Back-up bestandsnaam met tijd
        $file = fopen($backupFile, 'w'); // Open het bestand om te schrijven

        // Back-up van bezoekers
        $conn = new mysqli($servername, $username, $password, $dbname); // Nieuwe database verbinding
        $sql = "SELECT naam, email FROM bezoekers"; // SQL-query voor bezoekers
        $result = $conn->query($sql); // Query uitvoeren
        fputcsv($file, ['Naam', 'Email']); // Schrijf kolomkoppen in CSV
        while ($row = $result->fetch_assoc()) { // Rijen doorlopen
            fputcsv($file, [$row['naam'], $row['email']]); // Schrijf rijen in CSV
        }

        // Back-up van contact
        $sql = "SELECT naam, email, bericht FROM contact"; // SQL-query voor contact
        $result = $conn->query($sql); // Query uitvoeren
        fputcsv($file, []); // Lege regel in CSV
        fputcsv($file, ['Naam', 'Email', 'Bericht']); // Schrijf kolomkoppen in CSV
        while ($row = $result->fetch_assoc()) { // Rijen doorlopen
            fputcsv($file, [$row['naam'], $row['email'], $row['bericht']]); // Schrijf rijen in CSV
        }

        fclose($file); // Sluit het CSV-bestand
        echo "<p>Back-up succesvol opgeslagen als $backupFile.</p>"; // Bevestigingsbericht
    }

    
    ?>
</body>
</html>
