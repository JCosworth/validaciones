<?php
$errores = [];
$exito = false;

$nombre = '';
$correo = '';
$f_nacimiento = '';
$participacion = '';
$interes = [];
$ciudad = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar_registro"])){
    $nombre = trim ($_POST['nombre'] ?? '');
    $correo = filter_var (trim($_POST['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
    $f_nacimiento = trim($_POST['f_nacimiento'] ?? '');
        $f_objeto = DateTime::createFromFormat('d/m/Y', $f_nacimiento);
        $errores_f = DateTime::getLastErrors();
    $participacion = trim ($_POST['participacion'] ?? '');
    $interes = $_POST['interes'] ?? [];
    $ciudad = trim($_POST['ciudad'] ?? '');

if (empty($nombre)){
    $errores [] = "NOMBRE OBLIGATORIO";
}elseif(!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/", $nombre)){
    $errores [] = "LOS NOMBRES SOLAMENTE LLEVAN LETRAS Y ESPACIOS";
}elseif(strlen($nombre) < 5 || strlen($nombre) > 100){
    $errores [] = "ENTRE 5 y 100 CARACTERES";
}

if (empty($correo)){
    $errores [] = "CORREO OBLIGATORIO";
}elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
    $errores [] = "FORMATO CORREO INCORRECTO";
}

if (empty($f_nacimiento)){
    $errores [] = "FECHA NACIMIENTO OBLIGATORIA";
}elseif(!$f_objeto || $errores_f['error_count'] > 0 || $errores_f['warning_count'] > 0) {
    $errores [] = "FORMATO DEBE SER DD/MM/AAAA";
}

$modalidades = ['Asistente', 'Ponente'];
if (empty($participacion) || !in_array($participacion, $modalidades)){
    $errores [] = "SELECCIONE SU PARTICIPACION";
}

if (count($interes) < 2 || count($interes) > 4){
    $errores [] = "SELECCIONE ENTRE 2 Y 4";
}

if (in_array('Ciberseguridad', $interes) && ($ciudad !== 'Madrid' && $ciudad !== 'Sevilla')){
    $errores [] = "CIBERSEGURIDAD SOLAMENTE ESTAN EN SEVILLA Y MADRID";
}

if (empty($ciudad)){
    $errores [] = "MARQUE UNA CIUDAD";
}

if (empty($errores)){
    $exito = true;
}

}

?>

<!DOCTYPE html>
<html lang = "es">
<head>
    <meta charset="UTF-8">
    <title>
        REGISTRO TALLER
    </title>
</head>
<body>
    <h1>REGISTRO</h1>

    <?php if (!empty($errores)): ?>
        <div>
            <h3>CORRIGE: </h3>
            <ul>
                <?php foreach ($errores as $error): ?>
                <li><?php echo $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif ?>

<form action="" method="POST">
    <label>NOMBRE COMPLETO: </label><br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre) ?>"><br><br>

    <label>CORREO: </label><br>
    <input type="text" name="correo" value="<?php echo htmlspecialchars($correo) ?>"><br><br>
    <label>FECHA NACIMIENTO: </label>
    <input type="text" name="f_nacimiento" value="<?php echo htmlspecialchars($f_nacimiento) ?>"><br>
    <br>

    <label><b>SELECCIONE SU PARTICIPACION</b></label><br>
    <input type="radio" name="participacion" value="Asistente"<?php echo ($participacion === 'Asistente') ? 'checked' : ''; ?>> ASISTENTE <br>
    <input type="radio" name="participacion" placeholder="dd/mm/aaaa" value="Ponente"<?php echo ($participacion === 'Ponente') ? 'checked' : ''; ?>> PONENTE <br><br>

    <label><b>SELECCIONE TEMA INTERES</b></label><br>
    <input type="checkbox" name="interes[]" value="Programacion"<?php echo in_array ('Programacion', $interes) ? 'checked' : ''; ?>> PROGRAMACIÓN <br>
    <input type="checkbox" name="interes[]" value="Ciberseguridad"<?php echo in_array ('Ciberseguridad', $interes) ? 'checked' : ''; ?>> CIBERSEGURIDAD <br>
    <input type="checkbox" name="interes[]" value="Inteligencia_Artificial"<?php echo in_array ('Inteligencia_Artificial', $interes) ? 'checked' : ''; ?>> INTELIGENCIA ARTIFICIAL <br>
    <input type="checkbox" name="interes[]" value="Redes"<?php echo in_array ('Redes', $interes) ? 'checked' : ''; ?>> REDES <br>
    <input type="checkbox" name="interes[]" value="Desarrollo_Web"<?php echo in_array ('Desarrollo_Web', $interes) ? 'checked' : ''; ?>> DESARROLLO WEB <br><br>

    <select name="ciudad" id="">
        <option value="">SELECCIONE UNA CIUDAD</option>
        <option value="Madrid" <?php echo ($ciudad === 'Madrid') ? 'selected' : ''; ?>>MADRID</option>
        <option value="Barcelona" <?php echo ($ciudad === 'Barcelona') ? 'selected' : ''; ?>>BARCELONA</option>
        <option value="Sevilla" <?php echo ($ciudad === 'Sevilla') ? 'selected' : ''; ?>>SEVILLA</option>
        <option value="Valencia" <?php echo ($ciudad === 'Valencia') ? 'selected' : ''; ?>>VALENCIA</option>
    </select><br><br>

    <button type="submit" name="enviar_registro">REGISTRAR</button><br><br>
    <?php if($exito): ?>
        <p>GRACIAS POR REGISTRARSE</p>
    <?php endif; ?>
    


</form>
</body>
</html>