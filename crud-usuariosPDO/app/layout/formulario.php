
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD DE PRODUCTOS</title>
    <link href="web/default.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="container">
    <div id="header">
       <h1>GESTIÓN DE PRODUCTOS versión 1.1 + BD (PDO)</h1>
    </div>

    <div id="content">
        <form method="POST">
            <table>
                <tr>
                    <td><label for="codigo">Código</label></td>
                    <td>
                        <input type="text" id="codigo" name="codigo"
                               value="<?= $producto->codigo ?>"
                               <?= ($orden == "Detalles" || $orden == "Modificar") ? "readonly" : "" ?>>
                    </td>
                </tr>

                <tr>
                    <td><label for="nombre">Nombre</label></td>
                    <td>
                        <input type="text" id="nombre" name="nombre"
                               value="<?= $producto->nombre ?>"
                               <?= ($orden == "Detalles") ? "readonly" : "" ?>
                               autofocus>
                    </td>
                </tr>

                <tr>
                    <td><label for="stock">Stock</label></td>
                    <td>
                        <input type="number" id="stock" name="stock"
                               value="<?= $producto->stock ?>"
                               <?= ($orden == "Detalles") ? "readonly" : "" ?>>
                    </td>
                </tr>

                <tr>
                    <td><label for="stock_min">Stock mínimo</label></td>
                    <td>
                        <input type="number" id="stock_min" name="stock_min"
                               value="<?= $producto->stock_min ?>"
                               <?= ($orden == "Detalles") ? "readonly" : "" ?>>
                    </td>
                </tr>

                <tr>
                    <td><label for="precio">Precio</label></td>
                    <td>
                        <input type="number" step="any" id="precio" name="precio"
                               value="<?= $producto->precio ?>"
                               <?= ($orden == "Detalles") ? "readonly" : "" ?>>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 20px;">
                <!-- Envia la operacion que queremos realizar -->
                <input type="submit" name="orden" value="<?= $orden ?>">
                <input type="submit" name="orden" value="Volver">
            </div>
        </form>
    </div>
</div>

</body>
</html>
<?php exit(); ?>
