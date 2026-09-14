<?php
$special_chars = ".-_";
$allowed_chars = "abcdefghijklmnopqrstuvwxyz0123456789" . $special_chars;

$verify_login = function (string $login) use ($special_chars, $allowed_chars) {
    $is_valid = true;

    if (mb_strlen($login) < 1) $is_valid = false;
    else {
        for ($i = 0; $i < mb_strlen($login); $i++) {
            if (!str_contains($allowed_chars, $login[$i])) $is_valid = false;
        }

        if ($is_valid) {
            if (
                str_contains($special_chars, $login[0])
                || str_contains($special_chars, $login[-1])
            ) $is_valid = false;
            else if (str_contains($login, "---")) $is_valid = false;
            else {
                $login = str_replace("--", "$", $login);
                $lastSpecialCharPos = -1;
                for ($i = 0; $i < mb_strlen($login); $i++) {
                    if (str_contains($special_chars . "$", $login[$i])) {
                        if ($lastSpecialCharPos == $i - 1) {
                            $is_valid = false;
                            break;
                        }
                        $lastSpecialCharPos = $i;
                    }
                }
            }
        }
    }

    return $is_valid;
};

if (isset($_POST["login"]) && !empty($_POST["login"])) {
    $_POST["login"] = mb_strtolower($_POST["login"]);

    if ($verify_login($_POST["login"])) {
        $_POST["original_login"] = $_POST["login"];
        echo "<p style='color: green'>Votre proposition est valide.</p>";
    } else {
        echo "<p style='color: red'>Votre proposition est invalide.</p>";
    }
} else if (
    isset($_POST['firstname']) && !empty($_POST['firstname'])
    && isset($_POST['lastname']) && !empty($_POST['lastname'])
) {
    $login = mb_strtolower(
        mb_substr($_POST['firstname'], 0, 10)
        . "."
        . mb_substr($_POST['lastname'], 0, 10)
    );

    $login = iconv('UTF-8', 'ASCII//TRANSLIT', $login);

    for ($i = 0; $i < mb_strlen($login); $i++) {
        if (!str_contains($allowed_chars, $login[$i])) $login[$i] = '_';
    }

    $firstValidChar = 0;
    $lastValidChar = 100; // Plus que les 21 caractères max. pouvant être reçus à ce stade
    for ($i = 0; $i < mb_strlen($login); $i++) {
        if (str_contains($special_chars, $login[$i])) {
            if ($i == $firstValidChar) $firstValidChar = $i + 1;
        }
        else $lastValidChar = $i;
    }
    $login = mb_substr($login, $firstValidChar, $lastValidChar - $firstValidChar + 1);

    $login = str_replace("--", "$", $login);

    $lastSpecialCharPos = -1;
    for ($i = 0; $i < mb_strlen($login); $i++) {
        if (str_contains($special_chars . "$", $login[$i])) {
            if ($lastSpecialCharPos == $i - 1) {
                // On ne garde que :
                $login =
                    // les caractères de l'index 0 à actuel exclu (0 à $i)
                    mb_substr($login, 0, $i)
                    // + ceux au delà de l'actuel ($i+1)
                    . mb_substr($login, $i + 1);
                $i--; // On revient d'un cran en arrière puis la chaîne a été raccourcie
            }
            $lastSpecialCharPos = $i;
        }
    }

    $login = str_replace("$", "--", $login);

    $_POST["login"] = $login;

    $_POST["original_login"] = $_POST["login"];
} else {
    header('Location: index.php?e=1');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pj_NomCompte - Login généré</title>
</head>

<body>
    <form action="final.php" id="form" method="post">
        <input type="text" name="firstname" id="firstname" style="display: none" required
            value="<?php echo isset($_POST['firstname']) && !empty($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
        <input type="text" name="lastname" id="lastname" style="display: none" required
            value="<?php echo isset($_POST['lastname']) && !empty($_POST['lastname']) ? $_POST['lastname'] : ''; ?>">
        <input type="text" name="original_login" id="original_login" style="display: none" required
            value="<?php echo $_POST['original_login']; ?>">

        <p><b>Votre nom de compte : </b><?php echo isset($_POST['original_login']) && !empty($_POST['original_login']) ? $_POST['original_login'] : ''; ?></p>
        <input type="submit" value="Accepter">
    </form>
    
    <form action="choose.php" id="form" method="post">
        <input type="text" name="firstname" id="firstname" style="display: none" required
            value="<?php echo isset($_POST['firstname']) && !empty($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
        <input type="text" name="lastname" id="lastname" style="display: none" required
            value="<?php echo isset($_POST['lastname']) && !empty($_POST['lastname']) ? $_POST['lastname'] : ''; ?>">
        <input type="text" name="original_login" id="original_login" style="display: none" required
            value="<?php echo $_POST['original_login']; ?>">

        <label for="login">Nouvelle proposition :</label>
        <input type="text" name="login" id="login" required
            value="<?php echo isset($_POST['login']) && !empty($_POST['login']) ? $_POST['login'] : ''; ?>">
        <br />
        <input type="submit" value="Vérifier">
    </form>
</body>

</html>