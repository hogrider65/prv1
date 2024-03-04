<?php
// Configurazione del database
define('DB_SERVER', 'localhost'); // Host del database
define('DB_USERNAME', 'root'); // Nome utente del database
define('DB_PASSWORD', ''); // Password del database
define('DB_NAME', 'businessv'); // Nome del database

// Connessione al database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica della connessione
if ($conn === false) {
    die("Errore di connessione al database: " . mysqli_connect_error());
}
?>
