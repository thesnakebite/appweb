<?php

class TaskDB extends ConnectDB
{

    public function consultarTareasDB()
    {

        $sql = "SELECT * FROM `app_tareas`";
        $resultado = $this->consultarDB($sql);
        $total = mysqli_num_rows($resultado);

        if ($total > 0) {
            $tareas = [];

            while ($fila = mysqli_fetch_assoc($resultado)) {
                $tareas[] = $fila;
            }
            return $tareas;
        } else {
            return [];  // Devolvemos un array vacío
        }
    }

    public function addTareas($datos)
    {
        // Si estado no está definido, establecerlo a 0
        if (!isset($datos['estado'])) {
            $datos['estado'] = 0;
        }

        $sql = "INSERT INTO `app_tareas` (id, nombre, tiempo, estado) VALUES (NULL, '{$datos['nombre']}', '{$datos['tiempo']}', '{$datos['estado']}')";
        $resultado = $this->consultarDB($sql);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function consultarTareaById(int $id)
    {
        $sql = "SELECT * FROM `app_tareas` WHERE id = '{$id}'";
        $resultado = $this->consultarDB($sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // Convertir el resultado a un array asociativo
            return mysqli_fetch_assoc($resultado);
        } else {
            return false;
        }
    }

    public function updateTareasDB($datos)
    {
        $sql = "UPDATE `app_tareas` SET nombre = '{$datos['nombre']}', tiempo = '{$datos['tiempo']}', estado = '{$datos['estado']}' WHERE id = '{$datos['id']}'";
        $resultado = $this->consultarDB($sql);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTareasDB(int $id)
    {
        $sql = "DELETE FROM `app_tareas` WHERE id = '{$id}'";
        $resultado = $this->consultarDB($sql);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }
}