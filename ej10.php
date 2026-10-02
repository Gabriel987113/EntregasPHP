<?php

/**
 * Cinco dados - cinco-dados.php
 *
 * @author Alberto López
 *
 */
define('NUMDADOS', 5);

// Caracteres UTF8( de dados 1 a 6)
$tcharDados = [
  1 => "&#9856;", 2 => "&#9857;",
  3 => "&#9858;", 4 => "&#9859;",
  5 => "&#9860;",  6 => "&#9861;"
];



/* Funciones auxiliares */
          
/**
 *  Genera un array con valores de dados 1..6
 * @param int $numdados - tamaño de array generado
 * @return int[] array generado
 */
function generarDados(int $numdados): array
{
    $dados = [];
    for( $i=0; $i<$numdados; $i++){
        $dado=rand(1,6);
        $dados[]=$dado;
    }
  // Cambiar siempre se genera los mismo valores.
  return $dados;
}

/**
 * Calcula el valor de los datos
 * Suma de todos los valores menos el mas alto y el mas bajo
 * @param array $tdados
 * @return int
 */
function calcularPuntos( array $dados): int
{
  $maximo=max($dados);
  $posMaxima=array_search($maximo, $dados); //esto devuelve la posicion

  $minimo=min($dados);
  $posMinima=array_search($minimo,$dados);

  $dados[$posMaxima]=0;
  $dados[$posMinima]=0;

  return array_sum($dados);
}

/**
 * Gemera um mensaje indicando el jugador ganador o si ha habido empate
 * @param int $puntos1  - puntos del primer jugador
 * @param int $puntos2  - puntos del segundo jugador
 * @return string - Mensaje generado
 */
function generarMensajeGanador(int $puntos1, int $puntos2): string
{
  
  if($puntos1 > $puntos2){
    return "gana el jugador 1";
  }else if($puntos1<$puntos2){
    return " gana el jugador 2";
  }else{
    return " hay empate";
  }
}

// Función que genera un mensaje para múltiples ganadores
// Recibe un lista de parámetros variables
/**
 *   Genera el hmtl con la imagne asociado a el valor del dado
 * @param array $tdados - valores de los dados
 * @return string - cadena html donde se incluye el caracter asociado a valor de cada dado
 */
function generarImagenes( array $dados): string
{
  $msg = "";
  global $tcharDados;
    // COMPLETAR SOLO MUESTRA EL VALOR DE UN DADO 

   foreach($dados as $dado){
    $msg .= "<span style='font-size:100px;'>" . $tcharDados[ $dado] . "</span>";
   }
  
  return $msg;
}

/* Programa principal */

$dadosJugador1 = generarDados(NUMDADOS);
$dadosJugador2 = generarDados(NUMDADOS);
$puntosJugado1 = calcularPuntos($dadosJugador1);
$puntosJugado2 = calcularPuntos($dadosJugador2);


$msgGanador    = generarMensajeGanador($puntosJugado1, $puntosJugado2);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>
    Cinco dados.

  </title>

</head>

<body>
  <h1>Cinco dados</h1>

  <p>Actualice la página para mostrar una nueva tirada.</p>


  <table>
    <tbody>
      <tr>
        <th>Jugador 1</th>
        <td style="padding: 10px; background-color: red;">
          <?= generarImagenes($dadosJugador1); ?>

        </td>
        <th> <?= $puntosJugado1; ?> puntos</th>
      </tr>
      <tr>
        <th>Jugador 2</th>
        <td style="padding: 10px; background-color: blue;">
          <?= generarImagenes($dadosJugador2); ?>

        </td>
        <th> <?= $puntosJugado2 ?> puntos</th>
      </tr>
      <tr>
        <th>Resultado</th>
        <td><?= $msgGanador ?></td>
      </tr>
    </tbody>
  </table>

  <footer>
    <p><u>By Alberto López</u></p>
  </footer>
</body>

</html>