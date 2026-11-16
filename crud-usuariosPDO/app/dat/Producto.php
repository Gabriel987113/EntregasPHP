<?php

class Producto{

private $codigo;
private $nombre;
private $stock;
private $stock_min;
private $precio;

// = null permite crear el objeto sin pasarle datos.
// $this->propiedad = parametro asigna el valor al atributo del objeto.
public function __construct($codigo = null, $nombre = null, $stock = null, $stock_min = null, $precio = null){
    $this->codigo = $codigo;
    $this->nombre = $nombre;
    $this->stock = $stock;
    $this->stock_min = $stock_min;
    $this->precio = $precio;
}

// GET MAGICO
    public function __get($atributo) {
        return $this->$atributo;
    }

    // SET MAGICO
    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }
}

?>