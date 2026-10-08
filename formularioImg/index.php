<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include 'captura.html';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Saneamiento contra inyección de código (XSS)
    $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8') : '';
    $alias = isset($_POST['alias']) ? htmlspecialchars(trim($_POST['alias']), ENT_QUOTES, 'UTF-8') : '';
    $edad = isset($_POST['edad']) ? intval($_POST['edad']) : '';
    
    $armas = isset($_POST['armas']) && is_array($_POST['armas']) ? $_POST['armas'] : [];
    $armas_saneadas = array_map(function($item) {
        return htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    }, $armas);
    $armas_texto = !empty($armas_saneadas) ? implode(', ', $armas_saneadas) : 'Ninguna';
    
    $magia = isset($_POST['magia']) ? htmlspecialchars($_POST['magia'], ENT_QUOTES, 'UTF-8') : 'No';
    
    // 2. Control estricto de la subida del fichero
    $estado_imagen = 'ninguna'; // valores posibles: 'subida', 'ninguna', 'error'
    $ruta_imagen_mostrar = 'calavera.png';
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $file_type = $_FILES['imagen']['type'];
            $file_size = $_FILES['imagen']['size'];
            $file_tmp = $_FILES['imagen']['tmp_name'];
            
            // Validación: Solo formato PNG y tamaño menor o igual a 10 Kbytes (10240 bytes)
            if ($file_type === 'image/png' && $file_size <= 10240) {
                $nombre_archivo = time() . '_' . basename($_FILES['imagen']['name']);
                $directorio_destino = 'uploads/' . $nombre_archivo;
                
                if (move_uploaded_file($file_tmp, $directorio_destino)) {
                    $ruta_imagen_mostrar = $directorio_destino;
                    $estado_imagen = 'subida';
                } else {
                    $estado_imagen = 'error';
                }
            } else {
                $estado_imagen = 'error';
            }
        } else {
            $estado_imagen = 'error';
        }
    }
    
    // 3. Renderizado de la respuesta visual (bloques amarillos)
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Datos del Jugador</title>
        <style>
            body { font-family: Arial, sans-serif; display: flex; justify-content: center; padding: 40px; background-color: #f9f9f9; }
            .resultado-box {
                background-color: #ffff33;
                border: 1px solid #e6e600;
                padding: 20px;
                width: 450px;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                box-shadow: 3px 3px 10px rgba(0,0,0,0.1);
            }
            .datos-col { width: 55%; }
            .imagen-col { width: 40%; text-align: center; display: flex; flex-direction: column; align-items: center; }
            .datos-col h2 { margin-top: 0; font-size: 1.4em; }
            .datos-col p { margin: 8px 0; }
            .bold { font-weight: bold; }
            .avatar { width: 120px; height: 120px; object-fit: contain; border: 1px solid #000; background-color: #fff; margin-top: 5px; }
            .msg-img { font-weight: bold; font-size: 0.9em; min-height: 35px; display: flex; align-items: center; justify-content: center; }
            .msg-error { color: red; font-weight: bold; margin-top: 10px; font-size: 0.95em; }
        </style>
    </head>
    <body>

    <div class="resultado-box">
        <div class="datos-col">
            <h2>Datos del Jugador</h2>
            <p><span class="bold">Nombre:</span> <?php echo $nombre; ?></p>
            <p><span class="bold">Alias:</span> <?php echo $alias; ?></p>
            <p><span class="bold">Edad:</span> <?php echo $edad; ?></p>
            <p><span class="bold">Armas seleccionadas:</span> <?php echo $armas_texto; ?></p>
            <p><span class="bold">¿Practica artes mágicas?:</span> <?php echo $magia; ?></p>
        </div>
        
        <div class="imagen-col">
            <?php if ($estado_imagen === 'subida'): ?>
                <div class="msg-img">Imagen subida:</div>
                <img src="<?php echo $ruta_imagen_mostrar; ?>" alt="Imagen Jugador" class="avatar">
            <?php endif; ?>
            
            <?php if ($estado_imagen === 'ninguna'): ?>
                <div class="msg-img">No se subió ninguna imagen.</div>
                <img src="calavera.png" alt="Calavera" class="avatar">
            <?php endif; ?>
            
            <?php if ($estado_imagen === 'error'): ?>
                <div class="msg-img"></div>
                <img src="calavera.png" alt="Calavera" class="avatar">
                <div class="msg-error">Error al subir la imagen</div>
            <?php endif; ?>
        </div>
    </div>

    </body>
    </html>
    <?php
}
?>