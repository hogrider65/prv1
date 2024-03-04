<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <button type="submit" name="login">Login</button>
        </div>
    </form>
</body>
</html>

<?php
// Include il file di configurazione del database
include 'config.php';

// Processa il form di login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepara una query SQL per recuperare le credenziali dell'utente
    $sql = "SELECT id, user, password, role FROM lavori1 WHERE user = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Imposta i parametri
        $param_username = $_POST["username"];

        // Esegui la query
        if (mysqli_stmt_execute($stmt)) {
            // Memorizza il risultato
            mysqli_stmt_store_result($stmt);

            // Verifica se l'username esiste, se sì, verifica la password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Associa le variabili ai risultati della query
                mysqli_stmt_bind_result($stmt, $id, $username, $db_password, $role);
                if (mysqli_stmt_fetch($stmt)) {
                    if ($_POST["password"] === $db_password) {
                        // Avvia la sessione
                        session_start();

                        // Memorizza i dati dell'utente nella sessione
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;

                        // Reindirizza in base al ruolo
                        if ($role === "admin") {
                            header("location: admin.php");
                        } else {
                            header("location: users/" . $username . "/profile.php");
                        }
                    } else {
                        echo "La password non è valida.";
                    }
                }
            } else {
                echo "Nessun account trovato con questo username.";
            }
        } else {
            echo "Qualcosa è andato storto. Riprova più tardi.";
        }

        // Chiudi la dichiarazione
        mysqli_stmt_close($stmt);
    }

    // Chiudi la connessione
    mysqli_close($conn);
}
?>
