<?php
$errores = [];
$exito = false;

$nombre = "";
$contacto = "";
$correo = "";
$telefono = "";
$satisfaccion = "";
$mejoras = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_encuesta'])){
    $nombre = trim ($_POST['nombre'] ?? '');
    $contacto = trim ($_POST['contacto'] ?? '');
    $correo = filter_var(trim ($_POST['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefono = trim ($_POST['telefono'] ?? '');
    $satisfaccion = trim ($_POST['satisfaccion'] ?? '');
    $mejoras = $_POST['mejoras'] ?? [];

    if (empty($nombre)){
        $errores [] = "NOMBRE OBLIGATORIO";
    }elseif(!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)){
        $errores [] = "NOMBRE SOLO CON LETRAS Y ESPACIO";
    }elseif(strlen($nombre) < 5 || strlen($nombre) > 100){
        $errores [] = "NOMBRE DEBE TENER ENTRE 5 y 100 CARACTERES";
    }

    $contactos = ['email', 'telefono', 'whatsapp'];
    if (empty ($contacto)){
        $errores [] = "SELECCION OBLIGATORIA";
    }elseif(!in_array($contacto, $contactos)){
        $errores [] = "METODDO NO VÁLIDO";
    }

    if($contacto === 'email' && empty($correo)){
        $errores [] = "CORREO OBLIGATORIO";
    }elseif(!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $errores [] = "FORMATO NO VÁLIDO";
    }

    if(($contacto === 'telefono' || $contacto === 'whatsapp') && empty($telefono)){
        $errores [] = "TELÉFONO OBLIGATORIO";
    }elseif(!empty($telefono) && !preg_match("/^[6789][0-9]{8}$/", $telefono)){
        $errores [] = "FORMATO NO VÁLIDO";
    }

    $niveles = ['muy_satisfecho', 'satisfecho', 'neutral', 'insatisfecho', 'muy_insatisfecho'];
    if (empty ($satisfaccion)){
        $errores [] = "SELECCION OBLIGATORIA";
    }elseif(!in_array($satisfaccion, $niveles)){
        $errores [] = "METODO NO VÁLIDO";
    }

    if (count($mejoras) > 3) {
        $errores [] = "SOLAMENTE 3 ASPECTOS";
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
        Encuesta SATISFACCIÓN
    </title>
</head>
<body>
    <h1>
        ENCUESTA
    </h1>

    <?php if (!empty($errores)): ?>
        <div>
        <h3> CORRIGE: </h3>
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?php echo $error; ?>
            <?php endforeach; ?>
        </ul>
        </div>
    <?php endif ?>

    <?php if ($exito): ?>

    <div>
        <h3> GRACIAS: </h3>
        <p>CLIENTE: <?php echo htmlspecialchars($nombre); ?></p>
        <p>CONTACTO: <?php echo htmlspecialchars(strtoupper($contacto)); ?></p>

        <?php if (!empty($correo)): ?>
            <p>EMAIL: <?php echo htmlspecialchars($correo); ?></p>
        <?php endif; ?>

        <?php if (!empty($telefono)): ?>
            <p>TELEFONO: <?php echo htmlspecialchars($telefono); ?></p>
        <?php endif; ?>

    <p>NIVEL DE SATISFACCIÓN: <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $satisfaccion))); ?></p>

    <p>ASPECTOS A MEJORAR: </p>
    <?php if (!empty($mejoras)): ?>
        <ul>
            <?php foreach ($mejoras as $mejora): ?>
                <li><?php echo htmlspecialchars($mejora); ?></li>
            <?php endforeach ?>
        </ul>
    <?php else: ?>
        <p>NO HA SELECCIONADO NADA</p>
    <?php endif; ?>
    </div>
    <?php endif; ?>

<form action="" method="POST">
    <label>NOMBRE</label></br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>"></br></br>

    <label>METODO CONTACTO</label></br>
    <select name = "contacto">
    <option value=""> SELECCIONE UNA OPCION </option>
    <option value="email"> <?php echo ($contacto === 'email') ? 'selected' : ''; ?>>EMAIL</option>
    <option value="telefono"> <?php echo ($contacto === 'telefono') ? : ''; ?>>TELEFONO</option>
    <option value="whatsapp"> <?php echo ($contacto === 'whatsapp') ? : '' ; ?>>WHATSAPP</option>
    </select></br></br>

    <label>CORREO</label></br>
    <input type="text" name="correo" value="<?php echo htmlspecialchars($correo); ?>"></br></br>

    <label>TELEFONO</label></br>
    <input type="text" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>"></br></br>

    <label>NIVEL SATISFACCION</label></br>
    <input type="radio" name="satisfaccion" value="muy_satisfecho"<?php echo ($satisfaccion === 'muy_satisfecho') ? 'checked' : ''; ?>> MUY SATISFECHO<br>
    <input type="radio" name="satisfaccion" value="satisfecho"<?php echo ($satisfaccion === 'satisfecho') ? 'checked' : ''; ?>> SATISFECHO<br>
    <input type="radio" name="satisfaccion" value="neutral"<?php echo ($satisfaccion === 'neutral') ? 'checked' : ''; ?>> NEUTRAL<br>
    <input type="radio" name="satisfaccion" value="insatisfecho"<?php echo ($satisfaccion === 'insatisfecho') ? 'checked' : ''; ?>> INSATISFECHO<br>
    <input type="radio" name="satisfaccion" value="muy_insatisfecho"<?php echo ($satisfaccion === 'muy_insatisfecho') ? 'checked' : ''; ?>> MUY INSATISFECHO<br>
    </br>

    <label>MEJORAS</label></br>
    <input type="checkbox" name="mejoras[]" value="Atencion al Cliente"<?php echo in_array("Atencion al Cliente", $mejoras) ? 'checked' : ''; ?>> ATENCION AL CLIENTE<br>
    <input type="checkbox" name="mejoras[]" value="Tiempo Espera"<?php echo in_array("Tiempo Espera", $mejoras) ? 'checked' : ''; ?>> TIEMPO ESPERA<br>
    <input type="checkbox" name="mejoras[]" value="Calidad"<?php echo in_array("Calidad", $mejoras) ? 'checked' : ''; ?>> CALIDAD<br>
    <input type="checkbox" name="mejoras[]" value="Precio"<?php echo in_array("Precio", $mejoras) ? 'checked' : ''; ?>> PRECIO<br>
    <input type="checkbox" name="mejoras[]" value="WEB"<?php echo in_array("WEB", $mejoras) ? 'checked' : ''; ?>> WEB<br>
    </br>

    <button type="submit" name="enviar_encuesta">VALORACION</button>
</form>
</body>
</html>