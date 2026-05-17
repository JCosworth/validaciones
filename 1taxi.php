<?php
// 1. INICIALIZAR VARIABLES (Para evitar errores en el HTML al cargar por primera vez)
$errores = [];
$resultado_html = "";
$distancia_input = "";
$tiempo_input = "";

// 2. PROCESAR EL FORMULARIO CUANDO SE ENVÍA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calcular'])) {
    
    // Recogida de datos básica
    $distancia_input = trim($_POST['distancia'] ?? '');
    $tiempo_input = trim($_POST['tiempo'] ?? '');

    // 3. VALIDACIONES
    // Validar distancia (Número flotante/decimal, mayor que 0)
    $distancia = filter_var($distancia_input, FILTER_VALIDATE_FLOAT);
    if ($distancia === false || $distancia <= 0) {
        $errores[] = "La distancia debe ser un número positivo mayor que cero.";
    }

    // Validar tiempo (Número entero, entre 0 y 120)
    $tiempo = filter_var($tiempo_input, FILTER_VALIDATE_INT);
    if ($tiempo === false || $tiempo < 0 || $tiempo > 120) {
        $errores[] = "El tiempo debe ser un número entero entre 0 y 120 minutos.";
    }

    // 4. CÁLCULO SI NO HAY ERRORES
    if (empty($errores)) {
        $precio_base = 3.00;
        $coste_km = 1.50;
        $coste_min = 0.50;

        $coste_distancia = $distancia * $coste_km;
        $coste_tiempo = $tiempo * $coste_min;
        $total = $precio_base + $coste_distancia + $coste_tiempo;

        // Preparamos el bloque de texto con el resultado
        $resultado_html = "<h3>Resultado del viaje:</h3>" .
                          "Base: " . number_format($precio_base, 2) . "€<br>" .
                          "Por distancia ($distancia km): " . number_format($coste_distancia, 2) . "€<br>" .
                          "Por tiempo ($tiempo min): " . number_format($coste_tiempo, 2) . "€<br>" .
                          "<strong>Total a pagar: " . number_format($total, 2) . "€</strong>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora Taxi</title>
</head>
<body>

    <h1>Calculadora de Tarifas de Taxi</h1>

    <?php if (!empty($errores)): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <strong>Se encontraron los siguientes errores:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($resultado_html): ?>
        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">
            <?php echo $resultado_html; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Distancia (Km):</label><br>
        <input type="number" name="distancia" step="0.1" min="0" required 
               value="<?php echo htmlspecialchars($distancia_input); ?>">
        <br><br>

        <label>Tiempo (min):</label><br>
        <input type="number" name="tiempo" step="1" min="0" max="120" required
               value="<?php echo htmlspecialchars($tiempo_input); ?>">
        <br><br>

        <button type="submit" name="calcular">Calcular Coste</button>
    </form>

</body>
</html>