<?php
session_start();

// Verificar si los datos de la sesión están establecidos
if (!isset($_SESSION['id_cliente']) || !isset($_SESSION['llave_secreta'])) {
    die('No está autenticado.');
}

// Obtener id_cliente y llave_secreta de la sesión
$id_cliente = $_SESSION['id_cliente'];
$llave_secreta = $_SESSION['llave_secreta'];

// Obtener el ID del médico desde la URL
if (!isset($_GET['id'])) {
    die('ID de médico no especificado.');
}

$id_medico = trim($_GET['id']); // Eliminar cualquier espacio adicional

// URL de la API
$api_url = 'http://localhost/DesarrolloWeb2/api-rest/medicos/' . $id_medico;

$username = $id_cliente;
$password = $llave_secreta;

// Obtener datos del médico
$options = array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode("$username:$password"),
        'method' => 'GET'
    )
);

$context = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);

// Verificar si la solicitud fue exitosa
if ($response === FALSE) {
    $error = error_get_last();
    die('Error al obtener los datos del médico: ' . $error['message']);
}

$data = json_decode($response, true);
if ($data === NULL) {
    die('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
}

$medico = $data['detalle'][0] ?? [];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Datos actualizados del médico
    $datos = array(
        "id" => $id_medico,
        "nombre" => $_POST["nombre"] ?? '',
        "telefono" => $_POST["telefono"] ?? '',
        "especialidad" => $_POST["especialidad"] ?? ''
    );


    $datos_query = http_build_query($datos);

    // Configurar opciones de la solicitud PUT
    $options = array(
        'http' => array(
            'header' => "Authorization: Basic " . base64_encode("$username:$password") . "\r\n" .
                        "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'PUT',
            'content' => $datos_query
        )
    );

    $context = stream_context_create($options);

    // Realizar la solicitud PUT
    $response = file_get_contents($api_url, false, $context);

    if ($response === FALSE) {
        $error = error_get_last();
        die('Error al actualizar el médico: ' . $error['message']);
    }


    // Decodificar la respuesta para verificar errores
    $response_data = json_decode($response, true);
    if ($response_data === NULL) {
        die('Error al decodificar la respuesta JSON de la actualización: ' . json_last_error_msg());
    }

    // Verificar si la actualización fue exitosa
    if (isset($response_data['status']) && $response_data['status'] == 200) {
        // Redirigir a la lista de médicos
        header("Location: medicos.php");
        exit();
    } else {
        die('Error al actualizar el médico: ' . ($response_data['detalle'] ?? 'Error desconocido'));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Médico</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Mi Aplicación Médica</h1>
            <nav>
                <ul>
                    <li><a href="citas.php">Citas</a></li>
                    <li><a href="medicos.php">Médicos</a></li>
                    <li><a href="pacientes.php">Pacientes</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h1>Actualizar Médico</h1>
        <form method="POST" action="actualizar_medico.php?id=<?php echo $id_medico; ?>">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($medico['Nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <label for="telefono">Teléfono:</label><br>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($medico['Telefono'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <label for="especialidad">Especialidad:</label><br>
            <input type="text" id="especialidad" name="especialidad" value="<?php echo htmlspecialchars($medico['Especialidad'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <input type="submit" value="Actualizar">
            <a href="medicos.php"><button type="button">Cancelar</button></a>
        </form>
    </main>
</body>
</html>
