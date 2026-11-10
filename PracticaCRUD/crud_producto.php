
<?php

// ---------------------------------------------------------
// 1. CLASE MODELO (Entidad)
// ---------------------------------------------------------

class Producto {
    public $id;
    public $nombre;
    public $precio;
    public $stock;

    // Método auxiliar para mostrar info
    public function info() {
        return "[ID: {$this->id}] {$this->nombre} - Precio: {$this->precio}$ (Stock: {$this->stock})";
    }
}


// ---------------------------------------------------------
// 2. CLASE REPOSITORIO (Maneja la lógica PDO)
// ---------------------------------------------------------

class ProductoRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // --- CREATE (Insertar) ---
    public function crear(Producto $producto) {
        $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)";
        /*
         $this->pdo: accedemos a la conexión con MySQL que guardamos en el repositorio.
         prepare($sql): preparamos la consulta SQL.
         $stmt: guardamos la consulta preparada.
        */
        $stmt = $this->pdo->prepare($sql);

        //4. Introducimos los valores con bindValue();
        $stmt->bindValue(':nombre', $producto->nombre);
        $stmt->bindValue(':precio', $producto->precio);
        $stmt->bindValue(':stock', $producto->stock);

        //ejecutamos la consulta
        $stmt->execute();

        //lastInsertId() obtiene el último ID que MySQL ha generado 
        //automáticamente al insertar el producto.
        $producto->id = $this->pdo->lastInsertId();
        return $producto->id;
    }

    // --- READ (Leer Todos) ---
    public function obtenerTodos() {
        $sql = "SELECT * FROM productos";
        //query($sql) ejecuta directamente la consulta SQL que tenemos guardada en $sql.
        $stmt = $this->pdo->query($sql);

        /*
         - $stmt->fetchAll() recoge todos los registros que ha devuelto la consulta SQL.
         - PDO::FETCH_CLASS indica que queremos convertir cada registro en un objeto de una clase.
         - 'Producto' indica qué clase queremos utilizar para crear esos objetos.
         Por ejemplo, si la tabla tiene 3 productos, fetchAll() devolverá un array con 3 objetos de la clase Producto.
        */
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    // --- READ (Leer Uno) ---
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        /*
         fetchAll() → recupera todos los registros.
         fetch() → recupera un solo registro.
        */

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Producto');
        return $stmt->fetch();
    }

    // --- UPDATE (Actualizar) ---
    public function actualizar(Producto $producto) {
        /*
        UPDATE productos: indica que queremos modificar la tabla productos.
        SET: indica qué columnas queremos modificar.
        nombre = :nombre, precio = :precio, stock = :stock: son los nuevos valores.
        WHERE id = :id: indica qué producto queremos modificar, identificándolo por su ID
        */
        $sql = "UPDATE productos SET nombre = :nombre, precio = :precio, stock = :stock WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        //4. Asigna los valores a los parámetros:
        $stmt->bindValue(':nombre', $producto->nombre);
        $stmt->bindValue(':precio', $producto->precio);
        $stmt->bindValue(':stock', $producto->stock);
        $stmt->bindValue(':id', $producto->id);

        return $stmt->execute();
    }

    // --- DELETE (Eliminar) ---
    public function eliminar($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        //rowCount() devuelve el número de filas que han sido afectadas por la consulta SQL.
        return $stmt->rowCount();
    }
}


// ---------------------------------------------------------
// 3. EJEMPLO DE USO (Script Principal)
// ---------------------------------------------------------

// Configuración de conexión
/*
Aquí indicamos a qué base de datos queremos conectarnos.
- mysql: indica el tipo de base de datos.
- host=localhost: indica que MySQL está en nuestro propio ordenador.
- dbname=tienda_demo: indica el nombre de nuestra base de datos.
- charset=utf8mb4: indica la codificación de caracteres.
*/
$dsn = 'mysql:host=localhost;dbname=tienda_demo;charset=utf8mb4';
$user = 'root';
$pass = '';

try {
    // A. Conexión
    // PDO = permite conectar PHP con una base de datos.
    // $dsn = indica el tipo de BD, el servidor, su nombre y la codificación.
    // $user = usuario de la base de datos.
    // $pass = contraseña.
    // new PDO() = establece la conexión utilizando esos datos.
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Instanciamos el repositorio
    /*
    $repo: es la variable que almacena el objeto de la clase ProductoRepository,
    cuyos métodos utilizamos para gestionar los productos.
    */
    $repo = new ProductoRepository($pdo);

    echo "<h2>--- Inicio del CRUD ---</h2>";

    // 1. CREATE: Crear un nuevo producto
    $nuevoProd = new Producto();
    $nuevoProd->nombre = "Teclado Mecánico";
    $nuevoProd->precio = 59.99;
    $nuevoProd->stock = 10;

    $idGenerado = $repo->crear($nuevoProd);
    echo "Producto creado: " . $nuevoProd->info() . "<br>";

    // 2. READ: Listar todos
    echo "<br><strong>Lista de productos:</strong><br>";
    $lista = $repo->obtenerTodos();

    foreach ($lista as $p) {
        echo "- " . $p->info() . "<br>";
    }

    // 3. UPDATE: Modificar el producto creado
    $prodParaEditar = $repo->obtenerPorId($idGenerado);

    if ($prodParaEditar) {
        $prodParaEditar->precio = 45.00;
        $prodParaEditar->stock = 9;

        $repo->actualizar($prodParaEditar);
        echo "<br>Producto actualizado (Nuevo precio: 45.00$).<br>";
    }

    // 4. READ (Verificación): Vemos el cambio
    $prodVerificado = $repo->obtenerPorId($idGenerado);
    echo "Verificación: " . $prodVerificado->info() . "<br>";

    // 5. DELETE: Borrar el producto
    $filas = $repo->eliminar($idGenerado);

    if ($filas > 0) {
        echo "<br>Producto eliminado correctamente.";
    }

} catch (PDOException $e) {
    echo "Error Grave: " . $e->getMessage();
}

// Desconexión
$pdo = null;

?>
