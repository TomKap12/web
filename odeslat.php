<?php
// Spustíme zpracování pouze pokud byl formulář odeslán metodou POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Načteme data z formuláře a ošetříme je proti škodlivému kódu
    $jmeno = htmlspecialchars(trim($_POST['jméno']));
    $telefon = htmlspecialchars(trim($_POST['telefon']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $zprava = htmlspecialchars(trim($_POST['zpráva']));

    // Zkontrolujeme, zda jsou vyplněné povinné údaje
    if (empty($jmeno) || empty($email) || empty($zprava) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Pokud něco chybí, vrátíme uživatele zpět s chybou
        echo "Chyba: Prosím vyplňte všechny povinné údaje a zadejte platný e-mail.";
        exit;
    }

    // Kam se má poptávka odeslat (zadejte váš e-mail)
    $cilovy_email = "tomas.kapera@seznam.cz";

    // Předmět e-mailu, který vám přijde do schránky
    $predmet = "Nová poptávka z webu (Brány a ploty) od: " . $jmeno;

    // Sestavení textu zprávy
    $obsah_zpravy = "Někdo vám odeslal poptávku přes kontaktní formulář na webu:\n\n";
    $obsah_zpravy .= "Jméno a příjmení: " . $jmeno . "\n";
    $obsah_zpravy .= "Telefon: " . ($telefon ? $telefon : "Neuveden") . "\n";
    $obsah_zpravy .= "E-mail: " . $email . "\n\n";
    $obsah_zpravy .= "Zpráva:\n" . $zprava . "\n";

    // Nastavení hlaviček e-mailu (aby se jako odesílatel tvářil přímo zákazník a šlo na něj rovnou odpovědět)
    $hlavicky = "From: " . $email . "\r\n" .
                "Reply-To: " . $email . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

    // Odeslání e-mailu
    if (mail($cilovy_email, $predmet, $obsah_zpravy, $hlavicky)) {
        // Po úspěšném odeslání přesměrujeme zákazníka na děkovnou stránku (nebo zpět s hláškou)
        // Můžete vytvořit soubor dekuji.html a upravit cestu níže:
        echo "<script>alert('Děkujeme, vaše poptávka byla úspěšně odeslána.'); window.location.href='index.html';</script>";
    } else {
        echo "Odeslání zprávy se bohužel nezdařilo. Zkuste nás kontaktovat přímo e-mailem.";
    }
} else {
    // Pokud někdo přistoupí na soubor přímo bez odeslání formuláře
    header("Location: index.html");
    exit;
}
?>