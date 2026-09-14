<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pj_NomCompte</title>
</head>
<body>
    <?php if (isset($_GET["e"]) && !empty($_GET["e"])) echo "<p style='color: red'>Le prénom et le nom sont requis.</p>"; ?>
    <form action="choose.php" method="post">
        <label for="firstname">Prénom :</label>
        <input type="text" name="firstname" id="firstname" required>
        <br/>
        <label for="lastname">Nom :</label>
        <input type="text" name="lastname" id="lastname" required>
        <br/>
        <input type="submit" value="Générer un nom de compte">
    </form>
</body>
</html>