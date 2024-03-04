<?php
// Codice per la registrazione dell'utente...
// Dopo che l'utente si è registrato con successo e i dati sono stati inseriti nel database...

// Verifica se l'utente è un amministratore
$isAdmin = false; // Assumiamo che tu abbia un modo per determinare se l'utente è un amministratore
if ($isAdmin) {
    // Se l'utente è un amministratore, reindirizzalo alla sua pagina privata
    header("Location: admin.php");
    exit();
}

// Assicurati che l'utente sia loggato
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Creazione della pagina personalizzata per l'utente
$nomeUtente = $_POST['username']; // Assumendo che il nome utente sia stato fornito durante la registrazione
$emailUtente = $_POST['email']; // Assumendo che l'email sia stata fornita durante la registrazione


// Creazione del nome del file per la pagina personalizzata dell'utente
$nomeFile = strtolower(str_replace(' ', '_', $nomeUtente)) . ".php";


// Reindirizzamento dell'utente alla propria pagina personale
header("Location: $nomeFile");
exit();


?>


<?php
session_start();

// Verifica se l'utente è loggato, altrimenti reindirizzalo alla pagina di login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Recupera i dati dell'utente (sostituisci questi dati con quelli reali)
$username = $_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?></h2>

    <h3>Your Profile</h3>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <tr>
            <td><?php echo $username; ?></td>
            <td><?php echo $email; ?></td>
        </tr>
    </table>
</body>
</html>
