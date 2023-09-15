<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST["ime"];
    $email = $_POST["email"];
    $poruka = $_POST["poruka"];

    // Čuvam podatke u JSON fajlu
    $json_file = 'forma.json';

    $json_data = array(
        "Ime" => $ime,
        "Email" => $email,
        "Poruka" => $poruka
    );

    // Pretvaranje trenutnog sadržaja JSON fajla u niz (ako postoji)
    $existing_data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : array();

    // Dodavanje novih podataka u niz
    $existing_data[] = $json_data;

    // Pretvaranje niza u JSON format i čuvam ga u fajlu
    file_put_contents($json_file, json_encode($existing_data));

    // Povezivanje na MySQL bazu podataka
    $mysqli = new mysqli("localhost", "root", "", "for");

    // Provera konekcije
    if ($mysqli->connect_error) {
        die("Greška u konekciji sa bazom: " . $mysqli->connect_error);
    }

    // Priprema SQL upita za unos podataka u bazu
    $sql = "INSERT INTO kontakt_forma (ime, email, poruka) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    // Postavljanje parametara
    $stmt->bind_param("sss", $ime, $email, $poruka);

    // Izvršavanje upita
    if ($stmt->execute()) {
        echo "Vaša poruka je uspešno poslata i sačuvana u JSON fajlu i u bazi podataka.";
    } else {
        echo "Došlo je do greške prilikom unosa podataka: " . $stmt->error;
    }

    // Zatvaranje konekcije
    $stmt->close();
    $mysqli->close();
}
?>