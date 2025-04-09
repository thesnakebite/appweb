<?php
$RUTA_AB = $_SERVER['DOCUMENT_ROOT'].'/';
require_once($RUTA_AB.'config.php');
require_once($RUTA_AB.'model/user.php');
$usuarios = new Usuarios();


if($_POST){
    if(isset($_POST['action']) && !empty($_POST['action'])){
        if($_POST['action'] == 'REG_USUARIOS'){      
            $rowid = $usuarios->registrarUsuario($_POST);                
            $msn = $usuarios->SubirFoto($_FILES,$rowid);
            header('location:'.RUTA_WEB.'index.php?views=users&msn='.$msn);                  
        }
        if($_POST['action'] == 'LOGIN_USER'){
            $msn = $usuarios->loginUsuarios($_POST);  
            header('location:'.RUTA_WEB.'index.php?views=dashboard&msn='.$msn);               
        }
        if($_POST['action'] == 'UPDATE_USUARIOS'){
            $msn = $usuarios->updateUsuario($_POST); 
            if(isset($_POST['rowid']) && !empty($_POST['rowid'])){
                $rowid = $_POST['rowid'];
                $msn = $usuarios->SubirFoto($_FILES,$rowid);
                header('location:'.RUTA_WEB.'index.php?views=users&msn='.$msn);           
            }               
        }

    }       
}

if($_GET){  
    
    if(isset($_GET['action']) && !empty($_GET['action'])){
        if($_GET['action'] == 'CERRAR_SESSION'){
            $usuarios->cerrarSesion();
        }

        if($_GET['action'] == 'borraruser'){
            $rowid = base64_decode($_GET['id']);
            $usuarios->DeleteUsuario($rowid);
        }

        if($_GET['action'] == 'MODIFCAR_ESTADO'){
            $rowid = htmlspecialchars(trim($_GET['rowid']));
            $usuarios->ModificarEstado($rowid);
            
        }        
                 
    }
}


class Usuarios{

    private $formreg;
    private $formregedit;
    private $formlogin;
    private $userDB;
    private $table;
    private $dtUser;
    private $ruta_web;

    public function __construct(){
        $this->ruta_web = RUTA_WEB.'uploads/fotos_users/';
        $this->userDB = new UsuariosDB();
        $this->setFormReg();
        $this->setFormLogin();
        $this->setTable();
    }

    // Método para manejar registros
    public function registrarUsuario(Array $postData) {
        if (!isset($postData['action']) || $postData['action'] !== 'REG_USUARIOS') {
            return;
        }

        // Sanitizamos los datos
        $datos = [];
        foreach ($postData as $key => $value) {
            if ($key !== 'action') {
                $datos[$key] = htmlspecialchars(trim($value));
            }
        }     

        // Guardamos el usuario en la base de datos
        $res = $this->userDB->AddUsuariosDB($datos);
        
        return $res; 
        /*
            ?  '<div class="alert alert-success">Datos Guardados Correctamente</div>'
            :  '<div class="alert alert-danger">Datos No Guardados Correctamente</div>';
        */
    }

    public function updateUsuario(Array $postData){
        if (!isset($postData['action']) && $postData['action'] !== 'UPDATE_USUARIOS') {
            return;
        }

        // Sanitizamos los datos
        $datos = [];
        foreach ($postData as $key => $value) {
            if ($key !== 'action') {
                $datos[$key] = htmlspecialchars(trim($value));
            }
        }     

        // Guardamos el usuario en la base de datos
        $res = $this->userDB->UpdateUsuariosDB($datos);

        return ($res == 1) 
            ?  '<div class="alert alert-success">Datos Actualizados Correctamente</div>'
            :  '<div class="alert alert-danger">Datos No Actualizados Correctamente</div>';
    }

    public function loginUsuarios(Array $postData){
        if (!isset($postData['action']) || $postData['action'] !== 'LOGIN_USER') {
            return;
        }

        // Sanitizamos los datos
        $datos = [];
        foreach ($postData as $key => $value) {
            if ($key !== 'action') {
                $datos[$key] = htmlspecialchars(trim($value));
            }
        }

        // Consultamos el usuario en la base de datos
        $dtUser = $this->userDB->ConsultarUsuariosNombre($datos);

        if ($dtUser === -1) {
            return '<div class="alert alert-danger">No existe el Usuario</div>';
        }

        if ($dtUser['estado'] == 1) {
            if (MD5($datos['pass']) == $dtUser['pass']) {
                $_SESSION['user'] = $dtUser['email'];
                $_SESSION['nombre'] = $dtUser['nombre'];
                $_SESSION['rowid'] = $dtUser['rowid'];
                $this->userDB->ModificarConnectDB($dtUser['rowid']);
                return '<div class="alert alert-success">Inicio de sesión exitoso</div>';
            } else {
                return '<div class="alert alert-danger">Contraseña incorrecta</div>';
            }
        } else {    

            return '<div class="alert alert-danger">El Usuario no tiene permisos</div>';
        }
    }

