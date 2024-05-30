<?php
session_start();

// Verificar si los datos de la sesión están establecidos
if (!isset($_SESSION['id_cliente']) || !isset($_SESSION['llave_secreta'])) {
    die('No está autenticado.');
}

// Obtener id_cliente y llave_secreta de la sesión
$id_cliente = $_SESSION['id_cliente'];
$llave_secreta = $_SESSION['llave_secreta'];

// URL de la API
$api_url = 'http://localhost/DesarrolloWeb2/api-rest/pacientes';

// Autenticación básica
$username = $id_cliente;
$password = $llave_secreta;

// Configurar opciones de la solicitud GET
$options = array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode("$username:$password"),
        'method' => 'GET'
    )
);

// Crear el contexto de la solicitud
$context = stream_context_create($options);

// Realizar la solicitud GET
$response = file_get_contents($api_url, false, $context);

// Verificar si la solicitud fue exitosa
if ($response === FALSE) {
    die('Error al consumir la API');
}

// Limpiar la respuesta para obtener solo el JSON válido
$json_start = strpos($response, '{');
$json_response = substr($response, $json_start);

// Decodificar la respuesta JSON
$data = json_decode($json_response, true);

// Verificar si la decodificación fue exitosa
if ($data === NULL) {
    die('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
}

// Obtener la lista de pacientes
$pacientes = isset($data['detalle']) ? $data['detalle'] : [];

// Función para eliminar un paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_url = $api_url . '/' . $delete_id;

    $delete_options = array(
        'http' => array(
            'header' => "Authorization: Basic " . base64_encode("$username:$password"),
            'method' => 'DELETE'
        )
    );

    $delete_context = stream_context_create($delete_options);
    $delete_response = file_get_contents($delete_url, false, $delete_context);

    if ($delete_response === FALSE) {
        die('Error al eliminar el paciente');
    }

    // Recargar la página después de la eliminación
    header("Location: pacientes.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pacientes</title>
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
        <h1>Lista de Pacientes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pacientes)): ?>
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($paciente['Id']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['Dirección']); ?></td>
                            <td>
                                <form method="POST" action="pacientes.php" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $paciente['Id']; ?>">
                                    <input type="submit" value="Eliminar">
                                </form>
                                <a href="actualizar_paciente.php?id= <?php echo $paciente['Id']; ?>"><button>Actualizar</button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No se encontraron pacientes.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="ingresar_pacientes.php"><button>Ingresar Nuevo Paciente</button></a>
    </main>
</body>
</html>
