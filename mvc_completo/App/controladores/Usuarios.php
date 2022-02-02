<?php

    class Usuarios extends Controlador{

        public function __construct(){
            Sesion::iniciarSesion($this->datos);
            $this->datos['rolesPermitidos'] = [1,2];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/');
            }

            $this->usuarioModelo = $this->modelo('Usuario');

            $this->datos['menuActivo'] = 1;         // Definimos el menu que sera destacado en la vista
            
        }


        public function index(){
            //Obtenemos los usuarios
            $usuarios = $this->usuarioModelo->obtenerUsuarios();

            $this->datos['usuarios'] = $usuarios;

            $this->vista('usuarios/inicio',$this->datos);
            // $this->vista('usuarios/inicioVue',$this->datos);
        }


        public function agregar(){
            $this->datos['rolesPermitidos'] = [1];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/usuarios');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $usuarioNuevo = [
                    'nombre' => trim($_POST['nombre']),
                    'email' => trim($_POST['email']),
                    'telefono' => trim($_POST['telefono']),
                    'id_rol' => trim($_POST['rol']),
                ];

                if ($this->usuarioModelo->agregarUsuario($usuarioNuevo)){
                    redireccionar('/usuarios');
                } else {
                    die('Algo ha fallado!!!');
                }
            } else {
                $this->datos['usuario'] = (object) [
                    'nombre' => '',
                    'email' => '',
                    'telefono' => '',
                    'id_rol' => 3
                ];

                $this->datos['listaRoles'] = $this->usuarioModelo->obtenerRoles();

                $this->vista('usuarios/agregar_editar',$this->datos);
            }
        }


        public function editar($id){
            $this->datos['rolesPermitidos'] = [1];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/usuarios');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $usuarioModificado = [
                    'id_usuario' => $id,
                    'nombre' => trim($_POST['nombre']),
                    'email' => trim($_POST['email']),
                    'telefono' => trim($_POST['telefono']),
                    'id_rol' => trim($_POST['rol']),
                ];

                if ($this->usuarioModelo->actualizarUsuario($usuarioModificado)){
                    redireccionar('/usuarios');
                } else {
                    die('Algo ha fallado!!!');
                }
            } else {
                //obtenemos información del usuario y el listado de roles desde del modelo
                $this->datos['usuario'] = $this->usuarioModelo->obtenerUsuarioId($id);
                $this->datos['listaRoles'] = $this->usuarioModelo->obtenerRoles();

                $this->vista('usuarios/agregar_editar',$this->datos);
            }
        }


        public function borrar($id){
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->usuarioModelo->borrarUsuario($id)){
                    redireccionar('/usuarios');
                } else {
                    die('Algo ha fallado!!!');
                }
            } else {
                //obtenemos información del usuario desde del modelo
                $this->datos['usuario'] = $this->usuarioModelo->obtenerUsuarioId($id);

                $this->vista('usuarios/borrar',$this->datos);
            }
        }

        
        public function sesiones($id_usuario){
            $this->datos['rolesPermitidos'] = [1];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
                exit();
            }

            // En __construct() verificamos que se haya iniciado la sesion
            $sesiones = $this->usuarioModelo->obtenerSesionesUsuario($id_usuario);
            $usuario = $this->usuarioModelo->obtenerUsuarioId($id_usuario);

            // utilizamos $datos en lugar de $this->datos ya que no necesitamos los datos del usuario de sesion
            $datos['sesiones'] = $sesiones;
            $datos['usuario'] = $usuario;

            $this->vistaApi($datos);
        }


        public function cerrarSesion(){
            $this->datos['rolesPermitidos'] = [1];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
                exit();
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id_sesion = $_POST['id_sesion'];
                
                $resultado = $this->usuarioModelo->cerrarSesion($id_sesion);

                unlink(session_save_path().'\\sess_'.$id_sesion);
                $this->vistaApi($resultado);
            }
        }
    }