    public function cerrarSesion(){
        $this->userDB->ModificarConnectDB($_SESSION['rowid']);
        session_destroy();
        unset($_SESSION);
        return;
    }

    private function setFormReg(){
        $this->formreg = '
            <form id="FormReg" class="form-control" method="POST" action="index.php" enctype="multipart/form-data" >
                <h3>Formulario de registro:</h3>
                <input id ="user" name="nombre" type="text" class="form-control" placeholder="Usuario:" minlength="3" maxlength="10" required />
                <input id="pass1" type="password" class="form-control" placeholder="Password:" required />
                <input id="pass2" name="pass" type="password" class="form-control" placeholder="Repetir password:" required />
                <input id="mail1" type="email"  class="form-control" placeholder="E-mail:" required />
                <input id="mail2" name="email" type="mail"  class="form-control" placeholder="repetir E-mail:" required />
                <input type="file" name="foto" class="form-control" placeholder="Subir una foto:" />
                <input id="conectado" type="hidden" name="conectado" value="0" />
                <input id="estado" type="hidden" name="estado" value="0" />
                <input type="hidden" name="action" value="REG_USUARIOS" />
                <input class="btn btn-success" type="submit" value="REGISTRO" />
                <input class="btn btn-danger" type="reset" value="RESET" />
            </form>   
        ';
    }

    
    private function setFormRegEdit(){

        $this->formregedit = '            
            <form id="FormReg" class="form-control container" method="POST" action="index.php" enctype="multipart/form-data" >
                <div class="row">
                    <div class="col-lg-4">
                        <img id="foto" src="'.$this->ruta_web.$this->dtUser['foto'].'" style="width:100%" >
                    </div>
                    <div class="col-lg-8">
                        <h3>Formulario de actualización:</h3>
                        <input id ="user" name="nombre" type="text" class="form-control" placeholder="Usuario:" minlength="3" maxlength="10" value="'.$this->dtUser['nombre'].'" required />
                        <input id="pass1" type="password" class="form-control" placeholder="Password:" />
                        <input id="pass2" name="pass" type="password" class="form-control" placeholder="Repetir password:" />
                        <input id="mail1" type="email"  class="form-control" placeholder="E-mail:" value="'.$this->dtUser['email'].'" required />
                        <input id="mail2" name="email" type="mail"  class="form-control" placeholder="repetir E-mail:" value="'.$this->dtUser['email'].'" required />
                        <input id="files" type="file" name="foto" class="form-control" placeholder="Subir una foto:" value="'.$this->dtUser['foto'].'"  />
                        <input id="conectado" type="hidden" name="conectado" value="0" />
                        <input id="estado" type="hidden" name="estado" value="'.$this->dtUser['estado'].'" />
                        <input type="hidden" name="action" value="UPDATE_USUARIOS" />
                        <input type="hidden" name="rowid" value="'.$this->dtUser['rowid'].'" />
                        <input class="btn btn-success" type="submit" value="ACTUALIZAR" />
                        <input class="btn btn-danger" type="reset" value="RESET" />
                    </div>
                </div>
            </form> 
            <script>
                var files = document.getElementById("files");
                files.onchange = function(event){

                }
                function PrecargarFoto(nomfoto){
                    alert(nomfoto);
                    var foto = document.getElementById("foto");
                    foto.src = nomfoto;
                }
            </script>
        ';
    }

    private function setFormLogin(){
        $this->formlogin = '
            <form class="form-control" method="POST" action="index.php">
                <h3>Formulario de acceso:</h3>
                <input name="user" type="email" class="form-control" placeholder="Usuario:" required />
                <input name="pass" type="password" class="form-control" placeholder="Password:" required />
                <input type="hidden" name="action" value="LOGIN_USER" />
                <input class="btn btn-success" type="submit" value="LOGIN" />
                <input class="btn btn-danger" type="reset" value="RESET" />
            </form>           
        ';
    }

    public function getFormReg(){            
        return $this->formreg;
    }

    public function getFormRegEdit(){ 
        if(isset($_GET['views']) && !empty($_GET['views'])){
            if($_GET['views'] == 'users'){
                if(isset($_GET['action']) && !empty($_GET['action'])){
                    if($_GET['action'] == 'edituser'){
                        $rowid = base64_decode($_GET['id']);
                        $this->dtUser = $this->userDB->ConsultarUserIdDB($rowid);
                    }
                }

            }

        }  
        $this->setFormRegEdit();         
        return $this->formregedit;
    }

