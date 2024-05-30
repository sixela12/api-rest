<?php
session_start();

// Verificar si los datos de la sesión están establecidos
if (!isset($_SESSION['id_cliente']) || !isset($_SESSION['llave_secreta'])) {
    die('No está autenticado.');
}

// Obtener id_cliente y llave_secreta de la sesión
$id_cliente = $_SESSION['id_cliente'];
$llave_secreta = $_SESSION['llave_secreta'];

// Obtener el ID del paciente desde la URL
if (!isset($_GET['id'])) {
    die('ID de paciente no especificado.');
}

$id_paciente = trim($_GET['id']); // Eliminar cualquier espacio adicional

// URL de la API
$api_url = 'http://localhost/DesarrolloWeb2/api-rest/pacientes/' . $id_paciente;

$username = $id_cliente;
$password = $llave_secreta;

// Obtener datos del paciente
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
    die('Error al obtener los datos del paciente: ' . $error['message']);
}

$data = json_decode($response, true);
if ($data === NULL) {
    die('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
}

$paciente = $data['detalle'][0] ?? []; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Datos actualizados del paciente
    $datos = array(
        "Id" => $id_paciente,
        "Nombre" => $_POST["nombre"],
        "Telefono" => $_POST["telefono"],
        "Dirección" => $_POST["direccion"]
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
        die('Error al actualizar el paciente: ' . $error['message']);
    }


    // Redirigir a la lista de pacientes
    header("Location: pacientes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Paciente</title>
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
        <h1>Actualizar Paciente</h1>
        <form method="POST" action="actualizar_paciente.php?id=<?php echo $id_paciente; ?>">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($paciente['Nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <label for="telefono">Teléfono:</label><br>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($paciente['Telefono'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <label for="direccion">Dirección:</label><br>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($paciente['Dirección'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <input type="submit" value="Actualizar">
            <a href="pacientes.php"><button type="button">Cancelar</button></a>
        </form>
    </main>
</body>
</html>
