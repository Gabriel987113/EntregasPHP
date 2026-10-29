<?php
session_start(); //Nos permite guardar datos, como el dinero disponible, mientras juega.

//BOTON DE VOLVER A JUGAR---------------------------------------------------------------------------------------------
if(isset($_POST["volver a jugar"])){ //isset($_POST["volver"])
  unset($_SESSION["dineroDisponible"]); //elimina el saldo guardado de la sesión.
}

//CONTAR LAS VISITAS -------------------------------------------------------------------------------------------------
if (!isset($_SESSION["dineroDisponible"])) {

    if (isset($_COOKIE["visitas"])) {
        $visitas = $_COOKIE["visitas"];
    } else {
        $visitas = 0;
    }

    $visitas = $visitas + 1;

    setcookie("visitas", $visitas, time() + 30 * 24 * 60 * 60);
}

//VER EL DINERO DISPONIBLE --------------------------------------------------------------------------------------------
if(isset($_POST["cantidadini"]) && !isset($_SESSION["dineroDisponible"])){ //si el usu ha introduciod la cant y aun no hay dinero inicial
   $cantidadInicial = $_POST["cantidadini"]; //cantidadInicial sera igual a lo que ponga el usuario
  
  if($cantidadInicial <= 0){
    $mensaje = "Introduce una cantidad mayor que 0";
    require_once "form_bienvenida.php";
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
$mensaje="";
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
require_once  "form_apuesta.php";
?>




