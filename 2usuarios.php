<?php
// 1. INICIALIZAR VARIABLES (Para evitar errores en el HTML al cargar por primera vez)
$errores = [];
$exito = false;

$nombre = "";
$email = "";
$password = "";
$telefono = "";
$edad = "";

// 2. PROCESAR EL FORMULARIO CUANDO SE ENVÍA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    
    // saneamiento
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $edad = trim($_POST['edad'] ?? '');

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

    if (empty($password)) {
        $errores[] = "CONTRASEÑA OBLIGATORIA";
    } elseif (strlen($password) < 8) {
        $errores[] = "MINIMO 8 CARACTERES";
    }

    if (empty($telefono)) {
        $errores[] = "TELEFONO OBLIGATORIO";
    } elseif (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $errores[] = "9 DIGITOS";
    }

    if ($edad === ""){
        $errores[] = "EDAD OBLIGATORIA";
    }elseif(!preg_match("/^[0-9]+$/", $edad)){
        $errores[] = "SOLO NUMEROS";
    }elseif($edad < 0 || $edad > 120){
        $errores[] = "EDAD ENTRE 0 y 120";
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
    <title>REGISTRO</title>
</head>
<body>

    <h1>REGISTRO</h1>

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
            <p>EDAD: <?php echo htmlspecialchars($edad); ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label>NOMBRE: </label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
        <br><br>

        <label>EMAIL: </label><br>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <br><br>

        <label>PASSWORD: </label><br>
        <input type="password" name="password">
        <br><br>

        <label>TELEFONO: </label><br>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
        <br><br>

        <label>EDAD: </label><br>
        <input type="number" name="edad" value="<?php echo htmlspecialchars($edad); ?>">
        <br><br>

        <button type="submit" name="registrar">REGISTRAR</button>
    </form>

</body>
</html>