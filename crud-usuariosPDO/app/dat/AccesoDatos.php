<?php
include_once "app/dat/Producto.php";
include_once "app/config.php";

/*
 *  -> : para acceder a funciones o propiedades de un objeto. 
 * También se usa con $this para referirnos al objeto actual.
:: : para acceder a funciones o propiedades estáticas de una clase.
 */
class AccesoDatos
{

    private static $modelo = null; //es la variable que guarda el objeto AccesoDatos y nos permite llamar a sus funciones.
    private $conexion = null; //guardará la conexión con MySQL.


    public static function getModelo()
    {
        // Si no existe lo crea el acceso de a la BD
        if (self::$modelo == null) { //Comprueba si $modelo es null (si todavía no contiene ningún objeto).
            self::$modelo = new AccesoDatos(); //Si es null, crea un nuevo objeto de la clase AccesoDatos y lo guarda en $modelo.
        }
        return self::$modelo; //Devuelve $modelo, es decir, devuelve el objeto para que podamos utilizar sus funciones.
    }


    // Constructor privado  Patron singleton, se accede por getModelo

    private function __construct()
    {

        try {
            //indica dónde está la base de datos y qué tipo de conexión utilizamos.
            $dsn = "mysql:host=" . SERVER_DB . ";dbname=" . DATABASE . ";charset=utf8";

            // crea la conexión utilizando el DSN, el usuario y la contraseña.
            $this->conexion = new PDO($dsn, DB_USER, DB_PASSWD);

            // // Si ocurre un error en una consulta, PDO genera una excepción
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión " . $e->getMessage();
            exit();
        }
    }

    // // Cierra la conexión con la base de datos y libera el objeto AccesoDatos.
    public static function closeModelo()
    {
        if (self::$modelo != null) {
            $objetoAcceso = self::$modelo;
            $objetoAcceso->conexion = null;     // Cierro la conexión
            self::$modelo = null; // Borro el objeto.
        }
    }


    // 1.Para obtener todos los porductos guardados en la tabla productos
    public function getProductos(): array
    {
        // 0.Creamos un array vacío donde iremos guardando todos los productos que obtengamos de MySQL.
        $tablaProductos = [];

        // 1. Aquí guardamos la consulta que queremos realizar.
        $consultaProductos = "SELECT * FROM productos";

        // 2. utilizamos la conexión que tenemos guardada en $this->conexion para preparar la consulta.
        $buscarProductos = $this->conexion->prepare($consultaProductos);

        // 3. EJECUTAMOS LA CONSULTA
        $buscarProductos->execute();

        // 4. Indicamos que los resultados de MySQL se conviertan en objetos de la clase indicada.
        //SI LA CONSULTA DEVUELVE 3 PROD, se crearan 3 objetos de la clase Producto.
       $buscarProductos->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Producto');

        // 4. Recogemos los productos de uno en uno con fetch() y los guardamos en el array.
        while ($producto = $buscarProductos->fetch()) { // fetch recoge un producto de la consulta que hemos hecho a MySQL

            $tablaProductos[] = $producto;
        }

        return $tablaProductos;
    }

    // 2. PARA OBTENER UN SOLO PRODUCTO EN CONCRETO
    public function getProducto(int $codigo)
    {
        // 1. POR AHORA NO HEMOS BUSCADO POR LO QUE ES FALSE
        $producto = false;

        // 2. GUARDAMOS LA CONSULTA EN buscarProducto y la preparamos
        $buscarProducto = $this->conexion->prepare("select * from productos where codigo = :codigo");

        //// 3. Indicamos que los resultados de la consulta se convertirán en objetos de la clase Producto.
        $buscarProducto->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Producto');

        // // 4. Pasamos el código del producto a la consulta para buscarlo.
        $buscarProducto->bindParam(':codigo', $codigo);

        // 5. COMPRUEBA SI LA CONSULTA SE HA EJECUTADO CORRECTAMENTE
        if ($buscarProducto->execute()) {
            //GUARDA EN PRODUCTO EL PRODCUTO ENCONTRADO
            $producto = $buscarProducto->fetch();
        }

        return $producto;
    }

    /*
     bindParam() → vinculamos el marcador a una variable. bindParam(':codigo', $codigo);
     bindValue() → vinculamos el marcador a un valor directamente, que puede ser un número, 
     un texto o un atributo de un objeto. bindValue(':codigo', $producto->codigo);
    */
    // UPDATE
    public function modificarProducto(Producto $producto): bool
    {
        // 1. Guardamos la consulta UPDATE.  
        $consultaActualizarProducto  = ("update productos set nombre=:nombre, stock=:stock, stock_min=:stock_min
        , precio =:precio where codigo=:codigo");

        // 2. Preparamos la consulta.
        $modificarProducto = $this->conexion->prepare($consultaActualizarProducto);

        // 3. Pasamos los atributos del objeto Producto a los marcadores de la consulta.
        $modificarProducto->bindValue(':codigo', $producto->codigo);
        $modificarProducto->bindValue(':nombre', $producto->nombre);
        $modificarProducto->bindValue(':stock', $producto->stock);
        $modificarProducto->bindValue(':stock_min', $producto->stock_min);
        $modificarProducto->bindValue(':precio', $producto->precio);

        // 4. Ejecutamos la consulta para modificar el producto.
        $modificarProducto->execute();

        //Si rowCount() devuelve 1, significa que se ha modificado una fila y devolvemos true.
        if ($modificarProducto->rowCount() == 1) {
            $resultado = true;
        } else {
            $resultado = false; //Si devuelve 0, significa que no se ha modificado ninguna fila y devolvemos false.
        }
        return $resultado;
    }

    //INSERT, añadir un producto nuevo a la tabla productos.
    public function addProducto(Producto $producto): bool
    {
        $añadirProducto  = $this->conexion->prepare("insert into productos (codigo,nombre,stock,stock_min,precio) Values(:codigo, :nombre, :stock, :stock_min, :precio)");


        $añadirProducto->bindValue(':codigo', $producto->codigo);
        $añadirProducto->bindValue(':nombre', $producto->nombre);
        $añadirProducto->bindValue(':stock', $producto->stock);
        $añadirProducto->bindValue(':stock_min', $producto->stock_min);
        $añadirProducto->bindValue(':precio', $producto->precio);

        $añadirProducto->execute();

        if($añadirProducto->rowCount() == 1){
            $resultado=true;
        }else{
            $resultado=false;
        }

        return $resultado;
    }

    //DELETE
    public function deleteProducto(int $codigo): bool
    {
        $borrarProducto = $this->conexion->prepare("delete from productos where codigo = :codigo");

        $borrarProducto->bindValue(':codigo', $codigo);

        $borrarProducto->execute();

        
        if($borrarProducto->rowCount() == 1){
            $resultado=true;
        }else{
            $resultado=false;
        }

        return $resultado;
    }

    // Evito que se pueda clonar el objeto. (SINGLETON)
    public function __clone()
    {
        trigger_error('La clonación no permitida', E_USER_ERROR);
    }
}