    public function getFormLogin(){            
        return $this->formlogin;
    }

    private function setTable(){
        $this->table = '
            <table class="table table-striped table-dark table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Rowid</th>
                        <th>Nombre</th>
                        <th>E-mail</th>
                        <th>Conectado</th>
                        <th>Estado</th>
                        <th></th>
                        <th></th>                           
                    </tr>
                </thead>
                <tbody>';

                $dtUsuarios = $this->userDB->ConsultarUsuarios();
                $i=1;
                foreach($dtUsuarios as $dtUsuario){
                    /* '.$dtUsuario['estado'].' */

                    ($dtUsuario['estado'] == 1) ? $checked = 'checked' : $checked='';
                    ($dtUsuario['conectado'] == 1) ? $color = 'green' : $color='red';
                    $img  = $this->ruta_web.$dtUsuario['foto'];
                    $this->table .= '
                    <tr>                        
                        <td><img src="'.$img.'" style="width:50px;"></td>
                        <td>'.$i.'</td>
                        <td>'.$dtUsuario['nombre'].'</td>
                        <td>'.$dtUsuario['email'].'</td>
                        <td><i class="fa-solid fa-lightbulb" style="color:'.$color.'"></i></td>
                        <td>
                            <div class="form-check form-switch">
                                <input  class="form-check-input" type="checkbox" role="switch" id="est-'.$dtUsuario['rowid'].'" onchange="ModificarEstado(\''.$dtUsuario['rowid'].'\')" '.$checked.'>                                   
                            </div>
                        </td>
                        <td><a class="btn btn-success" href="index.php?views=users&action=edituser&id='.base64_encode($dtUsuario['rowid']).'"><i class="fa-solid fa-pen-to-square "></i></a></td>
                        <td><a class="btn btn-danger" href="index.php?action=borraruser&id='.base64_encode($dtUsuario['rowid']).'"><i class="fa-solid fa-trash "></i></a></td>
                                              
                    </tr>
                    ';
                    $i++;
                }
                
        $this->table .= '        
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">Total de usuarios:'.count($dtUsuarios).'</td>
                    </tr>
                </tboot>
            </table>
            <script>
                    function ModificarEstado(id){
                    
                        Dato = {
                            "rowid":id,
                            "action":"MODIFCAR_ESTADO"
                        }

                        $.get("controllers/user.php", Dato ,function(res){
                        
                            var salida = document.getElementById("salidas");
                            if(res == 1){
                                salida.innerHTML = \'<div class="alert alert-success mensaje">Estado actualizado Correctamente</div> \';
                            }
                            else
                            {
                                salida.innerHTML = \'<div class="alert alert-danger mensaje">Estado actualizado Correctamente</div> \';
                            }
                            
                            
                        })

                    }
            </script>
        
        ';
    }

    public function getTable(){
        return $this->table;
    }

    public function DeleteUsuario(Int $rowid){
        $res = $this->userDB->DeleteUserDB($rowid);
        return ($res == 1) 
        ?  '<div class="alert alert-success">Datos Borrado correctamente</div>'
        :  '<div class="alert alert-danger">Datos No Borrado Correctamente</div>';

    }

    public function ModificarEstado(Int $rowid){
        // llamada al modelo.
        echo $res = $this->userDB->ModificarEstadoDB($rowid);
        
    }

    public function SubirFoto(Array $fileData, Int $rowid){
        $nombre_archivo = $fileData['foto']['name'];
        $tipo_archivo = $fileData['foto']['type'];
        $tamano_archivo= $fileData['foto']['size'];
        $ruta_tmp = $fileData['foto']['tmp_name'];

        $ruta = RUTA_AB.'/uploads/fotos_users/';

       

        $nombre_final = 'foto-usuarios-'.$rowid.'.jpg';

        $res = $this->userDB->ActualizarFoto($nombre_final, $rowid);
        if($res == 1){
            // Comprobar si el directorio existe, si no , crear.

            if(!is_dir($ruta)){
                mkdir($ruta, 0777, true);
            }

            // Mover el fichero  de la carpeta tmp a la carpeta del servidor

            if($fileData['foto']['size'] > 0 or $fileData['foto']['size'] < 50000){
                if(move_uploaded_file($ruta_tmp,$ruta.$nombre_final)){                    
                    return '<div class="alert alert-success">Usuario guardado y fichero subido correctamente</div>';
                }
                else
                {                  
                    return '<div class="alert alert-danger">Error al subir el fichero</div>';
                }
            }
            else
            {                
                return '<div class="alert alert-danger">Tamaño de fichero no autorizado</div>';
            }
        }



    }

   
}




?>