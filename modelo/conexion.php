<?php
/*// Datos de conexión (reemplázalos con tus propios datos)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemajornada";


// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} /*else {
    echo "Conexión exitosa";
}*/


// Datos de conexión
$host = '104.243.47.131';
$dbname = 'cloudarc_SistemaJornada';
$username = 'cloudarc_ISOF';
$password = 'ISOFSEM7';

try {
    // Crear la conexión usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar PDO para manejar errores de forma excepcional y para que los datos se obtengan como asociativos por defecto
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Si la conexión es exitosa, puedes descomentar la siguiente línea para confirmarlo
    // echo "Conexión exitosa!";

} catch (PDOException $e) {
    // Si ocurre un error en la conexión, lo mostramos
    echo "Error de conexión: " . $e->getMessage();
}
?>

