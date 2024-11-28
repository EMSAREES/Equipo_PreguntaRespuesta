<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/Equipo_PreguntasRepuestas/modelo/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave = $_POST['clave'];
    $error = '';

    // Si el botón 'Alumno' fue presionado La contraseña es la Matricula
    if (isset($_POST['alumno'])) {
        $sql = "SELECT Id_Usu, Nombre_Usu, Matricula_Usu FROM Usuario WHERE Matricula_Usu = :clave";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Login exitoso de Alumno
            $_SESSION['user_id'] = $result['Id_Usu'];
            $_SESSION['user_type'] = 'alumno'; // Guardamos el tipo de usuario
            header("Location: ../preguntas.php"); // Redirigir al formulario de alumno
            exit();
        } else {
            $error = "Matricula es incorrecta para alumno.";
        }
    }

    // Si el botón 'Expositor' fue presionado La contraseña es la 2 primera letras del nombre y la dos primeras letra del tema
    if (isset($_POST['expositor'])) {
        // Obtener las primeras 2 letras de Nombre_ponente y Tema_ponente
        $nombre_prefix = substr($clave, 0, 2); // Primeras 2 letras de Nombre_ponente
        $tema_prefix = substr($clave, 2, 2); // Segunda parte de 2 letras para Tema_ponente

        // Consulta para verificar el expositor
        $sql = "SELECT id_ponente, Nombre_ponente, Tema_ponente 
                FROM ponentes 
                WHERE Nombre_ponente LIKE CONCAT(:nombre_prefix, '%') 
                  AND Tema_ponente LIKE CONCAT(:tema_prefix, '%')";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_prefix', $nombre_prefix, PDO::PARAM_STR);
        $stmt->bindParam(':tema_prefix', $tema_prefix, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Login exitoso de Expositor
            $_SESSION['id_ponente'] = $result['id_ponente']; // Guardar el ID del ponente
            $_SESSION['tema_ponente'] = $result['Tema_ponente'];
            $_SESSION['user_type'] = 'expositor'; // Guardamos el tipo de usuario
            header("Location: ../ContenedorPregunta.php"); // Redirigir al formulario de expositor
            exit();
        } else {
            $error = "No se encontró un expositor con las letras ingresadas.";
        }
    }

    // Guardar el mensaje de error en la sesión
    $_SESSION['login_error'] = $error;
    header("Location: /Equipo_PreguntasRepuestas/login.php");
    exit();
}
?>

