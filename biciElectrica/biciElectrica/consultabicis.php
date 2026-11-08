<?php
require_once("funciones.php");
$tabla = cargarbicis();
if (!empty($_GET['coordx']) && !empty($_GET['coordy'])) {
    $biciRecomendada = bicimascercana($tabla, $_GET['coordx'], $_GET['coordy']);
}

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>MOSTRAR BICIS OPERATIVA</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1> Listado de bicicletas operativas </h1>
    <?= mostrartablabicis($tabla); ?>
    <?php if (isset($biciRecomendada)) : ?>
        <h2> Bicicleta disponible más cercana es <?= $biciRecomendada ?> </h2>
        <button onclick="history.back()"> Volver </button>
    <?php else : ?>
        <h2> Indicar su ubicación: </h2>
                <form>
                    Coordenada X: <input type="number" name="coordx"><br>
                    Coordenada Y: <input type="number" name="coordy"><br>
                    <input type="submit" value=" Consultar ">
                </form>
            <?php endif ?>
</body>

</html>