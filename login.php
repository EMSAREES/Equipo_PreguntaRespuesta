<?php
session_start();
$error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']); // Limpiar el mensaje de error después de mostrarlo

// Obtener la fecha actual en el formato deseado (DD/MM/AAAA)
$fecha_actual = date('d/m/Y');
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Login</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Equipo_PreguntasRepuestas/css/login.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="form-container">
        <div class="header text-center">
            <h2>Inicio</h2>
        </div>

        <form method="POST" action="./controlador/procesar_login.php">
            <div class="d-grid gap-2 mt-3">
                <button type="submit" name="alumno" value="alumno" class="btn alumno">Alumno</button>
            </div>
            <hr>
            <div class="d-grid gap-2 mb-3">
                <button type="submit" name="expositor" class="btn expositor">Expositor</button>
            </div>
            <div class="mb-4"> <input type="password" name="clave" class="form-control clave" placeholder="Clave... " required  disabled>
           
            <div class="alert alert-danger mt-2" style="display: <?= empty($error) ? 'none' : 'block' ?>" id="error-message"><?= $error ?></div>
            </div>
        </form>
        <p class="date"><?= $fecha_actual ?></p> <!-- Mostrar la fecha actual -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const claveInput = document.querySelector('.clave');
            const alumnoButton = document.querySelector('.alumno');
            const expositorButton = document.querySelector('.expositor');

            // Función para habilitar el campo de clave
            function habilitarClave() {
                claveInput.disabled = false;
                claveInput.focus(); // Enfocar el campo
            }

            // Agregar eventos a los botones para habilitar el campo de clave
            alumnoButton.addEventListener('click', habilitarClave);
            expositorButton.addEventListener('click', habilitarClave);

            // Desbloquear el campo al pasar el mouse sobre él
            claveInput.addEventListener('mouseenter', habilitarClave);

            // Asegurarse de que el campo de clave se desbloquee si se hace clic en él
            claveInput.addEventListener('click', function () {
                if (claveInput.disabled) {
                    habilitarClave();
                }
            });
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
