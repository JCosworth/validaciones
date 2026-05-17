<?php
// 1. INICIALIZAR VARIABLES (Para evitar errores en el HTML al cargar por primera vez)
$errores = [];
$exito = false;

$nombre = "";
$email = "";
$telefono = "";
$cargo = "";
$carta = "";

// 2. PROCESAR EL FORMULARIO CUANDO SE ENVÍA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postular'])) {
    
    // saneamiento
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefono = trim($_POST['telefono'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $carta = trim($_POST['carta'] ?? '');

    // 3. VALIDACIONES
    
    if (empty($nombre)) {
        $errores[] = "NOMBRE OBLIGATORIO";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)){
        $errores[] = "FORMATO INCORRECTO";
    } elseif (strlen($nombre) < 3 || strlen($nombre) > 50){
        $errores[] = "NOMBRE ENTRE 3 y 50 caracteres";
    }

    if (empty($email)) {
        $errores[] = "CORREO OBLIGATORIO";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "FORMATO INCORRECTO";
    }

    if (empty($telefono)) {
        $errores[] = "TELEFONO OBLIGATORIO";
    } elseif (!preg_match("/^[6789][0-9]{8}$/", $telefono)) {
        $errores[] = "9 DIGITOS";
    }

    if (empty($cargo)) {
        $errores[] = "CARGO OBLIGATORIO";
    } elseif (strlen($cargo) <2 || strlen($cargo) >50){
        $errores[] = "ENTRE 2 y 50 CARACTERES";
    }

    if (empty($carta)) {
        $errores[] = "CARTA PRESENTACION OBLIGATORIA";
    } elseif (strlen($carta) < 50){
        $errores[] = "MINIMO 50 CARACTERES";
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
    <title>SOLICITUD EMPLEO</title>
</head>
<body>

    <h1>SOLICITUD</h1>

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
            <p>CARGO: <?php echo htmlspecialchars($cargo); ?></p>
            <p>CARTA: <?php echo htmlspecialchars($carta); ?></p>
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

        <label>CARGO: </label><br>
        <input type="text" name="cargo" value="<?php echo htmlspecialchars($cargo); ?>">
        <br><br>

        <label>CARTA: </label><br>
        <input type="text" name="carta" value="<?php echo htmlspecialchars($carta); ?>">
        <br><br>

        <button type="submit" name="postular">REGISTRAR</button>
    </form>

</body>
</html>