<?php
/** 
 * @web http://www.jc-mouse.net/
 * @author jc mouse
 */
class PeopleDB {
    
    protected $mysqli;
    const LOCALHOST = 'localhost';
    const USER = 'ssegycom_admin';
    const PASSWORD = '100%kaka';
    const DATABASE = 'ssegycom_webservices';
    
    /**
     * Constructor de clase
     */
    public function __construct() {           
        try{
            //conexión a base de datos
            $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
        }catch (mysqli_sql_exception $e){
            //Si no se puede realizar la conexión
            http_response_code(500);
            exit;
        }     
    } 


    /**
     * obtiene todos los registros de la tabla "people"
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getPeoples(){        
        $result = $this->mysqli->query('SELECT * FROM usuarios');          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
    }

    
    /**
     * obtiene un solo registro dado su ID
     * @param int $id identificador unico de registro
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getPeople($id=0){      
        $stmt = $this->mysqli->prepare("SELECT id, nombre, apellido, email FROM usuarios WHERE id=? ; ");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_NUM); 
        $stmt->close();
        return $peoples;              
    }
    
    
    
    /**
     * añade un nuevo registro en la tabla persona
     * @param String $name nombre completo de persona
     * @return bool TRUE|FALSE 
     */
    public function insert($data=''){
        
        list($nombre,$apellido,$usuario,$pas) = explode(",", $data);
        $name = trim($nombre);
        $lastname = trim($apellido);
        $email = trim($email);
        $user = trim($usuario);
        $pass = md5(trim($pas));
        $stmt = $this->mysqli->prepare("INSERT INTO usuarios(nombre, apellido, email, usuario, password) VALUES ('$name','$lastname','$email','$user','$pass');");
        $stmt->bind_param('s', $data);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;        
    }
    
    /**
     * elimina un registro dado el ID
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function delete($id=0) {
        $stmt = $this->mysqli->prepare("DELETE FROM usuarios WHERE id = ? ; ");
        $stmt->bind_param('s', $id);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;
    }
    
    /**
     * Actualiza registro dado su ID
     * @param int $id Description
     */
    public function update($id, $newdata) {
        if($this->checkID($id)){
        list($nombre,$apellido,$usuario,$pas) = explode(",", $newdata);
        $name = trim($nombre);
        $lastname = trim($apellido);
        $user = trim($usuario);
        $pass = md5(trim($pas));


            $stmt = $this->mysqli->prepare("UPDATE usuarios SET nombre=?, apellido=?,  usuario=?, password=? WHERE id = ?;");
            $stmt->bind_param('ssssi',$name,$lastname,$user,$pass,$id);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;    
        }
        return false;
    }
    
    /**
     * verifica si un ID existe
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function checkID($id){
        $stmt = $this->mysqli->prepare("SELECT * FROM usuarios WHERE id=?");
        $stmt->bind_param("s", $id);
        if($stmt->execute()){
            $stmt->store_result();    
            if ($stmt->num_rows == 1){                
                return true;
            }
        }        
        return false;
    }


    


    
}
