<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Si se desea, destruir la sesión
session_destroy();

// Redirigir al inicio de sesión
header("Location: /Equipo_PreguntasRepuestas//login.php");
exit();
?>
