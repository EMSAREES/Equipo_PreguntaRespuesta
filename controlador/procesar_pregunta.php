<?php

/*// Comprobar si el usuario ha iniciado sesión
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'alumno') {
    $user_id = $_SESSION['user_id'];
    // Aquí puedes usar $user_id como necesites en el formulario
    echo "Bienvenido, tu ID de usuario es: " . $user_id;
} else {
    // Si no está iniciada la sesión o no es alumno, redirigir al login
    header("Location: login.php");
    exit();
}*/


session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'alumno') {
    header("Location: login.php");
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . '/PreguntasRespuestas/modelo/conexion.php');

// Obtener el ID del usuario que ha iniciado sesión
$user_id = $_SESSION['user_id'];

// Obtener el nombre del usuario que ha iniciado sesión
$sql = "SELECT Nombre_Usu FROM Usuario WHERE Id_Usu = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $user_name = $result['Nombre_Usu'];
} else {
    echo "Error: No se pudo obtener el nombre del usuario.";
    exit;
}

// Crear un array con "Anónimo" y el nombre del usuario logueado
$autores = [ $user_name];

// Obtener el nombre de los temas 
$sql = "SELECT Tema_ponente FROM ponentes";
$stmt = $conn->prepare($sql);
$stmt->execute();
$expositores = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Verificar si el formulario fue enviado
if (isset($_POST['btnAceptar'])) {
      // Obtener el autor seleccionado
      $autor = $_POST['autor'];
      // Si el autor es "Anónimo", asignamos el ID del usuario logueado
      if ($autor === 'Anonimo') {
          $autor_id = $user_id;  // Usar el ID real del usuario
          $es_anonimo = 1;        // Establecer EsAnonimo a 1
      } else {
          // Si no es "Anónimo", usar el autor seleccionado
          $sql = "SELECT Id_Usu FROM Usuario WHERE Nombre_Usu = :autor";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':autor', $autor, PDO::PARAM_STR);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          $autor_id = $result['Id_Usu'];
          $es_anonimo = 0;  // No es anónimo
      }
  
      $pregunta = $_POST['Preguntatext'];
      $expositor = $_POST['expositor'];
      $contexto = $_POST['textContexto'] ?? null;
  
      // Validar que se seleccionó un expositor
      if (empty($expositor) || $expositor == "Elige un expositor") {
          echo "Error: Debes seleccionar un expositor válido.";
          exit();
      }
  
      // Obtener la fecha y hora actuales
      $fecha = date('Y-m-d');
      $hora = date('H:i:s');
  
      // Insertar los datos en la tabla tbl_Pregunta
      $sql = "INSERT INTO Pregunta (Id_Autor, id_Tema, Pregunta, Contexto, Hora, Fecha, Estado, EsAnonimo) 
              VALUES (
                  :autor_id, 
                  (SELECT id_ponente FROM ponentes WHERE Tema_ponente = :expositor),
                  :pregunta, :contexto, :hora, :fecha, 1, :es_anonimo)";
  
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':autor_id', $autor_id, PDO::PARAM_INT);
      $stmt->bindParam(':expositor', $expositor, PDO::PARAM_STR);
      $stmt->bindParam(':pregunta', $pregunta, PDO::PARAM_STR);
      $stmt->bindParam(':contexto', $contexto, PDO::PARAM_STR);
      $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
      $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
      $stmt->bindParam(':es_anonimo', $es_anonimo, PDO::PARAM_INT);
  
         // Después de ejecutar la inserción de la pregunta:
         if ($stmt->execute()) {
            // Si la inserción fue exitosa, devolver un mensaje JSON
            echo json_encode(['success' => true]); 
        } else {
            // Si ocurrió un error, devolver un mensaje de error
            echo json_encode(['success' => false, 'message' => 'Error al guardar la pregunta.']);
        }
      /*if ($stmt->execute()) {
          echo "Pregunta guardada correctamente.";
      } else {
          echo "Error al guardar la pregunta: " . $stmt->errorInfo()[2];
      }*/
  
      // Redirigir al usuario a la página principal o de confirmación
      //header("Location: /PreguntasRespuestas/preguntas.php");
      exit();
}

// Manejar el botón de cancelar
if (isset($_POST['btnCancelar'])) {
    header("Location: login.php");
    exit();
}

// Cerrar la conexión
$conn = null;
?>

