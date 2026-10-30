<?php
//isset() comprueba si una variable existe.
session_start(); //Nos permite guardar datos, como el dinero disponible, mientras juega.
$mensaje="";
$visitas="";
$dineroFinal="";
//BOTON DE VOLVER A JUGAR Y CONTAR LAS VISITAS---------------------------------------
if(isset($_POST["volverJugar"])){ 
  unset($_SESSION["dineroDisponible"]); //elimina el saldo guardado de la sesión.

  if (isset($_COOKIE["visitas"])) {
        $visitas = $_COOKIE["visitas"];
    } else {
        $visitas = 0;
    }

    $visitas = $visitas + 1;

    setcookie("visitas", $visitas, time() + 30 * 24 * 60 * 60);

    $_SESSION["visitaContada"] = true; //ponemos true para que no entre en el if de abajo
    //y vuelva a contar la visita.

  require_once "form_bienvenida.php"; //muestra la pagina de bienvenida.
  exit(); //detiene completamente la ejecución del código PHP en ese momento.
}

//CONTAR LAS VISITAS --------------------------------------------------------------
if (!isset($_SESSION["visitaContada"])) {//Si todavía NO existe la variable visitaContada, entra y cuenta una visita

    if (isset($_COOKIE["visitas"])) { // Comprueba si ya existe la cookie.
        $visitas = $_COOKIE["visitas"]; // Recupera el número de visitas guardado.
    } else {
        $visitas = 0; // Si no existe, empezamos desde 0.
    }

    $visitas = $visitas + 1;

    setcookie("visitas", $visitas, time() + 30 * 24 * 60 * 60); // Guarda las visitas durante 30 días.

    $_SESSION["visitaContada"] = true;// Crea la variable de sesion y le asigna true.
    // Asi recuerda que ya hemos contado la visita y no vuelve a entrar en este if.
}

//VER EL DINERO DISPONIBLE --------------------------------------------------------------------------------------------
if(isset($_POST["cantidadini"]) && !isset($_SESSION["dineroDisponible"])){ //si el usu ha introduciod la cant y aun no hay dinero inicial
   $cantidadInicial = $_POST["cantidadini"]; //cantidadInicial sera igual a lo que ponga el usuario
  
  if($cantidadInicial <= 0){
    $mensaje = "Introduce una cantidad mayor que 0";
    require_once "form_bienvenida.php"; //muestra la pagina de bienvenida y permite poner las variables .
    exit();
  }else{
    $_SESSION["dineroDisponible"] = $cantidadInicial;
  }
}

//MOSTRAR LA BIENVENIDA SI NO HA ENVIADO AUN EL FORMULARIO --------------------------------------------------------------
if(!isset($_SESSION["dineroDisponible"])){ //comprueba que todavía no tenemos dinero guardado.
  require_once "form_bienvenida.php"; //muestra el formulario de bienvenida.
  exit(); //detiene el resto del código para que el usuario no llegue todavía a las apuestas.
}


//QUE OCURRE CUANDO LE DA A APOSTAR ? ------------------------------------------------------------------------------------

if (isset($_POST["apostar"])) { //comprueba si se ha enviado el formulario pulsando el botón Apostar cantidad.

    $cantidadApostada=$_POST["cantidad"]; // cantidadApostada es lo que el usuario ha apostado.

    if($cantidadApostada <= 0 || $cantidadApostada >= $_SESSION["dineroDisponible"]){ 
        $mensaje="ERROR: NO PUEDES APOSTAR MAS DINERO DEL QUE TIENES";
    }else{ 
      $numero=rand(1,2);
      if($numero==1){
       $resultado = "PAR"; //tiene que ser igual al name del html,el input tiene como value PAR.
      }else{
      $resultado = "IMPAR";
      }

      if($resultado == $_POST["apuesta"]){ //$resultado contiene el resultado aleatorio: "PAR" o "IMPAR".
         $mensaje = "Has acertado!! Ha salido " . $resultado;
         $_SESSION["dineroDisponible"] = $_SESSION["dineroDisponible"] + $cantidadApostada;
      }else{
      $_SESSION["dineroDisponible"] = $_SESSION["dineroDisponible"] - $cantidadApostada;
      $mensaje = "Has fallado. Ha salido " . $resultado;
      }
    }
    
}

// SI ABANDONA O NO LE QUEDA DINERO ------------------------------------------------------------------------------
 if (isset($_POST["dejar"]) || ($_SESSION["dineroDisponible"] <= 0) ) {    
   $dineroFinal = $_SESSION["dineroDisponible"]; // este es el dinero final que se mostrara cuando se vaya.
   require_once "form_despedida.php";
   exit();
} 


// Muestro el formulario si puede seguir apostando
require_once  "form_apuesta.php"; //Esto hace que se muestre el formulario de apuestas.
?>