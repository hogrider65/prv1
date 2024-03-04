<?php
// Verifica se l'utente è loggato
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
</head>
<body>
    <h2>User Panel</h2>
    <?php include "users/" . $_SESSION["username"] . "/profile.php"; ?>
</body>
</html>


