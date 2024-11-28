<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   
    <link rel="stylesheet" href="/PreguntasRespuestas/css/pregunta_detalle.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <!-- Botón de regresar (flecha) en la esquina superior izquierda -->
  <button class="btn btn-link top-left-button">
        <i class="fas fa-arrow-left fa-2x"></i>
    </button>

    <!-- Botón de visto (estrella) en la esquina superior derecha -->
    <button class="btn btn-link top-right-button">
        <i class="far fa-star fa-2x"></i>
    </button>

    <!-- Tarjeta con pregunta y contexto -->
    <div class="card shadow-sm w-100" style="max-width: 400px;">
        <div class="card-body">
            <h4 class="card-title font-weight-bold">¿Qué es una base de datos?</h4>
            <div class="row">
                <p class="card-text text-muted small col-5">Pregunta de: Anónimo </p>
                <p class="card-text text-muted small text-end col-4"></p>1:25 pm</p>
            </div>
    
            <hr>
            <h6 class="font-weight-bold">Contexto</h6>
            <div class="context-area bg-light p-3 rounded">
                Tengo la duda de que en sí, si es una base de datos como NoSQL y SQL, cuál serían sus diferencias, en qué proyecto sería recomendado usarlos, etc. Este es un ejemplo de texto muy largo que podría desbordarse. Quiero saber qué debo hacer cuando hay más texto de lo que cabe en el espacio visible. ¿Cómo puedo asegurarme de que el texto no cubra otros elementos en la página y siga siendo visible?
                Este texto es un ejemplo extendido para mostrar cómo se comportará el contenido cuando el texto se haga más largo de lo esperado. La idea es que el área de contexto tenga un límite de altura y, cuando se sobrepase, el texto se haga desplazable (scrollable).
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
</body>
</html>
