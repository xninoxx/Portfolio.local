<?php
session_start();

if(isset($_SESSION["SENT"])){
    header("Location: contact.html");
    exit; // dit is blijkbaar NODIG, AL ZOU HEADER HET MOETEN REDIRECTEN EN STOPPEN, MAAR DAT DOET HIJ NIET.
    // WANT FUCK MIJ EN MIJN LEVEN. - Peter
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['website'])) {
        header("Location: contact.html?status=succes");
        exit;
    }

    $naam    = htmlspecialchars(trim($_POST['flname'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $bericht = htmlspecialchars(trim($_POST['vraag'] ?? ''));

    if (!empty($naam) && !empty($email) && !empty($bericht) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mijn_strato_email = "info@ninowolfsen.nl";

        $to          = $mijn_strato_email;
        $subject     = "Nieuw contactbericht van: " . $naam;

        $email_body  = "Je hebt een nieuw bericht ontvangen via je contactformulier:\n\n";
        $email_body .= "Naam: " . $naam . "\n";
        $email_body .= "E-mail: " . $email . "\n\n";
        $email_body .= "Bericht:\n" . $bericht . "\n";

        $headers  = "From: " . $mijn_strato_email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $email_body, $headers)) {
            $_SESSION["SENT"] = 1;
            header("Location: contact.html?status=succes");
            exit;
        } else {
            echo "Er is iets misgegaan bij het verzenden. Probeer het later opnieuw.";
        }
    } else {
        echo "Vul a.u.b. alle velden correct in.";
    }
} else {
    header("Location: contact.html");
    exit;
}