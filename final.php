<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pj_NomCompte - Récapitulatif</title>
</head>
<body>
    <?php
    if (isset($_POST["original_login"]) && !empty($_POST["original_login"])) $_POST["login"] = $_POST["original_login"];
    ?>
    <p><b>Prénom : </b><?php echo isset($_POST['firstname']) && !empty($_POST['firstname']) ? $_POST['firstname'] : ''; ?></p>
    <p><b>Nom : </b><?php echo isset($_POST['lastname']) && !empty($_POST['lastname']) ? $_POST['lastname'] : ''; ?></p>
    <p><b>Login : </b><?php echo isset($_POST['login']) && !empty($_POST['login']) ? $_POST['login'] : ''; ?></p>
</body>
</html>