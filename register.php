<?php
include 'config.php';

$username = $password = "";
$username_err = $password_err = "";


// Dopo aver eseguito la registrazione con successo
// Creare una directory per l'utente
$user_directory = $_POST["username"]; // Assumendo che l'username sia univoco
mkdir("users/" . $user_directory);

// Creare un file PHP all'interno della directory dell'utente
$user_file = "users/" . $user_directory . "/profile.php";
$file_content = "<?php\n";
$file_content .= "// Mostra i dati dell'utente\n";
$file_content .= "echo 'Benvenuto, $user_directory!';\n";
// Aggiungere altri dati dell'utente secondo necessità
$file_content .= "?>";
file_put_contents($user_file, $file_content);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controlli sui dati inviati dal form di registrazione
    if (empty(trim($_POST["username"]))) {
        $username_err = "Inserisci un username.";
    } else {
        // Prepara una query SQL per verificare se l'username è già in uso
        $sql = "SELECT id FROM lavori1 WHERE user = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Questo username è già in uso.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Qualcosa è andato storto. Riprova più tardi.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    // Controlla la validità della password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Inserisci una password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "La password deve avere almeno 6 caratteri.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Verifica che non ci siano errori prima di inserire i dati nel database
    if (empty($username_err) && empty($password_err)) {
        // Prepara una query di inserimento SQL
        $sql = "INSERT INTO lavori1 (user, password) VALUES (?, ?)";
         
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            $param_username = $username;
            $param_password = $password;
            
            if (mysqli_stmt_execute($stmt)) {
                // Reindirizza l'utente alla pagina di login dopo la registrazione
                header("location: login.php");
            } else {
                echo "Oops! Qualcosa è andato storto. Riprova più tardi.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    // Chiudi la connessione
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<body>
    <h2>Registrazione</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <span><?php echo $username_err; ?></span>
        </div>    
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <button type="submit">Registrati</button>
        </div>
    </form>
</body>
</html>
