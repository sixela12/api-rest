<?php
session_start();

// Procesamiento del formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ob_start();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = array(
        'email' => $email,
        'password' => $password
    );

    // Crear el contexto para la petición POST
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );

    $context  = stream_context_create($options);
    
    $result = file_get_contents('http://localhost/DesarrolloWeb2/api-rest/usuario', false, $context);

    if ($result === FALSE) {
        echo '<script>alert("Error al conectar con la API.");</script>';
    } else {
        $response = json_decode($result, true);

        

        if ($response === null) {
            echo '<script>alert("Error al decodificar la respuesta de la API: ' . json_last_error_msg() . '");</script>';
        } elseif (isset($response['status']) && $response['status'] == 200) {
            $id_cliente = $response['id_cliente'];
            $llave_secreta = $response['llave_secreta'];
            
            // Guardar en la sesión
            $_SESSION['id_cliente'] = $id_cliente;
            $_SESSION['llave_secreta'] = $llave_secreta;
            
            

            // Redirigir a index.php
            header("Location: index.php");
            exit();
        } else {
            echo '<script>alert("Credenciales incorrectas. Intenta nuevamente.");</script>';
        }
    }
    ob_end_flush();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #dfe9f3;
            background: linear-gradient(to top, #ffffff, #dfe9f3);
        }

        .wrapper {
            position: relative;
            width: 100%;
            max-width: 850px;
            display: flex;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .wrapper .form-box {
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .form-box h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }

        .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin-bottom: 30px;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            padding: 0 30px 0 50px;
            font-size: 16px;
            color: #333;
            border: none;
            outline: none;
            background: none;
            border: 1px solid #ccc;
            border-radius: 25px;
            transition: 0.3s;
        }

        .input-box input:focus {
            border-color: #007bff;
        }

        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #007bff;
        }

        .input-box label {
            position: absolute;
            left: 50px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #aaa;
            pointer-events: none;
            transition: 0.3s;
        }

        .input-box input:focus ~ label,
        .input-box input:valid ~ label {
            top: 5px;
            left: 50px;
            font-size: 12px;
            color: #007bff;
        }

        .btn {
            width: 100%;
            height: 50px;
            border: none;
            outline: none;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .info-text {
            display: none;
        }

        @media (min-width: 768px) {
            .wrapper {
                flex-direction: row;
            }

            .form-box {
                border-top-left-radius: 15px;
                border-bottom-left-radius: 15px;
            }

            .info-text {
                display: block;
                width: 100%;
                max-width: 450px;
                padding: 40px;
                background: #007bff;
                color: #fff;
                border-top-right-radius: 15px;
                border-bottom-right-radius: 15px;
            }

            .info-text h2 {
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 15px;
            }

            .info-text p {
                font-size: 16px;
                line-height: 1.5;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <div class="form-box login">
            <h2>Login</h2>
            <form method="post">
                <div class="input-box">
                    <input type="text" name="email" required>
                    <i class='bx bxs-user'></i>
                    <label>Correo Electrónico</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <label>Contraseña</label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
        <div class="info-text login">
            <h2>!!Bienvenido de Nuevo¡¡</h2>
            <p>Inicia sesión para acceder a la plataforma</p>
        </div>
    </div>
</body>
</html>
