<?php
function usuarioOk($usuario, $contraseña) :bool {
  
   //EL usuario debe tener 8 o mas caracteres
   if(strlen($usuario)<8){
      return false;
   }

   //La contraseña debe ser el nombre de usuario al reves
   return($contraseña === strrev($usuario));
    
}


function limpiarEntrada($texto) :string{
   return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}
