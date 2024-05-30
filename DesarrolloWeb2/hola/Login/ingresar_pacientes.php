<?php
session_start();

// Verificar si los datos de la sesión están establecidos
if (!isset($_SESSION['id_cliente']) || !isset($_SESSION['llave_secreta'])) {
    die('No está autenticado.');
}

// Obtener id_cliente y llave_secreta de la sesión
$id_cliente = $_SESSION['id_cliente'];
$llave_secreta = $_SESSION['llave_secreta'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Datos del nuevo paciente
    $datos = http_build_query(array(
        "nombre" => $_POST["nombre"],
        "telefono" => $_POST["telefono"],
        "direccion" => $_POST["direccion"]
    ));

    // URL de la API
    $api_url = 'http://localhost/DesarrolloWeb2/api-rest/pacientes';

    $username = $id_cliente;
    $password = $llave_secreta;

    // Configurar opciones de la solicitud POST
    $options = array(
        'http' => array(
            'header' => "Authorization: Basic " . base64_encode("$username:$password") . "\r\n" .
                        "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => $datos
        )
    );

    // Crear el contexto de la solicitud
    $context = stream_context_create($options);

    // Realizar la solicitud POST
    $response = file_get_contents($api_url, false, $context);

    // Verificar si la solicitud fue exitosa
    if ($response === FALSE) {
        die('Error al consumir la API');
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
    <title>Ingresar Nuevo Paciente</title>
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
        <h1>Ingresar Nuevo Paciente</h1>
        <form method="POST" action="ingresar_pacientes.php">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required><br><br>
            <label for="telefono">Teléfono:</label><br>
            <input type="text" id="telefono" name="telefono" required><br><br>
            <label for="direccion">Dirección:</label><br>
            <input type="text" id="direccion" name="direccion" required><br><br>
            <input type="submit" value="Agregar">
            <a href="pacientes.php"><button type="button">Cancelar</button></a>
        </form>
    </main>
</body>
</html>
