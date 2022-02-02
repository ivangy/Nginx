<?php

    class Inicio extends Controlador{

        public function __construct(){

        }

        public function index(){
            if (Sesion::sesionCreada($this->datos)){
                $this->vista('inicio',$this->datos);
            } else {
                $this->vista('inicio_no_logueado');
            }
        }

    }
