<?php

class ConectarDB
{
    private $host = HOST;
    private $user = USER;
    private $pass = PASS;
    private $db = DB;
    private $port = PORT;
    private $con;
    private $res;

    private function ConectarDB()
    {
        $this->con = mysqli_connect($this->host, $this->user, $this->pass, $this->db, $this->port);
    }

    public function ConsultarDB(string $sql)
    {
        $this->ConectarDB();
        $datos = mysqli_query($this->con, $sql);
        $this->DesconectarDB();
        return $datos;
    }


    private function DesconectarDB(){
        $this->res = mysqli_close($this->con);
       
    }
}
?>