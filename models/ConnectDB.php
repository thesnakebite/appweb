<?php

class ConnectDB
{
    private $host = HOST;
    private $user = USER;
    private $pass = PASSWORD;
    private $db = DB;
    private $port = PORT;
    private $conn;
    private $res;

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

    public function consultarDB(string $sql)
    {
        $this->conectarDB();
        $datos = mysqli_query($this->conn, $sql);
        $this->desconectarDB();
        return $datos;
    }


    private function desconectarDB()
    {
        $this->res = mysqli_close($this->conn);   
    }
}
?>