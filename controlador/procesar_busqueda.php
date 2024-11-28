<?php
session_start(); // Asegúrate de iniciar la sesión al principio

// Verifica si el usuario ha iniciado sesión como expositor
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'expositor') {
    header("Location: login.php"); // Redirige a la página de inicio de sesión
    exit(); // Asegúrate de que el script se detenga después de la redirección
}

include($_SERVER['DOCUMENT_ROOT'] . '/Equipo_PreguntasRepuestas/modelo/conexion.php');

// Verificar si el formulario fue enviado
if (isset($_POST['btnEye']) && isset($_POST['id_pregunta'])) {
    $idPregunta = $_POST['id_pregunta'];
    
    // Actualizar el estado de la pregunta con el ID proporcionado
    $sqlUpdate = "UPDATE Pregunta SET Estado = 2 WHERE Id_Pregunta = ?";
    $stmt = $conn->prepare($sqlUpdate);
    
    $stmt->bindValue(':idPregunta', $idPregunta, PDO::PARAM_INT); // Aquí pasamos el valor correctamente
    
    if ($stmt->execute()) {
        echo "<p>Estado actualizado correctamente.</p>";
    } else {
        echo "<p>Error al actualizar el estado.</p>";
    }
    
    // Recargar la página para mostrar el cambio de estado
    header("Refresh:0");
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
    <link rel="stylesheet" href="/Equipo_PreguntasRepuestas/css/contenedorpregunta.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<div class="search-container">
    <!-- Flecha hacia atrás -->
    <div class="d-flex align-items-center mb-3">
        <i class="back-button bi bi-arrow-left" onclick="window.history.back()"></i>
        <h1 class="ms-3">Resultados de Búsqueda</h1>
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

    <!-- Contenedor de etiquetas dinámicas -->
    <div class="tag-container d-flex gap-2 flex-wrap" id="tag-container">
        <?php
        // Mostrar las etiquetas previamente añadidas
        if (isset($_GET['tags']) && !empty($_GET['tags'])) {
            $tags = $_GET['tags'];
            foreach ($tags as $tag) {
                echo "<div class='tag'>$tag <i class='bi bi-x' onclick='removeTag(this, \"$tag\")'></i></div>";
            }
        }
        ?>
    </div>
</div>

<!-- Contenedor de los resultados de búsqueda -->
<div class="container mt-4">
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/Equipo_PreguntasRepuestas/modelo/conexion.php');

    if (isset($_GET['tags']) && !empty($_GET['tags'])) {
        $tags = $_GET['tags'];

        // Preparamos la consulta para buscar las preguntas que contengan alguna de las etiquetas
        $sql = "SELECT p.Id_Pregunta, p.Pregunta, p.Contexto, p.Hora, p.Fecha, 
                       u.Nombre_Usu, u.Apellidos_Usu, pon.Tema_ponente, p.Estado, p.EsAnonimo
                FROM Pregunta p
                LEFT JOIN Usuario u ON p.Id_Autor = u.Id_Usu
                LEFT JOIN ponentes pon ON p.id_Tema = pon.id_ponente
                WHERE ";
        $conditions = [];
        foreach ($tags as $tag) {
            // Cada condición busca la etiqueta en el título de la pregunta o en el contexto o en el tema
            $conditions[] = "(p.Pregunta LIKE '%" . $tag . "%' OR p.Contexto LIKE '%" . $tag . "%' OR pon.Tema_ponente LIKE '%" . $tag . "%')";
        }
        // Unimos todas las condiciones con 'OR'
        $sql .= implode(" OR ", $conditions);

        // Imprimir la consulta SQL para depuración
        error_log("Consulta SQL: " . $sql);

        // Ejecutar la consulta
        //$result = $conn->query($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
            // Mostrar cada resultado encontrado
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Imprimir cada fila obtenida para depuración
                error_log("Resultado de la consulta: " . json_encode($row));

                echo "<div class='container mt-4'>";
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                
                echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                // Mostrar el autor de la pregunta, o 'Anónimo' si no existe información del autor
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
                
                // Mostrar el título de la pregunta
                echo "<h4 class='card-title'>" . $row["Pregunta"] . "</h4>";
                echo "<div class='context-area col-8'>";
                // Mostrar el contexto de la pregunta
                echo "<p class='card-text scrollable-text'>" . $row["Contexto"] . "</p>";
                echo "</div>";
                
                // Mostrar el tema del ponente
                echo "<p class='card-text'><strong>Tema: </strong>" . $row["Tema_ponente"] . "</p>";

                // Dentro del bucle que genera las preguntas
                echo "<form method='POST'>";
                echo "<input type='hidden' name='id_pregunta' value='" . $row['Id_Pregunta'] . "'>";
                echo "<div class='d-flex justify-content-end align-items-center'>";
                echo "<button type='submit' class='btnEye btn' name='btnEye' style='background-color: " . ($row['Estado'] == 2 ? "green" : "") . "; border: none;'>";
                echo "<img class='imgEye' src='https://cdn-icons-png.flaticon.com/512/159/159604.png' alt='Ver detalle' style='width: 50px; height: 50px;'>";
                echo "</button>";
                echo "</div>";
                echo "</form>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            // Mensaje si no se encuentran resultados
            error_log("No se encontraron resultados para las etiquetas proporcionadas");
            echo "<p>No se encontraron preguntas que coincidan con las etiquetas.</p>";
        }
    } else {
        // Mensaje si no se proporcionan etiquetas
        error_log("No se proporcionaron etiquetas para la búsqueda");
        echo "<p>No se proporcionaron etiquetas para la búsqueda.</p>";
    }

    // Cerrar la conexión a la base de datos
    error_log("Cerrando la conexión a la base de datos");
    $conn = null;
    ?>
