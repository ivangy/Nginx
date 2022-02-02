<?php

    class Usuario {
        private $db;

        public function __construct(){
            $this->db = new Base;
        }


        public function obtenerUsuarios(){
            $this->db->query("SELECT * FROM usuarios");

            return $this->db->registros();
        }


        public function obtenerRoles(){
            $this->db->query("SELECT * FROM roles");

            return $this->db->registros();
        }


        public function agregarUsuario($datos){
            $this->db->query("INSERT INTO usuarios (nombre, email, telefono, id_rol) 
                                        VALUES (:nombre, :email, :telefono, :id_rol)");

            //vinculamos los valores
            $this->db->bind(':nombre',$datos['nombre']);
            $this->db->bind(':email',$datos['email']);
            $this->db->bind(':telefono',$datos['telefono']);
            $this->db->bind(':id_rol',$datos['id_rol']);

            //ejecutamos
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }


        public function obtenerUsuarioId($id){
            $this->db->query("SELECT * FROM usuarios WHERE id_usuario = :id");
            $this->db->bind(':id',$id);

            return $this->db->registro();
        }


        public function actualizarUsuario($datos){
            $this->db->query("UPDATE usuarios SET nombre=:nombre, email=:email, telefono=:telefono, id_rol=:id_rol
                                                WHERE id_usuario = :id");

            //vinculamos los valores
            $this->db->bind(':id',$datos['id_usuario']);
            $this->db->bind(':nombre',$datos['nombre']);
            $this->db->bind(':email',$datos['email']);
            $this->db->bind(':telefono',$datos['telefono']);
            $this->db->bind(':id_rol',$datos['id_rol']);

            //ejecutamos
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }


        public function borrarUsuario($id){
            $this->db->query("DELETE FROM usuarios WHERE id_usuario = :id");
            $this->db->bind(':id',$id);

            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

///////////////////////////////////////////////// Sesion //////////////////////////////////////////////

        public function obtenerSesionesUsuario($id){
            $this->db->query("SELECT * FROM sesiones 
                                        WHERE id_usuario = :id
                                        ORDER BY fecha_inicio");
            $this->db->bind(':id',$id);

            return $this->db->registros();
        }


        public function cerrarSesion($id_sesion){
            $this->db->query("UPDATE sesiones SET fecha_fin = NOW()  
                                    WHERE id_sesion = :id_sesion");

            $this->db->bind(':id_sesion',$id_sesion);

            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }
    }
