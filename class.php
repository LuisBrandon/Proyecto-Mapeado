<?php

class Datos
{
    //Variables
    private $address;
    private $latlng;
    private $latlngDestino;
    private $addressDestino;

    public function __construct()
    {
        

        if (isset($_GET['address'])) {
            $this->address = $_GET['address']; //Recojo la dirección si me viene
        } else {
            $this->address = "Calle del transporte, Guadalcacin, 11591"; //Valor de la variable por defecto si no nos viene la dirección
        }

        if (isset($_GET['latlng'])) {
            $this->latlng = $_GET['latlng']; //Recojo la lat y lng si me vienen
        } else {
            $this->latlng = null;
        }

        if (isset($_GET['latlngdestino'])) { //Recojo la lat y lng destino si me vienen
            $this->latlngDestino = $_GET['latlngdestino'];
        } else {
            $this->latlngDestino = null;
        }

        if (isset($_GET['addressdestino'])) { //Recojo la lat y lng destino si me vienen
            $this->addressDestino = $_GET['addressdestino'];
        } else {
            $this->addressDestino = "Calle del transporte, Guadalcacin, 11591";
        }

    }

    public function getAddress()
    {
        return $this->address; //Devuelvo la dirección (direccion, cp, poblacion)
    }

    public function getlatlng()
    {
        return $this->latlng; //Devuelvo la lat y lng (en una cadena)
    }

    public function getLatlngDestino()
    {
        return $this->latlngDestino; //Devuelvo la lat y lng destino (en una cadena)
    }

    public function getAddressDestino()
    {
        return $this->addressDestino;
    }
}
