<?php
// Verifica se l'utente è loggato e ha il ruolo di admin
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

// Includi il file di configurazione del database
include 'config.php';

// Esegui una query SQL per ottenere gli utenti con ruolo "utente" dal database
$sql = "SELECT id, user FROM lavori1 WHERE role = 'user'";
$result = mysqli_query($conn, $sql);

// Gestisci il form di selezione dell'utente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["select_user"])) {
    $selected_user_id = $_POST["user_id"];
    
    // Esegui una query SQL per ottenere i dati dell'utente selezionato
    $sql_data = "SELECT nome, nome_progetto, numero_progetto, soldi_pagati, soldi_pattuiti, debito FROM lavori1 WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql_data)) {
        mysqli_stmt_bind_param($stmt, "i", $selected_user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nome, $nome_progetto, $numero_progetto, $soldi_pagati, $soldi_pattuiti, $debito);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Panel</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="user_select">Seleziona utente:</label>
        <select name="user_id" id="user_select">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row["id"]; ?>"><?php echo $row["user"]; ?></option>
            <?php } ?>
        </select>
        <button type="submit" name="select_user">Mostra dati utente</button>
    </form>

    <?php if (isset($selected_user_id)) { ?>
        <h3>Dati utente selezionato:</h3>
        <table>
            <tr>
                <th>Nome</th>
                <th>Nome Progetto</th>
                <th>Numero Progetto</th>
                <th>Soldi Pagati</th>
                <th>Soldi Pattuiti</th>
                <th>Debito</th>
            </tr>
            <tr>
                <td><?php echo $nome; ?></td>
                <td><?php echo $nome_progetto; ?></td>
                <td><?php echo $numero_progetto; ?></td>
                <td><?php echo $soldi_pagati; ?></td>
                <td><?php echo $soldi_pattuiti; ?></td>
                <td><?php echo $debito; ?></td>
            </tr>
        </table>
        <form action="update_data.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $selected_user_id; ?>">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $nome; ?>">
            <!-- Aggiungi altri campi per consentire all'admin di modificare i dati -->
            <button type="submit" name="update_data">Salva modifiche</button>
        </form>
    <?php } ?>
</body>
</html>
