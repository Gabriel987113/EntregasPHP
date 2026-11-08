<?php
require_once("BiciElectrica.php");

// Leer el fichero csv y devuelve una tabla de bicicletas
function cargarbicis(): array
{
    $fich = @fopen("Bicis.csv", "r");
    if ($fich == false) {
        die("Error al abrir el fichero");
    }
    $tabla = [];

    while ($valor = fgetcsv($fich)) {
        list($id, $cx, $cy, $bat, $op) = $valor;
        $bici = new BiciElectrica($id, $cx, $cy, $bat, $op);
        $tabla[] = $bici;
    }

    return $tabla;
}
// Devuelve una cadena con la tabla html de bicis operativas
function mostrartablabicis($tabla): string
{
    $cadena = "<table><tr><th>Id</th><th>Coord X</th><th>Coord Y</th><th>Bateria</th></tr>";
   // Recorre las bicis: en cada vuelta, $bici es una bici distinta de $tabla
    foreach($tabla as $bici){
        if($bici->operativa == true){
         $cadena.="<tr><td>" . $bici->id . "</td>" .
         "<td>" . $bici->coordx . "</td>" . 
         "<td>" . $bici->coordy . "</td>" . 
         "<td>" . $bici->bateria . "%" . "</td></tr>";
        }
    }
    $cadena .="</table>";

    return $cadena;
}


/**
 * Devuelve la bici con menor distancia a las coordenadas de usuario. 
 * @param $tabla - tabla de bicicletas
 * @param $x - Coordenada x de usuario
 * @param $y - Coordenada x de usuario
 * @return bicicleta más cercana o null
 */
function bicimascercana($tabla, $x, $y)
{
    $biciCerca = null; // La mejor bici hasta ahora (todavía ninguna)
    $distanciaMin = null;   // La distancia de esa bici, para comparar con las siguientes

    foreach($tabla as $bici){
        // Solo se tienen en cuenta las operativas
        if($bici->operativa==true){
            // Distancia de esta bici al usuario ( el método de la clase).
            $dist=$bici->distancia($x,$y);
            if($biciCerca===null || $distanciaMin>$dist){
                $biciCerca = $bici; // Guarda la bici (el objeto entero)
                $distanciaMin=$dist;  // Guarda su distancia para la próxima comparación
            }
        }
    }
    //// Devuelve la bici más cercana (o null si no había ninguna operativa)
    return $biciCerca;
}

