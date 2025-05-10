<?php

class ConnectDB
{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $port;
    private $conn;
    private $res;

    public function __construct()
    {
        $this->host = HOST;
        $this->user = USER;
        $this->pass = PASSWORD;
        $this->db = DB;
        $this->port = PORT;

        $this->conectarDB();
    }

    private function conectarDB()
    {
        $this->conn = mysqli_connect(
            $this->host, 
            $this->user, 
            $this->pass, 
            $this->db, 
            $this->port
        );
    }

    public static function conectorDB()
    {
        return $conn = mysqli_connect(
            HOST,
            USER,
            PASSWORD,
            DB,
            PORT
        );
    }

    public function consultarDB(string $sql)
    {
        try {
            // Conectamos a la DB para esta consulta
            $this->conectarDB();

            // Ejecutamos la consulta SQL
            $datos = mysqli_query($this->conn, $sql);

            // Desconectamos a la DB
            $this->desconectarDB();
            
            // Verificamos si los datos obtenidos son válidos
            if(isset($datos) and !empty($datos) and is_object($datos)) {
                return $datos;  // Devolvemos el conjunto de resultados
            } else {
                return $this->res;  // Devolvemos el resultado de la desconexión
            }
            
        } catch (\Exception $e) {
            // En caso de error, nos aseguramos de cerrar la conexión
            $this->desconectarDB();
            // Devolvemos la excepción para su manejo
            return $e;
        }
    }


    private function desconectarDB()
    {
        
            $this->res = mysqli_close($this->conn);
        
    }
}

class DataDB extends ConnectDB
{
    private $table_one;
    private $table_two;
    private $validate;

    public function __construct(string $table1, string $table2 = '')
    {
        parent::__construct();

        $this->table_one = $table1;
        $this->table_two = $table2;
    }

    
}