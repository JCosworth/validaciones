<?php
// 1. INICIALIZAR VARIABLES (Para evitar errores en el HTML al cargar por primera vez)
$errores = [];
$exito = false;

$nombre = "";
$email = "";
$telefono = "";
$entrada = "";
$noches = "";

// 2. PROCESAR EL FORMULARIO CUANDO SE ENVÍA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservar'])) {
    
    // saneamiento
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefono = trim($_POST['telefono'] ?? '');
    $entrada = trim($_POST['entrada'] ?? '');
    $noches = trim($_POST['noches'] ?? '');

    // 3. VALIDACIONES
    
    if (empty($nombre)) {
        $errores[] = "NOMBRE OBLIGATORIO";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)){
        $errores[] = "FORMATO INCORRECTO";
    } elseif (strlen($nombre) < 5 || strlen($nombre) > 100){
        $errores[] = "NOMBRE ENTRE 5 y 100 caracteres";
    }

    if (empty($email)) {
        $errores[] = "CORREO OBLIGATORIO";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "FORMATO INCORRECTO";
    }

    if (empty($telefono)) {
        $errores[] = "TELEFONO OBLIGATORIO";
    } elseif (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $errores[] = "9 DIGITOS";
    }

    if (empty($entrada)) {
        $errores[] = "Fecha obligatoria";
    } else {
        $hoy = new DateTime('today');
        $f_usuario = new DateTime($entrada);

        if ($f_usuario < $hoy){
            $errores[] = "FECHA ANTERIOR";
        }
    }

    if (empty($noches)) {
        $errores[] = "EL NÚMERO DE NOCHES ES OBLIGATORIO (NI PUEDES MARCAR 0)";
    } elseif (!preg_match("/^[0-9]+$/", $noches)){
        $errores[] = "SOLO NÚMEROS";
    } elseif ($noches < 1 || $noches > 30) {
        $errores[] = "EL NÚMERO DE NOCHES ES ENTRE 1 y 30";
    }

    if (empty($errores)){
        $exito = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>RESERVA HOTEL</title>
</head>
<body>

    <h1>RESERVA</h1>

    <?php if (!empty($errores)): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($exito): ?>
        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">
            <p>NOMBRE: <?php echo htmlspecialchars($nombre); ?></p>
            <p>EMAIL: <?php echo htmlspecialchars($email); ?></p>
            <p>TELEFONO: <?php echo htmlspecialchars($telefono); ?></p>
            <p>FECHA: <?php echo htmlspecialchars($entrada); ?></p>
            <p>NOCHES: <?php echo htmlspecialchars($noches); ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label>NOMBRE: </label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
        <br><br>

        <label>EMAIL: </label><br>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <br><br>

        <label>TELEFONO: </label><br>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
        <br><br>

        <label>ENTRADA: </label><br>
        <input type="date" name="entrada" value="<?php echo htmlspecialchars($entrada); ?>">
        <br><br>

        <label>NOCHES: </label><br>
        <input type="number" name="noches" value="<?php echo htmlspecialchars($noches); ?>">
        <br><br>

        <button type="submit" name="reservar">RESERVAR</button>
    </form>

</body>
</html>