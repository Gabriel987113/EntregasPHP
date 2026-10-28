<?php
session_start();

// Manejo de la sesión con dos valores
// 'cliente' => nombre del cliente
// 'pedidos' => array asociativo fruta => cantidad

// Nuevo cliente: anoto en la sesión su nombre y creo su tabla de pedidos vacía
if (isset($_GET['cliente']) && !empty(trim($_GET['cliente']))) {
    $_SESSION['cliente'] = htmlspecialchars(trim($_GET['cliente']));
    $_SESSION['pedidos'] = [
        'Platanos' => 0,
        'Naranjas' => 0,
        'Limones'  => 0,
        'Manzanas' => 0
    ];
}

// No hay definido un cliente todavía en la session 
if (!isset($_SESSION['cliente'])) {
    require_once 'bienvenida.php';
    exit; // Detiene la ejecución para que solo muestre la bienvenida
}

// Variable bandera para saber si el cliente terminó su pedido
$mostrarDespedida = false;

// Proceso las acciones 
if (isset($_POST["accion"])) {
    // Usamos trim() porque tus botones contienen espacios en blanco en el 'value' (" Anotar ", etc.)
    switch (trim($_POST["accion"])) {
        case "Anotar":
            if (isset($_POST['fruta'], $_POST['cantidad'])) {
                $fruta = $_POST['fruta'];
                $cantidad = (int)$_POST['cantidad'];
                // Actualizo la tabla de pedidos en la sesión acumulando el valor
                if (array_key_exists($fruta, $_SESSION['pedidos']) && $cantidad > 0) {
                    $_SESSION['pedidos'][$fruta] += $cantidad;
                }
            }
            break;
            
        case "Anular":
            // Vacío la tabla de pedidos en la sesión (reseteo a 0)
            if (isset($_SESSION['pedidos'])) {
                foreach ($_SESSION['pedidos'] as $fruta => $cantidad) {
                    $_SESSION['pedidos'][$fruta] = 0;
                }
            }
            break;
            
        case "Terminar":
            // Indicamos que se debe cargar la vista de despedida
            $mostrarDespedida = true;
            break;
    }
}

// Generamos la tabla con el estado actual o final de los pedidos
$compraRealizada = htmlTablaPedidos();

// Enrutamos a la vista correspondiente
if ($mostrarDespedida) {
    // Primero guardamos los datos del resumen final en otra variable para no perderlos al borrar la sesión
    $compraFinal = $compraRealizada;
    
    // Destruyo la sesión para que el botón de JS del nuevo cliente inicie limpio
    session_unset();
    session_destroy();
    
    require_once 'despedida.php';
} else {
    require_once 'compra.php';
}

// Función auxiliar que genera una tabla HTML a partir de la tabla de pedidos
// Almacenada en la sesión
function htmlTablaPedidos(): string
{
    $msg = "";
    if (isset($_SESSION['pedidos'])) {
        $tieneItems = false;
        $msg .= "<table>";
        $msg .= "<thead><tr><th>Fruta</th><th>Cantidad</th></tr></thead>";
        $msg .= "<tbody>";
        
        foreach ($_SESSION['pedidos'] as $fruta => $cantidad) {
            if ($cantidad > 0) {
                $tieneItems = true;
                $msg .= "<tr><td>" . htmlspecialchars($fruta) . "</td><td>" . $cantidad . " unidades</td></tr>";
            }
        }
        
        if (!$tieneItems) {
            $msg .= "<tr><td colspan='2' style='text-align:center;'>La cesta está vacía</td></tr>";
        }
        
        $msg .= "</tbody></table>";
    }
    return $msg;
}