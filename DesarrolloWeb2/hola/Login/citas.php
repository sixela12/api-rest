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
$url = 'http://localhost/DesarrolloWeb2/api-rest/citas';

$username = $id_cliente;
$password = $llave_secreta;

// Configurar opciones de la solicitud
$options = array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode("$username:$password"),
        'method' => 'GET'
    )
);

// Crear el contexto de la solicitud
$context = stream_context_create($options);

// Realizar la solicitud
$response = file_get_contents($url, false, $context);

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

// Obtener la lista de citas
$citas = isset($data['detalle']) ? $data['detalle'] : [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Citas</title>
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
        <h1>Lista de Citas</h1>
        <table>
            <thead>
                <tr>
                    <th>Cita</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>No Consultorio</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($citas)): ?>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cita['Fecha_Cita']); ?></td>
                            <td><?php echo htmlspecialchars($cita['NombrePaciente']); ?></td>
                            <td><?php echo htmlspecialchars($cita['NombreMedico']); ?></td>
                            <td><?php echo htmlspecialchars($cita['No_Consultorio']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No se encontraron citas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="http://localhost/DesarrolloWeb2/api-rest/FPDF/index.php" target="_blank"><button>Generar PDF</button></a>
    </main>
</body>
</html>