</div>

<!--<script>
    // Seleccionamos todos los botones con la clase 'btnEye'
    document.querySelectorAll('.btnEye').forEach(button => {
        button.addEventListener('click', function() {
            // Cambiamos el color de fondo del botón clicado
            this.style.backgroundColor = "rgb(94, 229, 94)";
        });
    });
</script>-->


<script>
// Funcionalidad de agregar etiquetas al hacer clic en "Añadir palabra clave/etiqueta"
let tags = <?php echo json_encode(isset($_GET['tags']) ? $_GET['tags'] : []); ?>;

// Evento para añadir una nueva etiqueta al hacer clic en el botón
document.getElementById('add-tag-btn').addEventListener('click', function() {
    console.log("Botón 'Añadir palabra clave/etiqueta' clicado");
    const input = document.getElementById('tag-input');
    const tagValue = input.value.trim();

    // Verifica si el valor no está vacío y si no se ha añadido antes
    if (tagValue && !tags.includes(tagValue)) {
        console.log("Añadiendo etiqueta:", tagValue);
        tags.push(tagValue);  // Añadir la etiqueta al array
        addTagToContainer(tagValue);  // Añadir la etiqueta visualmente
        input.value = '';  // Limpiamos el campo de entrada después de añadir
    } else {
        console.log("Etiqueta ya existe o el valor está vacío");
    }
});

// Función para añadir una etiqueta al contenedor visual de etiquetas
function addTagToContainer(tag) {
    console.log("Añadiendo etiqueta al contenedor visual:", tag);
    const tagContainer = document.getElementById('tag-container');
    
    // Crear un nuevo elemento div para la etiqueta
    const tagElement = document.createElement('div');
    tagElement.classList.add('tag');
    // Añadir el contenido de la etiqueta y un icono para eliminarla
    tagElement.innerHTML = `${tag} <i class="bi bi-x" onclick="removeTag(this, '${tag}')"></i>`;
    
    // Añadir la etiqueta al contenedor de etiquetas
    tagContainer.appendChild(tagElement);
}

// Función para eliminar una etiqueta del contenedor y del array
function removeTag(element, tag) {
    console.log("Eliminando etiqueta:", tag);
    // Encuentra el índice de la etiqueta en el array y elimínala
    const index = tags.indexOf(tag);
    if (index !== -1) {
        tags.splice(index, 1);  // Eliminar del array
        console.log("Etiqueta eliminada del array:", tag);
    }
    // Eliminar el elemento visual de la etiqueta
    element.parentElement.remove();
    console.log("Etiqueta eliminada del contenedor visual");

    // Redirigir a ContenedorPregunta.php para mostrar todas las preguntas
    if (tags.length === 0) {
        window.location.href = '/Equipo_PreguntasRepuestas/ContenedorPregunta.php'; // Cambia la URL a la ruta correcta si es necesario
    }
}


// Funcionalidad de búsqueda y redirección a "procesar_busqueda_por_etiquetas.php"
document.getElementById('search-btn').addEventListener('click', function() {
    console.log("Botón 'Buscar' clicado");
    // Verificar si hay al menos una etiqueta antes de realizar la búsqueda
    if (tags.length > 0) {
        // Construir la cadena de consulta con las etiquetas
        const queryString = tags.map(tag => `tags[]=${encodeURIComponent(tag)}`).join('&');
        // Redirigir a la misma página con las etiquetas en la consulta
        window.location.href = `procesar_busqueda.php?${queryString}`;
    } else {
        console.log("No hay etiquetas para buscar");
    }
});
</script>

</body>
</html>
