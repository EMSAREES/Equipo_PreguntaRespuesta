<?php
session_start(); // Asegúrate de iniciar la sesión al principio

// Verifica si el usuario ha iniciado sesión como expositor
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'expositor') {
    header("Location: login.php"); // Redirige a la página de inicio de sesión
    exit(); // Asegúrate de que el script se detenga después de la redirección
}


// Supongamos que tienes el tema o ponente guardado en la sesión
$tema_ponente = isset($_SESSION['tema_ponente']) ? $_SESSION['tema_ponente'] : '';


include($_SERVER['DOCUMENT_ROOT'] . '/Equipo_PreguntasRepuestas/modelo/conexion.php');

// Verificar si el formulario fue enviado
if (isset($_POST['btnEye']) && isset($_POST['id_pregunta'])) {
    $idPregunta = $_POST['id_pregunta'];

    // Actualizar el estado de la pregunta con el ID proporcionado
    $sqlUpdate = "UPDATE Pregunta SET Estado = 2 WHERE Id_Pregunta = :idPregunta"; // Usamos :idPregunta como marcador de posición

    // Preparamos la consulta
    $stmt = $conn->prepare($sqlUpdate);

    // Vinculamos el parámetro con el nombre :idPregunta
    $stmt->bindValue(':idPregunta', $idPregunta, PDO::PARAM_INT); // Aquí pasamos el valor correctamente

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        echo "<p>Estado actualizado correctamente.</p>";
    } else {
        echo "<p>Error al actualizar el estado.</p>";
    }

    // Recargar la página para mostrar el cambio de estado
  //  header("Refresh:0");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pregunta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--     No borrar   -->
    <link rel="stylesheet" href="css/contenedorpregunta.css">

</head>
<body>

<!--------------------------------------Parte de Ricardo--------------------------------->

<div class="search-container">
    <!-- Flecha hacia atrás -->
    <div class="d-flex align-items-center mb-3">
        <button class="btn btn-light me-2" aria-label="Regresar" onclick="logout();">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 12l14 0" />
                    <path d="M5 12l4 4" />
                    <path d="M5 12l4 -4" />
                </svg>
        </button>
        <h1 class="ms-3">Preguntas</h1>
    </div>

    <!-- Barra de búsqueda -->
    <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-list"></i></span>
        <input type="text" class="form-control search-input" id="tag-input" placeholder="Añadir nueva etiqueta">
    </div>

    <!-- Botones para añadir etiquetas o buscar -->
    <div class="mb-3">
        <button class="btn btn-primary" id="add-tag-btn">Añadir palabra clave/etiqueta</button>
        <button class="btn btn-success" id="search-btn">Buscar</button>
    </div>
    

    <div class="tag-container d-flex gap-2 flex-wrap" id="tag-container">
        <!-- Aquí se agregan las etiquetas dinámicamente -->       
    </div>
  
</div>

<!-- JavaScript -->
<script>
let tags = [];

// Agrega el tema o ponente del usuario al array de etiquetas
const tema_ponente = "<?php echo htmlspecialchars($tema_ponente); ?>"; // Obtiene el tema o ponente de PHP

if (tema_ponente) {
    tags.push(tema_ponente); // Añade el tema o ponente a las etiquetas
    addTagToContainer(tema_ponente); // Muestra la etiqueta en el contenedor
}

console.log("Etiquetas iniciales:", tags);

// Añadir etiquetas al hacer clic en "Añadir palabra clave/etiqueta"
document.getElementById('add-tag-btn').addEventListener('click', function() {
    console.log("Botón 'Añadir palabra clave/etiqueta' clicado");
    const input = document.getElementById('tag-input');
    const tagValue = input.value.trim();

    console.log("Valor del campo de entrada:", tagValue);
    
    // Verifica si el campo no está vacío y si la etiqueta no existe ya
    if (tagValue && !tags.includes(tagValue)) {
        tags.push(tagValue);
        console.log("Etiqueta añadida:", tagValue);
        console.log("Lista de etiquetas actualizada:", tags);
        addTagToContainer(tagValue);
        input.value = '';  // Limpia el campo de entrada después de añadir
    } else {
        console.log("La etiqueta ya existe o el valor está vacío");
    }
});

// Función para añadir una etiqueta al contenedor de etiquetas
function addTagToContainer(tag) {
    const tagContainer = document.getElementById('tag-container');
    const tagElement = document.createElement('div');
    tagElement.classList.add('tag');
    tagElement.innerHTML = `${tag} <i class="bi bi-x" onclick="removeTag(this, '${tag}')"></i>`;
    tagContainer.appendChild(tagElement);
}

// Función para eliminar una etiqueta del contenedor
function removeTag(element, tag) {
    const index = tags.indexOf(tag);
    if (index !== -1) {
        tags.splice(index, 1);
    }
    element.parentElement.remove();
}

// Funcionalidad de búsqueda
document.getElementById('search-btn').addEventListener('click', function() {
    if (tags.length > 0) {
        const queryString = tags.map(tag => `tags[]=${encodeURIComponent(tag)}`).join('&');
        window.location.href = `/Equipo_PreguntasRepuestas/controlador/procesar_busqueda.php?${queryString}`;
    } else {
        alert('Por favor, añade al menos una etiqueta antes de buscar.');
    }
});

