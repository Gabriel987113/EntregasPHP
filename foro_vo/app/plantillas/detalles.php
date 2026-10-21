<?php

//cOM`PROBAMOS si nos ha llegado el comentario desde el formulario
if(isset($_REQUEST['comentario'])){
    $texto=$_REQUEST['comentario'];
}else{
    $texto="";
}

//Calculamos la longitud
$numCaracteres=strlen($texto);

//Calculamos el numero de palabras
$numPalabras=str_word_count($texto);

//Que letra se repite mas?
    //Quitamos los espacios para que no se cuenten como letra
    $texto_sin_espacios=str_replace(" ","",$texto);

    //Cuantas veces sale cada caracter
    $lista_letras=count_chars($texto_sin_espacios, 1);

    //Ordenamos la lista de mayor a menor,La QUE MAS SE REPITE ESTARA PRIMERA
    arsort($lista_letras); //asort para menor a mayor

    //Sacamos el nombre de la primera letra del array
    $letra_mas_repetida=chr(key($lista_letras));//key encuentra el primer valor

//La palabra que mas se repite
    //Lo guardamos en un array las palabras y lo ponemmos todo en minuscula
    $array_palabras = str_word_count(mb_strtolower($texto),1);

    //Contamos cuantas veces se repite cada palabra de la lista
    $lista_palabras=array_count_values($array_palabras); //["pepe" => 2, "hola" => 1]

    //ordenamos de mayor a menor
    arsort($lista_palabras);

    //Sacamos el nombre de la palabra que se ha quedado en el primer puesto
    $palabra_mas_repetida=key($lista_palabras);


?>

<div>
<b> Detalles:</b><br>
<table>
<tr><td>Longitud:</td>
    <td><?= $numCaracteres ?></td>
</tr>
<tr><td>Nº de palabras:</td>
    <td><?= $numPalabras ?></td>
</tr>
<tr>
    <td>Letra + repetida:  </td>
    <td><?= $letra_mas_repetida ?></td>
</tr>
<tr>
    <td>Palabra + repetida:</td>
    <td><?= $palabra_mas_repetida ?></td>
</tr>
</table>
</div>



