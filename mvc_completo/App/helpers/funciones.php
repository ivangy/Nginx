<?php

    //Para redireccionar la pagina
    function redireccionar($pagina){
        header('location: '.RUTA_URL.$pagina);
    }

    function tienePrivilegios($rol_usuario,$rolesPermitidos){
        // si $rolesPermitidos es vacio, se tendran privilegios
        if (empty($rolesPermitidos) || in_array($rol_usuario, $rolesPermitidos)) {
            return true;
        }
    }