// Función para añadir una etiqueta al contenedor de etiquetas
function addTagToContainer(tag) {
    console.log("Añadiendo etiqueta al contenedor:", tag);
    const tagContainer = document.getElementById('tag-container');
    
    // Crear un elemento div para la etiqueta
    const tagElement = document.createElement('div');
    tagElement.classList.add('tag');
    // Añadir el texto de la etiqueta y un icono para eliminarla
    tagElement.innerHTML = `${tag} <i class="bi bi-x" onclick="removeTag(this, '${tag}')"></i>`;
    
    // Añadir la etiqueta al contenedor
    tagContainer.appendChild(tagElement);
    console.log("Etiqueta añadida al contenedor con éxito");
}

// Función para eliminar una etiqueta del contenedor
function removeTag(element, tag) {
    console.log("Eliminando etiqueta:", tag);
    // Encuentra el índice de la etiqueta en el array y elimínala
    const index = tags.indexOf(tag);
    if (index !== -1) {
        tags.splice(index, 1);
        console.log("Etiqueta eliminada del array:", tag);
        console.log("Lista de etiquetas actualizada:", tags);
    }
    // Elimina el elemento visual de la etiqueta
    element.parentElement.remove();
    console.log("Etiqueta eliminada del contenedor visual");
}

// Funcionalidad de búsqueda y redirección a "procesar_busqueda.php"
document.getElementById('search-btn').addEventListener('click', function() {
    console.log("Botón 'Buscar' clicado");
    if (tags.length > 0) {
        // Construir la cadena de consulta con las etiquetas
        const queryString = tags.map(tag => `tags[]=${encodeURIComponent(tag)}`).join('&');
        console.log("Redirigiendo con la cadena de consulta:", queryString);
        // Redirigir al archivo PHP con las etiquetas como parámetros
        window.location.href = `/Equipo_PreguntasRepuestas/controlador/procesar_busqueda.php?${queryString}`;
    } else {
        // Alerta si no se ha añadido ninguna etiqueta
        console.log("Intento de búsqueda sin etiquetas");
        alert('Por favor, añade al menos una etiqueta antes de buscar.');
    }
});
</script>
<!-------------------------------------------Parte de Adan----------------------------------->
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Equipo_PreguntasRepuestas/modelo/conexion.php');

// Realizamos un JOIN entre la tabla Pregunta y Usuario
$sql = "SELECT p.Id_Pregunta, 
               p.Pregunta, 
               p.Contexto, 
               p.Hora, 
               p.Fecha, 
               u.Nombre_Usu, 
               u.Apellidos_Usu,
               p.Estado,
               p.EsAnonimo
        FROM Pregunta p
        LEFT JOIN Usuario u ON p.Id_Autor = u.Id_Usu";

//$result = $conn->query($sql);
// Preparamos la consulta
$stmt = $conn->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='container mt-4'>"; // Agregamos margen superior
            echo "<div class='card'>"; // Creamos una tarjeta para cada pregunta
                echo "<div class='card-body'>"; // Contenido de la tarjeta
                
                echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                    // Verificamos si el autor existe y mostramos "Anónimo" si es nulo o vacío
                    //$autor = (!empty($row["Nombre_Usu"])) ? $row["Nombre_Usu"] . " " . $row["Apellidos_Usu"] : "Anónimo";
                    // Verificamos si el autor es anónimo
                    if ($row['EsAnonimo'] == 1) {
                        $autor = "Anónimo";
                    } else {
                        $autor = (!empty($row["Nombre_Usu"])) ? $row["Nombre_Usu"] . " " . $row["Apellidos_Usu"] : "Anónimo";
                    }
                    echo "<p class='card-text mb-0'><strong>Autor: </strong>" . $autor . "</p>";
                    echo "<p class='card-text mb-0'><small class='text-muted'>" . $row["Hora"] . "</small></p>";
                echo "</div>";
                
                echo "<h4 class='card-title'>" . $row["Pregunta"] . "</h4>"; // Título de la pregunta

                echo "<div Class='context-area col-8'>";
                echo "<p class='card-text scrollable-text'>" . $row["Contexto"] . "</p>"; // Contexto de la pregunta
                echo "</div>";

                echo "<form method='POST'>";
                    echo "<input type='hidden' name='id_pregunta' value='" . $row['Id_Pregunta'] . "'>";
                    echo "<div class='d-flex justify-content-end align-items-center'>"; // Botón de vista en el lado derecho
                    echo "<button type='submit' class='btnEye btn' name='btnEye' style='background-color: " . ($row['Estado'] == 2 ? "green" : "") . "; border: none;'>"; // Botón sin fondo
                            echo "<img class='imgEye' src='https://cdn-icons-png.flaticon.com/512/159/159604.png' alt='Ver detalle' style='width: 50px; height: 50px;'>"; // Imagen de ojo
                        echo "</button>";
                    echo "</div>";
                echo "</form>";

                echo "</div>"; // Fin de card-body
            echo "</div>"; // Fin de tarjeta (card)
        echo "</div>"; // Fin de contenedor
    }
} else {
    echo "0 resultados";
}


$conn = null;
?>


<!--<script>
    // Seleccionamos todos los botones con la clase 'btnEye'
    document.querySelectorAll('.btnEye').forEach(button => {
        button.addEventListener('click', function() {
            // Cambiamos el color de fondo del botón clicado
            this.style.backgroundColor = "rgb(94, 229, 94)";
        });
    });
</script>-->



    <!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- Archivo JavaScript -->
<script src="script.js"></script>

    <script>     
       function logout() {
           // Redirigir a un script PHP que destruye la sesión
           window.location.href = "./controlador/logout.php"; // Cambia esto a la ruta donde está tu script de logout
       }
   </script>

</body>
</html>
    </div>
</body>
</html>