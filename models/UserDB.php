<?php
require_once('./models/ConnectDB.php');

class UserDB extends ConnectDB
{

    private $validar = false;
    private $nombre;
    private $email;
    private $password;
    private $foto;
    private $conectado;
    private $estado;
    private $tabla;


    private function validarUsuario($datos)
    {
        if (isset($datos['email']) && !empty($datos['email'])) {
            $sql = "SELECT `email` FROM `app_usuarios` WHERE `email` = '" . $datos['email'] .  "'";
            $res = $this->consultarDB($sql);
            $resultado = mysqli_num_rows($res);

            return $resultado == 0; // Retorna true si el email no existe
        }
        
        return false;
    }

    public function loginUser($datos)
    {
        $sql = "SELECT * FROM `app_usuarios` WHERE  `email` = '" . $datos['email'] . "'";

        $datos = $this->consultarDB($sql);

        $total = mysqli_num_rows($datos);

        if ($total > 0) {

            foreach ($datos as $item)

                return $item;
        } else {
            return false;
        }
    }

    public function addUsuarios($datos)
    {
        if ($this->validarUsuario($datos)) {
            
            // Asegurarnos de que el estado tenga un valor por defecto
            $datos['estado'] = isset($datos['estado']) ? $datos['estado'] : '0';
            
            // Si hay contraseña, aplicamos md5
            if (isset($datos['password'])) {
                $datos['password'] = md5($datos['password']);
            }
            
            // Si no hay foto, establecemos un valor vacío
            if (!isset($datos['foto'])) {
                $datos['foto'] = '';
            }
            
            // Eliminar campos que no existen en la tabla
            if (isset($datos['redirect_to'])) {
                unset($datos['redirect_to']);
            }
            if (isset($datos['confirm_email'])) {
                unset($datos['confirm_email']);
            }
            if (isset($datos['confirm_password'])) {
                unset($datos['confirm_password']);
            }
            
            // Creamos una instancia de DataDB para app_usuarios
            $dataDB = new DataDB('app_usuarios');
            
            // Usamos el método guardarDatosDB para insertar el registro
            $resultado = $dataDB->guardarDatosDB($datos);
            
            return $resultado;
        }
        return false;
    }

    public function getUsuarios()
    {
        $sql = 'SELECT * FROM `app_usuarios` ORDER BY `id` ASC';

        $datos = $this->consultarDB($sql);

        $total = mysqli_num_rows($datos);

        if ($total > 0) {
            $i = 0;

            foreach ($datos as $item) {
                $usuarios[$i] = $item;
                $i++;
            }

            return $usuarios;
        } else {
            return -1;
        }
    }

    public function getUsuarioPorId($id) 
    {
        $sql = "SELECT * FROM `app_usuarios` WHERE `id` = " . intval($id);
        $resultado = $this->consultarDB($sql);
        
        if (mysqli_num_rows($resultado) > 0) {
            return mysqli_fetch_assoc($resultado);
        }
        
        return null;
    }

    public function updateUser($datos) 
    {
        // Construir SQL para actualizar
        $sql = "UPDATE `app_usuarios` SET 
                `nombre` = '" . $datos['nombre'] . "',
                `email` = '" . $datos['email'] . "',
                `estado` = '" . (isset($datos['estado']) ? $datos['estado'] : '0') . "'";
        
        // Si se proporcionó una nueva contraseña
        if (isset($datos['password']) && !empty($datos['password'])) {
            $sql .= ", `password` = '" . md5($datos['password']) . "'";
        }
        
        // Verificar si hay foto
        if (isset($datos['foto']) && !empty($datos['foto'])) {
            $sql .= ", `foto` = '" . $datos['foto'] . "'";
        }
        
        // Completar la consulta con WHERE al final
        $sql .= " WHERE `id` = " . intval($datos['id']);
        
        // Ejecutar la consulta
        return $this->consultarDB($sql);
    }

    public function deleteUser($id)
    {
        $id = intval($id);

        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM `app_usuarios` WHERE `id` = " . $id;

        return $this->consultarDB($sql);
    }

    public function updateStateDB($id)
    {
        $sql = "SELECT `estado` FROM `app_usuarios` WHERE `id` = " . intval($id);

        $resultado = $this->consultarDB($sql);

        foreach ($resultado as $item) {
            $estado = $item['estado'];
        }

        if ($estado == 1) {
            $estado = 0;
        } else {
            $estado = 1;
        }

        $sql = "UPDATE `app_usuarios` SET `estado` = " . $estado . " WHERE `id` = " . intval($id);

        return $this->consultarDB($sql);
    }

    public function updateConnected($id, $conectado)
    {
        $sql = 'UPDATE `app_usuarios` SET `conectado` =' . $conectado . ' WHERE `id` = ' . intval($id);

        return $this->consultarDB($sql);
    }

    public function updatePhoto($fileData)
    {
        if (isset($fileData['foto']) && $fileData['foto']['error'] === UPLOAD_ERR_OK) {
            
            // Creamos un directorio para las fotos
            $directorio = 'storage/users/';

            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            
            // Generar un nombre único para el archivo
            $nombreArchivo = uniqid() . '_' . $fileData['foto']['name'];
            $rutaCompleta = $directorio . $nombreArchivo;
            
            // Mover el archivo subido
            if (move_uploaded_file($fileData['foto']['tmp_name'], $rutaCompleta)) {
                // Devolvemos la ruta donde se guardó la foto
                return $rutaCompleta;
            }
        }

        return false;
    }
}