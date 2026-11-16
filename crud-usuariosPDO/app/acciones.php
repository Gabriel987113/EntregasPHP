
<?php
require_once "app/dat/Producto.php";
require_once "app/dat/AccesoDatos.php";

// BORRAR UN PRODUCTO
function accionBorrar($codigo){
    $db = AccesoDatos::getModelo(); // Obtiene el objeto de acceso a datos
    $resultado = $db->deleteProducto($codigo); // Borra el producto por su codigo
}

// CERRAR SESION Y VOLVER AL INICIO
function accionTerminar(){
    AccesoDatos::closeModelo(); // Cierra la conexion con la base de datos
    session_destroy(); // Destruye la sesion
    header("Refresh:0 url='./index.php'"); // Vuelve al inicio
}

// MOSTRAR FORMULARIO PARA DAR DE ALTA
function accionAlta(){
    $producto = new Producto(); // Crea un objeto Producto
    $producto->codigo = ""; // Inicializa el codigo vacio
    $producto->nombre = ""; // Inicializa el nombre vacio
    $producto->stock = ""; // Inicializa el stock vacio
    $producto->stock_min = ""; // Inicializa el stock minimo vacio
    $producto->precio = ""; // Inicializa el precio vacio
    $orden = "Nuevo"; // Indica que vamos a crear un producto
    include_once "layout/formulario.php"; // Muestra el formulario
}

// MOSTRAR LOS DETALLES DE UN PRODUCTO
function accionDetalles($codigo){
    $db = AccesoDatos::getModelo(); // Obtiene el objeto de acceso a datos
    $producto = $db->getProducto($codigo); // Busca el producto por su codigo
    $orden = "Detalles"; // Indica que vamos a mostrar los detalles
    include_once "layout/formulario.php"; // Muestra el formulario
}

// MOSTRAR FORMULARIO PARA MODIFICAR
function accionModificar($codigo){
    $db = AccesoDatos::getModelo(); // Obtiene el objeto de acceso a datos
    $producto = $db->getProducto($codigo); // Obtiene el producto que queremos modificar
    $orden = "Modificar"; // Indica que vamos a modificar un producto
    include_once "layout/formulario.php"; // Muestra el formulario
}

// RECOGER DATOS DEL FORMULARIO Y DAR DE ALTA
function accionPostAlta(){
    $producto = new Producto(); // Crea un objeto Producto
    $producto->codigo = $_POST['codigo']; // Recoge el codigo del formulario
    $producto->nombre = $_POST['nombre']; // Recoge el nombre
    $producto->stock = $_POST['stock']; // Recoge el stock
    $producto->stock_min = $_POST['stock_min']; // Recoge el stock minimo
    $producto->precio = $_POST['precio']; // Recoge el precio

    $db = AccesoDatos::getModelo(); // Obtiene el objeto de acceso a datos
    $db->addProducto($producto); // Inserta el producto en la base de datos
}

// RECOGER DATOS DEL FORMULARIO Y MODIFICAR
function accionPostModificar(){
    $producto = new Producto(); // Crea un objeto Producto
    $producto->codigo = $_POST['codigo']; // Recoge el codigo del formulario
    $producto->nombre = $_POST['nombre']; // Recoge el nombre
    $producto->stock = $_POST['stock']; // Recoge el stock
    $producto->stock_min = $_POST['stock_min']; // Recoge el stock minimo
    $producto->precio = $_POST['precio']; // Recoge el precio

    $db = AccesoDatos::getModelo(); // Obtiene el objeto de acceso a datos
    $db->modificarProducto($producto); // Actualiza el producto en la base de datos
}
